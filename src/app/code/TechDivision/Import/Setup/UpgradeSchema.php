<?php

/**
 * TechDivision\Import\Setup\UpgradeSchema
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

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

/**
 * Upgrade schema setup functionality.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 *
 * @codeCoverageIgnore
 */
class UpgradeSchema implements UpgradeSchemaInterface
{

    /**
     * Installs DB schema for the module.
     *
     * @param \Magento\Framework\Setup\SchemaSetupInterface   $setup   The setup instance
     * @param \Magento\Framework\Setup\ModuleContextInterface $context The module context instance
     *
     * @return void
     */
    public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        // start setup
        $setup->startSetup();

        // finish setup
        $setup->endSetup();
    }
}
