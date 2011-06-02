<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__).'/../src/Morph/Enum.php';
require_once dirname(__FILE__).'/../src/Morph/IQuery.php';
require_once dirname(__FILE__).'/../src/Morph/Query/Property.php';
require_once dirname(__FILE__).'/../src/Morph/Query.php';
require_once dirname(__FILE__).'/../src/Morph/Storage.php';
require_once dirname(__FILE__).'/../src/Morph/Object.php';
require_once dirname(__FILE__).'/ForTesting.php';

/**
 * @package Morph
 */
class TestQuery extends PHPUnit_Framework_TestCase
{

    protected $_testDb;

    public function setUp()
    {
        $this->_testDb = new Morph_ForTesting();
    }

    public function testLimit()
    {
        $query = new Morph_Query();
        $query->limit(10);
        $this->assertEquals(10, $query->getLimit());
    }

    public function testSkip()
    {
        $query = new Morph_Query();
        $query->skip(10);
        $this->assertEquals(10, $query->getSkip());
    }

    public function testProperty()
    {
        $expected = array('bob' => 'hoskins');
        $query = new Morph_Query();
        $query->property('bob')->equals('hoskins');
        $this->assertEquals($expected, $query->getRawQuery());

    }

    public function testFluent()
    {
        $expected = array('bob' => 'hoskins');
        $query = new Morph_Query();
        $query->property('bob')
        ->equals('hoskins');
        $this->assertEquals($expected, $query->getRawQuery());
    }

    public function testMultipleProperties()
    {
        $expected = array('bob' => 'hoskins', 'abc' => 12);
        $query = new Morph_Query();
        $query->property('bob')
        ->equals('hoskins')
        ->property('abc')
        ->equals(12);
        $this->assertEquals($expected, $query->getRawQuery());
    }

    public function testFullFluency()
    {
        $expected = array('bob' => 'hoskins', 'abc' => 12);
        $query = new Morph_Query();
        $query->limit(10)->skip(12)
        ->property('bob')
        ->equals('hoskins')
        ->sort(1)
        ->property('abc')
        ->equals(12);
        $this->assertEquals($expected, $query->getRawQuery());
        $this->assertEquals(10, $query->getLimit());
        $this->assertEquals(12, $query->getSkip());
        $this->assertEquals(array('bob'=>1), $query->getRawSort());
    }

    public function testNotFoundMulti()
    {
        $query = new Morph_Query();
        $query->property('TestField')->equals('example');

        $result = $this->_testDb->findByQuery($query);

        $this->markTestIncomplete();
        #$this->assertArrayNotHasKey('added', $query->getRawQuery());
    }

    public function testSort()
    {
        $query = new Morph_Query();
        $query->property('id')
            ->equals(12)
            ->property('added')
            ->sort(Morph_Enum::DIRECTION_DESC);

        $this->assertArrayNotHasKey('added', $query->getRawQuery());
    }
}
