<?php

/**
 * TechDivision\Import\Setup\InstallSchema
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

namespace TechDivision\Import\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Install schema setup functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 *
 * @codeCoverageIgnore
 */
class InstallSchema implements InstallSchemaInterface
{

    /**
     * Installs DB schema for the module.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup   The setup instance
     * @param \Magento\Framework\Setup\ModuleContextInterface $context The module context instance
     *
     * @return void
     */
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        // start setup
        $setup->startSetup();

        // load the connection
        $connection = $setup->getConnection();

        // add indices necessary to optimize the importer performance
        $connection->addIndex('url_rewrite', $setup->getIdxName('url_rewrite', ['entity_id']), ['entity_id']);
        $connection->addIndex('url_rewrite', $setup->getIdxName('url_rewrite', ['entity_id', 'entity_type']), ['entity_id', 'entity_type']);
        $connection->addIndex('catalog_product_entity_varchar', $setup->getIdxName('catalog_product_entity_varchar', ['value']), ['value']);
        $connection->addIndex('eav_attribute_option_value', $setup->getIdxName('eav_attribute_option_value', ['value']), ['value']);
        $connection->addIndex('catalog_product_entity_media_gallery', $setup->getIdxName('catalog_product_entity_media_gallery', ['value']), ['value']);

        // finish setup
        $setup->endSetup();
    }
}
