<?php

/**
 * TechDivision\Import\Model\MagentoConfigurationLoader
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

namespace TechDivision\Import\Model;

use Symfony\Component\Console\Input\InputInterface;
use Magento\Framework\App\Filesystem\DirectoryList;

/**
 * The configuration factory implementation.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 */
class MagentoConfigurationLoader extends Configuration
{

    /**
     * The instance with the directory list.
     *
     * @var \Magento\Framework\Filesystem\DirectoryList
     */
    protected $directoryList;

    /**
     * Initializes the configuration loader.
     *
     * @param \Magento\Framework\Filesystem\DirectoryList                     $directoryList                The directory list
     * @param \Symfony\Component\Console\Input\InputInterface                 $input                        The input instance
     * @param \Symfony\Component\DependencyInjection\ContainerInterface       $container                    The container instance
     * @param \TechDivision\Import\Cli\LibraryLoader                          $libraryLoader                The configuration loader instance
     * @param \TechDivision\Import\ConfigurationFactoryInterface              $configurationFactory         The configuration factory instance
     * @param \TechDivision\Import\Utils\CommandNames                         $commandNames                 The available command names
     * @param \TechDivision\Import\Utils\Mappings\CommandNameToEntityTypeCode $commandNameToEntityTypeCodes The mapping of the command names to the entity type codes
     */
    public function __construct(
        DirectoryList $directoryList,
        InputInterface $input,
        ContainerInterface $container,
        LibraryLoader $libraryLoader,
        ConfigurationFactoryInterface $configurationFactory,
        CommandNames $commandNames,
        CommandNameToEntityTypeCode $commandNameToEntityTypeCodes
    ) {

        // set the passed instances
        $this->directoryList = $directoryList;
        $this->input = $input;
        $this->container = $container;
        $this->libraryLoader = $libraryLoader;
        $this->configurationFactory = $configurationFactory;
        $this->commandNames = $commandNames;
        $this->commandNameToEntityTypeCode = $commandNameToEntityTypeCodes;
    }

    /**
     * Return's the vendor directory used to load the default configuration files from.
     *
     * @return string The vendor directory
     */
    public function getVendorDir()
    {
        return sprintf('%s/vendor', $this->directoryList->getPath('base'));
    }
}
