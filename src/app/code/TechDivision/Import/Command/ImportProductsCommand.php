<?php

/**
 * TechDivision\Import\Command\ImportProductsCommand
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

namespace TechDivision\Import\Command;

use Psr\Log\LoggerInterface;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Filesystem\DirectoryList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TechDivision\Import\App\Magento;
use TechDivision\Import\Utils\LoggerKeys;
use TechDivision\Import\Utils\EntityTypeCodes;
use TechDivision\Import\Model\ConfigurationLoader;
use TechDivision\Import\Configuration\Jms\Configuration;

/**
 * The command implementation to import products.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 */
class ImportProductsCommand extends Command
{

    /**
     * Object manager to create various objects.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $objectManager;

    /**
     * The importer configuration loader.
     *
     * @var \TechDivision\Import\Model\ConfigurationLoader
     */
    private $configurationLoader;

    /**
     * The directory list instance.
     *
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * The system logger instance.
     *
     * @var \Psr\Log\LoggerInterface
     */
    private $systemLogger;

    /**
     * The application instance.
     *
     * @var \TechDivision\Import\App\Magento
     */
    private $app;

    /**
     * Constructor to initialize the command.
     *
     * @param \TechDivision\Import\App\Magento               $app                 The import application instance
     * @param \Psr\Log\LoggerInterface                       $systemLogger        The system logger instance
     * @param \TechDivision\Import\Model\ConfigurationLoader $configurationLoader The configuration loader instance
     */
    public function __construct(
        Magento $app,
        LoggerInterface $systemLogger,
        ConfigurationLoader $configurationLoader
    ) {

        // set passed instances
        $this->app = $app;
        $this->systemLogger = $systemLogger;
        $this->configurationLoader = $configurationLoader;

        // call parent constructor
        parent::__construct();
    }

    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {

        // initialize the array for the options
        $options = [
            new InputArgument(
                InputArgumentKeys::OPERATION_NAME,
                InputArgument::OPTIONAL,
                'Operation that has to be executed',
                InputArgumentKeys::OPERATION_NAME_ARG_ADD_UPDATE
            ),
            new InputOption(
                InputOptionKeys::INSTALLATION_DIR,
                null,
                InputOption::VALUE_REQUIRED,
                'The magento installation directors to use',
                getcwd()
            ),
            new InputOption(
                InputOptionKeys::CONFIGURATION,
                null,
                InputOption::VALUE_REQUIRED,
                'Path to the configuration file'
            ),
            new InputOption(
                InputOptionKeys::SYSTEM_NAME,
                null,
                InputOption::VALUE_REQUIRED,
                'Specify the system name to use',
                gethostname()
            ),
            new InputOption(
                InputOptionKeys::PID_FILENAME,
                null,
                InputOption::VALUE_REQUIRED,
                'The explicit PID filename to use',
                sprintf('%s/%s', sys_get_temp_dir(), Configuration::PID_FILENAME)
            ),
            new InputOption(
                InputOptionKeys::MAGENTO_EDITION,
                null,
                InputOption::VALUE_REQUIRED,
                'The Magento edition to be used, either one of "CE" or "EE"'
            ),
            new InputOption(
                InputOptionKeys::MAGENTO_VERSION,
                null,
                InputOption::VALUE_REQUIRED,
                'The Magento version to be used, e. g. "2.1.2"'
            ),
            new InputOption(
                InputOptionKeys::CONFIGURATION,
                null,
                InputOption::VALUE_REQUIRED,
                'Specify the pathname to the configuration file to use'
            ),
            new InputOption(
                InputOptionKeys::ENTITY_TYPE_CODE,
                null,
                InputOption::VALUE_REQUIRED,
                'Specify the entity type code to use, either one of "catalog_product", "catalog_category" or "eav_attribute"'
            ),
            new InputOption(
                InputOptionKeys::SOURCE_DIR,
                null,
                InputOption::VALUE_REQUIRED,
                'The directory that has to be watched for new files'
            ),
            new InputOption(
                InputOptionKeys::TARGET_DIR,
                null,
                InputOption::VALUE_REQUIRED,
                'The target directory with the files that has been imported'
            ),
            new InputOption(
                InputOptionKeys::ARCHIVE_DIR,
                null,
                InputOption::VALUE_REQUIRED,
                'The directory the imported files will be archived in'
            ),
            new InputOption(
                InputOptionKeys::SOURCE_DATE_FORMAT,
                null,
                InputOption::VALUE_REQUIRED,
                'The date format used in the CSV file(s)'
            ),
            new InputOption(
                InputOptionKeys::USE_DB_ID,
                null,
                InputOption::VALUE_REQUIRED,
                'The explicit database ID used for the actual import process'
            ),
            new InputOption(
                InputOptionKeys::DB_PDO_DSN,
                null,
                InputOption::VALUE_REQUIRED,
                'The DSN used to connect to the Magento database where the data has to be imported, e. g. mysql:host=127.0.0.1;dbname=magento;charset=utf8'
            ),
            new InputOption(
                InputOptionKeys::DB_USERNAME,
                null,
                InputOption::VALUE_REQUIRED,
                'The username used to connect to the Magento database'
            ),
            new InputOption(
                InputOptionKeys::DB_PASSWORD,
                null,
                InputOption::VALUE_REQUIRED,
                'The password used to connect to the Magento database'
            ),
            new InputOption(
                InputOptionKeys::LOG_LEVEL,
                null,
                InputOption::VALUE_REQUIRED,
                'The log level to use'
            ),
            new InputOption(
                InputOptionKeys::DEBUG_MODE,
                null,
                InputOption::VALUE_REQUIRED,
                'Whether use the debug mode or not'
            )
        ];

        // initialize the command
        $this->setName('import:products')
             ->setDescription('Import Products')
             ->setDefinition($options);

        // call parent method
        parent::configure();
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input  An InputInterface instance
     * @param \Symfony\Component\Console\Output\OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     * @throws \Symfony\Component\Console\Exception\LogicException When this abstract method is not implemented
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {

        // initialize and load the importer configuration
        /** @var \TechDivision\Import\ConfigurationInterface $configuration */
        $configuration = $this->configurationLoader->load($input, EntityTypeCodes::CATALOG_PRODUCT);

        // add the configuration as well as input/outut instances to the DI container
        /*
        $container->set(SynteticServiceKeys::INPUT, $input);
        $container->set(SynteticServiceKeys::OUTPUT, $output);
        $container->set(SynteticServiceKeys::CONFIGURATION, $configuration);
        $container->set(SynteticServiceKeys::APPLICATION, $this->getApplication());
        */

        // add the PDO connection to the DI container
        // $container->set(SynteticServiceKeys::CONNECTION, $connection);


        // initialize the system logger
        $loggers = array();

        // add the system logger to the array with the configured loggers
        $loggers[LoggerKeys::SYSTEM] = $this->systemLogger;

        // append the configured loggers or override the default one
        foreach ($configuration->getLoggers() as $loggerConfiguration) {
            // load the factory class that creates the logger instance
            $loggerFactory = $loggerConfiguration->getFactory();
            // create the logger instance and add it to the available loggers
            $loggers[$loggerConfiguration->getName()] = $loggerFactory::factory($configuration, $loggerConfiguration);
        }

        // add the system loggers to the DI container
        // $container->set(SynteticServiceKeys::LOGGERS, $loggers);

        // start the import process
        // $container->get(SynteticServiceKeys::SIMPLE)->process();

        $this->app->setOutput($output);
        $this->app->setSystemLoggers($loggers);
        $this->app->setConfiguration($configuration);

        $this->app->process();

        $output->writeln('<info>Finished import!</info>');
    }
}
