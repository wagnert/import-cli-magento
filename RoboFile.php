<?php

/**
 * RoboFile.php
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 */

use Lurker\Event\FilesystemEvent;

use Symfony\Component\Finder\Finder;
use AppserverIo\RoboTasks\AbstractRoboFile;
use Robo\Robo;

/**
 * Defines the available build tasks.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 *
 * @SuppressWarnings(PHPMD)
 */
class RoboFile extends AbstractRoboFile
{

    /**
     * Configuration key for the directories.
     *
     * @var string
     */
    const DIRS = 'dirs';

    /**
     * Configuration key for the source directory.
     *
     * @var string
     */
    const SRC = 'src';

    /**
     * Configuration key for the destination directory.
     *
     * @var string
     */
    const DEST = 'dest';

    /**
     * Configuration key for the deploy directory.
     *
     * @var string
     */
    const DEPLOY = 'deploy';

    /**
     * Configuration key for the docker configuration.
     *
     * @var string
     */
    const DOCKER = 'docker';

    /**
     * Configuration key for the docker target container name.
     *
     * @var string
     */
    const TARGET_CONTAINER = 'target-container';

    /**
     * Returns the deploy directory.
     *
     * @return string The directory to deploy the sources to
     */
    protected function getDeployDir()
    {
        return Robo::config()->get(sprintf('%s.%s', RoboFile::DIRS, RoboFile::DEPLOY));
    }

    /**
     * Returns the name of the docker target container.
     *
     * @return string The docker target container
     */
    protected function getTargetContainer()
    {
        return Robo::config()->get(sprintf('%s.%s', RoboFile::DOCKER, RoboFile::TARGET_CONTAINER));
    }

    /**
     * Returns the Magento 2 root directory inside the docker container.
     *
     * @return string The Magento 2 root directory
     */
    protected function getDockerMagentoRootDir()
    {
        return Robo::config()->get(sprintf('%s.%s.%s', RoboFile::DOCKER, RoboFile::DIRS, RoboFile::DEPLOY));
    }

    /**
     * Returns the synchronization source directory inside the docker container.
     *
     * @return string The synchronization source directory
     */
    protected function getDockerSyncSrcDir()
    {
        return Robo::config()->get(sprintf('%s.%s.%s', RoboFile::DOCKER, RoboFile::DIRS, RoboFile::SRC));
    }

    /**
     * Returns the synchronization destination directory inside the docker container.
     *
     * @return string The synchronization destination directory
     */
    protected function getDockerSyncDestDir()
    {
        return Robo::config()->get(sprintf('%s.%s.%s', RoboFile::DOCKER, RoboFile::DIRS, RoboFile::DEST));
    }

    /**
     * Run's the composer install command.
     *
     * @return void
     */
    public function composerInstall()
    {
        // optimize autoloader with custom path
        $this->taskComposerInstall()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    /**
     * Run's the composer update command.
     *
     * @return void
     */
    public function composerUpdate()
    {
        // optimize autoloader with custom path
        $this->taskComposerUpdate()
             ->preferDist()
             ->optimizeAutoloader()
             ->run();
    }

    /**
     * Clean up the environment for a new build.
     *
     * @return void
     */
    public function clean()
    {
        $this->taskDeleteDir($this->getTargetDir())->run();
    }

    /**
     * Prepare's the environment for a new build.
     *
     * @return void
     */
    public function prepare()
    {
        $this->taskFileSystemStack()
             ->mkdir($this->getTargetDir())
             ->mkdir($this->getReportsDir())
             ->run();
    }

    /**
     * Run's the PHPMD.
     *
     * @return void
     */
    public function runMd()
    {

        // run the mess detector
        $this->_exec(
            sprintf(
                '%s/bin/phpmd %s xml phpmd.xml --reportfile %s/reports/pmd.xml --ignore-violations-on-exit',
                $this->getVendorDir(),
                $this->getSrcDir(),
                $this->getTargetDir()
            )
        );
    }

    /**
     * Run's the PHPCPD.
     *
     * @return void
     */
    public function runCpd()
    {

        // run the copy past detector
        $this->_exec(
            sprintf(
                '%s/bin/phpcpd %s --log-pmd %s/reports/pmd-cpd.xml',
                $this->getVendorDir(),
                $this->getSrcDir(),
                $this->getTargetDir()
            )
        );
    }

    /**
     * Run's the PHPCodeSniffer.
     *
     * @return void
     */
    public function runCs()
    {

        // run the code sniffer
        $this->_exec(
            sprintf(
                '%s/bin/phpcs -n --report-full --extensions=php --standard=phpcs.xml --report-checkstyle=%s/reports/phpcs.xml %s',
                $this->getVendorDir(),
                $this->getTargetDir(),
                $this->getSrcDir()
            )
        );
    }

    /**
     * Run's the PHPUnit tests.
     *
     * @return void
     */
    public function runTests()
    {

        // run PHPUnit
        $this->taskPHPUnit(sprintf('%s/bin/phpunit', $this->getVendorDir()))
             ->configFile('phpunit.xml')
             ->run();
    }

    /**
     * Deploy's the extension to it's target directory.
     *
     * @return void
     */
    public function deploy()
    {
        $this->taskCopyDir(array($this->getSrcDir() => $this->getDeployDir()))->run();
    }

    /**
     * Deploy's the extension to it's target directory in the specified docker container.
     *
     * @return void
     */
    public function dockerDeploy()
    {

        // copy the file itself
        $this->taskExec('docker')
             ->arg('cp')
             ->arg(sprintf('%s/app', $this->getSrcDir()))
             ->arg(sprintf('%s:%s', $this->getTargetContainer(), $this->getDeployDir()))
             ->run();
    }

    /**
     * Start's the synchronization between the local sources and the Magento 2 instance
     * inside the container.
     *
     * @return void
     */
    public function dockerSync()
    {

        // copy the sources to the container
        $this->dockerDeploy();

        // start syncing the sources
        $this->taskExec('docker')
             ->arg('exec')
             ->arg($this->getTargetContainer())
             ->arg('bash')
             ->arg('-c')
             ->arg(
                 sprintf(
                     'cd %s && vendor/bin/robo sync %s %s',
                     $this->getDockerMagentoRootDir(),
                     $this->getDockerSyncSrcDir(),
                     $this->getDockerSyncDestDir()
                 )
             )
             ->run();
    }

    /**
     * Invokes the Magento 2 setup:upgrade command inside the docker container.
     *
     * @params array $args The arguments to pass to the bin/magento script inside the docker container
     *
     * @return void
     */
    public function dockerMagento(array $args)
    {

        // if not argument has been passed, execute the info command
        if (sizeof($args) === 0) {
            $args = array('help');
        }

        // start syncing the sources
        $this->taskExec('docker')
             ->arg('exec')
             ->arg($this->getTargetContainer())
             ->arg('bash')
             ->arg('-c')
             ->arg(sprintf('cd %s && chmod +x bin/magento && bin/magento %s', $this->getDockerMagentoRootDir(), implode(' ', $args)))
             ->run();
    }

    /**
     * Invokes the passed Composer command inside the Magento root directory of the docker container.
     *
     * @params array $args The arguments to pass to the composer script inside the docker container
     *
     * @return void
     */
    public function dockerComposer(array $args)
    {

        // if not argument has been passed, execute the info command
        if (sizeof($args) === 0) {
            $args = array('help');
        }

        // start syncing the sources
        $this->taskExec('docker')
             ->arg('exec')
             ->arg($this->getTargetContainer())
             ->arg('bash')
             ->arg('-c')
             ->arg(sprintf('cd %s && composer %s', $this->getDockerMagentoRootDir(), implode(' ', $args)))
             ->run();
    }

    /**
     * The complete build process.
     *
     * @return void
     */
    public function build()
    {
        $this->clean();
        $this->prepare();
        $this->runCs();
        $this->runCpd();
        $this->runMd();
        $this->runTests();
    }
}
