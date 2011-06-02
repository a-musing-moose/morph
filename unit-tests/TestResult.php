<?php
/**
 * @package test
 * @category PhpUnit
 * @author Kanstantsin Kamkou (2ka.by)
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__) . '/../src/Morph/Storage.php';
require_once dirname(__FILE__) . '/../src/Morph/Iterator.php';

/**
 * @see PHPUnit_Framework_TestCase
 */
class TestResult extends PHPUnit_Framework_TestCase
{
    protected $_table;
    protected $_cache;

    public function setUp()
    {
        // connection check
        try {
            $db = new Mongo();
        } catch (MongoException $e) {
            $this->markTestIncomplete();
        }

        Morph_Storage::init($db->selectDB('test'));

        $this->_table = new Morph_ForTesting();

        for ($i = 1; $i <= 20; $i++) {
            $this->_cache[] = $obj = clone $this->_table;
            $obj->TestField = ($i > 10) ? "HelloWorld{$i}" : 'HelloWorld';
            $obj->save();
        }
    }

    public function tearDown()
    {
        foreach ($this->_cache as $obj) {
            $obj->delete();
        }
    }

    public function testFoundMulti()
    {
        $query = new Morph_Query();
        $query->property('TestField')->equals('HelloWorld');

        $result = $this->_table->findByQuery($query);

        $this->assertEquals(10, count($result));
    }

    public function testFoundOne()
    {
        $query = new Morph_Query();
        $query->property('TestField')
            ->equals('HelloWorld');

        $result = $this->_table->findOneByQuery($query);

        $this->assertEquals('HelloWorld', $result->TestField);
    }

    public function testNotFoundMulti()
    {
        $query = new Morph_Query();
        $query->property('TestField')
            ->equals('example');

        $result = $this->_table->findByQuery($query);

        $this->assertEquals(0, count($result));
    }

    public function testNotFoundOne()
    {
        $query = new Morph_Query();
        $query->property('TestField')
            ->equals('example');

        $result = $this->_table->findOneByQuery($query);

        $this->assertEquals(0, count($result));
    }
}
