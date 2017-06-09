<?php

/**
 * TechDivision\Import\App\Magento
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
 * @link      https://github.com/techdivision/import-app-simple
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\App;

use Magento\Framework\ObjectManagerInterface;
use TechDivision\Import\App\Simple;
use TechDivision\Import\Services\ImportProcessorInterface;
use TechDivision\Import\Services\RegistryProcessorInterface;

/**
 * The M2IF - Simple Application implementation.
 *
 * This is a example application implementation that should give developers an impression
 * on how the M2IF could be used to implement their own Magento 2 importer.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-app-simple
 * @link      http://www.techdivision.com
 */
class Magento extends Simple
{

    /**
     * The Magento object manager.
     *
     * @var \Magento\Framework\ObjectManagerInterface
     */
    protected $container;

    /**
     * The constructor to initialize the instance.
     *
     * @param \Magento\Framework\ObjectManagerInterface                $container         The DI container instance
     * @param \TechDivision\Import\Services\RegistryProcessorInterface $registryProcessor The registry processor instance
     * @param \TechDivision\Import\Services\ImportProcessorInterface   $importProcessor   The import processor instance
     */
    public function __construct(
        ObjectManagerInterface $container,
        RegistryProcessorInterface $registryProcessor,
        ImportProcessorInterface $importProcessor
    ) {

        // register the shutdown function
        register_shutdown_function(array($this, 'shutdown'));

        // initialize the instance with the passed values
        $this->container = $container;
        $this->importProcessor = $importProcessor;
        $this->registryProcessor = $registryProcessor;
    }
}
