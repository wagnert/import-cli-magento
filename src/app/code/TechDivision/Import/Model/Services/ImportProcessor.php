<?php

/**
 * TechDivision\Import\Model\Services\ImportProcessor
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

namespace TechDivision\Import\Model\Services;

use Magento\Framework\App\ResourceConnection;
use TechDivision\Import\Assembler\CategoryAssembler;
use TechDivision\Import\Repositories\CategoryRepository;
use TechDivision\Import\Repositories\CategoryVarcharRepository;
use TechDivision\Import\Repositories\EavAttributeRepository;
use TechDivision\Import\Repositories\EavAttributeSetRepository;
use TechDivision\Import\Repositories\EavAttributeGroupRepository;
use TechDivision\Import\Repositories\EavEntityTypeRepository;
use TechDivision\Import\Repositories\StoreRepository;
use TechDivision\Import\Repositories\StoreWebsiteRepository;
use TechDivision\Import\Repositories\TaxClassRepository;
use TechDivision\Import\Repositories\LinkTypeRepository;
use TechDivision\Import\Repositories\LinkAttributeRepository;
use TechDivision\Import\Repositories\CoreConfigDataRepository;

use TechDivision\Import\Services\ImportProcessor as GenericImportProcessor;

/**
 * Processor implementation to load global data.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-cli-magento
 * @link      http://www.techdivision.com
 */
class ImportProcessor extends GenericImportProcessor
{

    /**
     * Initialize the processor with the necessary assembler and repository instances.
     *
     * @param \Magento\Framework\App\ResourceConnection                     $connection                  The connection to use
     * @param \TechDivision\Import\Assembler\CategoryAssembler              $categoryAssembler           The category assembler instance
     * @param \TechDivision\Import\Repositories\CategoryRepository          $categoryRepository          The repository to access categories
     * @param \TechDivision\Import\Repositories\CategoryVarcharRepository   $categoryVarcharRepository   The repository to access category varchar values
     * @param \TechDivision\Import\Repositories\EavAttributeRepository      $eavAttributeRepository      The repository to access EAV attributes
     * @param \TechDivision\Import\Repositories\EavAttributeSetRepository   $eavAttributeSetRepository   The repository to access EAV attribute sets
     * @param \TechDivision\Import\Repositories\EavAttributeGroupRepository $eavAttributeGroupRepository The repository to access EAV attribute groups
     * @param \TechDivision\Import\Repositories\EavEntityTypeRepository     $eavEntityTypeRepository     The repository to access EAV entity types
     * @param \TechDivision\Import\Repositories\StoreRepository             $storeRepository             The repository to access stores
     * @param \TechDivision\Import\Repositories\StoreWebsiteRepository      $storeWebsiteRepository      The repository to access store websites
     * @param \TechDivision\Import\Repositories\TaxClassRepository          $taxClassRepository          The repository to access tax classes
     * @param \TechDivision\Import\Repositories\LinkTypeRepository          $linkTypeRepository          The repository to access link types
     * @param \TechDivision\Import\Repositories\LinkAttributeRepository     $linkAttributeRepository     The repository to access link attributes
     * @param \TechDivision\Import\Repositories\CoreConfigDataRepository    $coreConfigDataRepository    The repository to access the configuration
     */
    public function __construct(
        ResourceConnection $connection,
        CategoryAssembler $categoryAssembler,
        CategoryRepository $categoryRepository,
        CategoryVarcharRepository $categoryVarcharRepository,
        EavAttributeRepository $eavAttributeRepository,
        EavAttributeSetRepository $eavAttributeSetRepository,
        EavAttributeGroupRepository $eavAttributeGroupRepository,
        EavEntityTypeRepository $eavEntityTypeRepository,
        StoreRepository $storeRepository,
        StoreWebsiteRepository $storeWebsiteRepository,
        TaxClassRepository $taxClassRepository,
        LinkTypeRepository $linkTypeRepository,
        LinkAttributeRepository $linkAttributeRepository,
        CoreConfigDataRepository $coreConfigDataRepository
    ) {

        // initialize the PDO connection
        $connection = $this->connection->getConnection(ResourceConnection::DEFAULT_CONNECTION)
                                       ->getConnection();

        // pass the arguments to the parent class
        parent::__construct(
            $connection,
            $categoryAssembler,
            $categoryRepository,
            $categoryVarcharRepository,
            $eavAttributeRepository,
            $eavAttributeSetRepository,
            $eavAttributeGroupRepository,
            $eavEntityTypeRepository,
            $storeRepository,
            $storeWebsiteRepository,
            $taxClassRepository,
            $linkTypeRepository,
            $linkAttributeRepository,
            $coreConfigDataRepository
        );
    }
}
