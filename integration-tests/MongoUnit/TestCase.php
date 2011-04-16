<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package MongoUnit
 */

require_once 'PHPUnit/Framework/TestCase.php';
require_once dirname(__FILE__).'/JsonLoader.php';
require_once dirname(__FILE__).'/Constraint/CollectionExists.php';
require_once dirname(__FILE__).'/Constraint/CollectionDoesNotExist.php';
require_once dirname(__FILE__).'/Constraint/DocumentExists.php';
require_once dirname(__FILE__).'/Constraint/DocumentPropertyEquals.php';

/**
 * MongoUnit Test Case
 *
 * This class forms the basis of all MongoDB unit tests.
 * It provides new assertion types specific to MongoDB
 * @package MongoUnit
 */
class MongoUnit_TestCase extends PHPUnit_Framework_TestCase
{

    const TEST_DB_NAME = 'MongoTestDB';
    const TEST_DSN = 'mongodb://localhost';

    /**
     * @var Mongo
     */
    private $connection;

    protected $dsn = self::TEST_DSN;

    /**
     * @var string
     */
    protected $databaseName = self::TEST_DB_NAME;

    /**
     * @var MongoDB
     */
    protected $database;

    /**
     * @return void
     */
    public function setup()
    {

        if (!extension_loaded('mongo')) {
            $this->markSkipped('Mongo extension not installed');
        }
    }

    /**
     * @return void
     */
    public function tearDown()
    {
        if (isset($this->database)) {
            $this->database->drop();
        }
    }

    /**
     * Sets the connection string to use when creating a Mongo connection
     *
     * @param string $dsn defaults is mongodb://localhost
     * @return MongoTestCase
     */
    protected function setDsn($dsn = self::TEST_DSN)
    {
        $this->dsn = $dsn;
        $this->connection = null;
        $this->database = null;
        return $this;
    }

    /**
     * Returns the current DSN
     *
     * @return string
     */
    protected function getDsn()
    {
        return $this->dsn;
    }

    /**
     * Returns a Mongo connection
     *
     * @return Mongo
     */
    protected function getConnection()
    {
        if (!isset($this->connection)) {
            $this->connection = new Mongo($this->getDsn());
        }
        return $this->connection;
    }

    /**
     * Sets the name of the database to use for testing
     *
     * @param string $name
     * @return MongoTestCase
     */
    protected function setDatabaseName($name)
    {
        $this->databaseName = (string)$name;
        $this->database = null;
        return $this;
    }

    /**
     * Returns the name of the current database
     *
     * @return string
     */
    protected function getDatabaseName()
    {
        return $this->databaseName;
    }

    /**
     * Returns the current database object
     *
     * @return MongoDB
     */
    protected function getDatabase()
    {
        if (empty($this->database)) {
            $this->getConnection()->dropDb($this->getDatabaseName());
            $this->database = $this->getConnection()->selectDb($this->getDatabaseName());
        }
        return $this->database;
    }

    ////////////////
    // ASSERTIONS //
    ////////////////

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

        self::assertThat($collection, new MongoUnit_Constraint_CollectionExists($this->getDatabase()), $message);
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

        self::assertThat($collection, new MongoUnit_Constraint_CollectionDoesNotExist($this->getDatabase()), $message);
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
        self::assertThat($id, new MongoUnit_Constraint_DocumentExists($this->getDatabase(), $collection), $message);
    }

    public function assertDocumentPropertyEquals($expected, $collection, $property, $id, $message = '')
    {
        $constraint = new MongoUnit_Constraint_DocumentPropertyEquals($this->getDatabase(), $collection, $property, $expected);
        self::assertThat($id, $constraint, $message);
    }

    //////////////////////////////////
    // Collection Content Functions //
    //////////////////////////////////

    /**
     * Clear the specified collection and populates it with the content of $filePath
     *
     * The specified file should contain a JSON encoded array of documents
     *
     * @param string $collectionName
     * @param string $filePath
     * @return boolean
     */
    public function loadJsonFileDatasetIntoCollection($collectionName, $filePath)
    {
        $collection = $this->getDatabase()->selectCollection($collectionName);
        $loader = new MongoUnit_JsonLoader($collection);
        return $loader->loadFromJsonFile($filePath);
    }

    /**
     * Clear the specified collection and populates it with the $json
     *
     * $json should be a JSON encoded array of documents
     *
     * @param string $collectionName
     * @param string $json
     * @return boolean
     */
    public function loadJsonStringDatasetIntoCollection($collectionName, $json)
    {
        $collection = $this->getDatabase()->selectCollection($collectionName);
        $loader = new MongoUnit_JsonLoader($collection);
        return $loader->loadJsonFromString($json);
    }

}