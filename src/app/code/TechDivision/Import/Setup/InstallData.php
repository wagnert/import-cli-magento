<?php

/**
 * TechDivision\Import\Setup\InstallData
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

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * Install data setup functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 *
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * Installs data for a module.
     *
     * @param \Magento\Framework\Setup\ModuleDataSetupInterface $setup   The setup instance
     * @param \Magento\Framework\Setup\ModuleContextInterface   $context The module context instance
     *
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {

        // query version, only install data if we're lower than 2.1.6
        if (version_compare($context->getVersion(), '2.1.5', '>')) {
            return;
        }

        // start setup
        $setup->startSetup();

        // initialize the data for product link attributes
        $data = [
            [
                'link_type_id' => \Magento\Catalog\Model\Product\Link::LINK_TYPE_RELATED,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int',
            ],
            [
                'link_type_id' => \Magento\Catalog\Model\Product\Link::LINK_TYPE_UPSELL,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int'
            ],
            [
                'link_type_id' => \Magento\Catalog\Model\Product\Link::LINK_TYPE_CROSSSELL,
                'product_link_attribute_code' => 'position',
                'data_type' => 'int'
            ],
        ];

        // install the product link attributes
        $setup->getConnection()
              ->insertMultiple($setup->getTable('catalog_product_link_attribute'), $data);

        // finish setup
        $setup->endSetup();
    }
}
