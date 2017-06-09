<?php

/**
 * TechDivision\Import\App\ConnectionWrapper
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

use Magento\Framework\App\ResourceConnection;

/**
 * A connection wrapper that wraps a PDO connection.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-app-simple
 * @link      http://www.techdivision.com
 */
class ConnectionWrapper
{

    /**
     * The wrapped PDO connection.
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * Initialize the wrapper with the Magento resource connection.
     *
     * @param \Magento\Framework\App\ResourceConnection $connection The Magento resource connection
     */
    public function __construct(ResourceConnection $connection)
    {

        // initialize the PDO connection
        $this->connection = $connection->getConnection(ResourceConnection::DEFAULT_CONNECTION)
                                       ->getConnection();
    }

    /**
     * Delegate all method calls to the PDO connection.
     *
     * @param string $name      The method name
     * @param array  $arguments The method arguments
     *
     * @return mixed The result of the method call
     */
    public function __call($name, array $arguments = array())
    {
        return call_user_func_array(array($this->connection, $name), $arguments);
    }
}
