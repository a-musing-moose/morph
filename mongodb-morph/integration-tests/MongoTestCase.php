<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/MongoConstraint/CollectionExists.php';
require_once dirname(__FILE__).'/MongoConstraint/CollectionDoesNotExist.php';
require_once dirname(__FILE__).'/MongoConstraint/DocumentExists.php';
require_once dirname(__FILE__).'/MongoConstraint/DocumentPropertyEquals.php';

/**
 * Mongo Test Case
 *
 * This class forms the basis of all MongoDB unit tests.
 * It provides new assertion types specific to MongoDB
 */
class MongoTestCase extends PHPUnit_Framework_TestCase
{

    const TEST_DB_NAME = 'TestDB';

    protected $db;

    public function setup()
    {
        $connection = new Mongo();
        $connection->dropDB(self::TEST_DB_NAME);
        $this->db = $connection->selectDB(self::TEST_DB_NAME);
    }

    public function tearDown()
    {
        if (isset($this->db)) {
            $this->db->drop();
        }
    }

    /**
     * Returns true only if the specified $collection exists
     *
     * @param string $collection
     * @return void
     */
    public function assertCollectionExists($collection, $message = '')
    {
        if (!is_string($collection)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        self::assertThat($collection, new MongoConstraint_CollectionExists($this->db), $message);
    }

/**
     * Returns true only if the specified $collection does not exist
     *
     * @param string $collection
     * @return void
     */
    public function assertCollectionDoesNotExist($collection, $message = '')
    {
        if (!is_string($collection)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }

        self::assertThat($collection, new MongoConstraint_CollectionDoesNotExist($this->db), $message);
    }

    /**
     * Checks to see if the specified document exists
     *
     * @param string $collection
     * @param string|MongoId $id
     * @return void
     */
    public function assertDocumentExists($collection, $id, $message = '')
    {
        if (!is_string($collection)) {
            throw PHPUnit_Util_InvalidArgumentHelper::factory(1, 'string');
        }
        self::assertThat($id, new MongoConstraint_DocumentExists($this->db, $collection), $message);
    }

    public function assertDocumentPropertyEquals($expected, $collection, $property, $id, $message = '')
    {
        $constraint = new MongoConstraint_DocumentPropertyEquals($this->db, $collection, $property, $expected);
        self::assertThat($id, $constraint, $message);
    }
}