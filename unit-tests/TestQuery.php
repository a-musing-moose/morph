<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */
namespace morph;

require_once dirname(__FILE__).'/../src/morph/Enum.php';
require_once dirname(__FILE__).'/../src/morph/IQuery.php';
require_once dirname(__FILE__).'/../src/morph/query/Property.php';
require_once dirname(__FILE__).'/../src/morph/Query.php';

/**
 * @package Morph
 */
class TestQuery extends \PHPUnit_Framework_TestCase
{

    public function testLimit()
    {
        $query = new Query();
        $query->limit(10);
        $this->assertEquals(10, $query->getLimit());
    }

    public function testSkip()
    {
        $query = new Query();
        $query->skip(10);
        $this->assertEquals(10, $query->getSkip());
    }

    public function testProperty()
    {
        $expected = array('bob' => 'hoskins');
        $query = new Query();
        $query->property('bob')->equals('hoskins');
        $this->assertEquals($expected, $query->getRawQuery());

    }

    public function testFluent()
    {
        $expected = array('bob' => 'hoskins');
        $query = Query::instance()
        ->property('bob')
        ->equals('hoskins');
        $this->assertEquals($expected, $query->getRawQuery());
    }

    public function testMultipleProperties()
    {
        $expected = array('bob' => 'hoskins', 'abc' => 12);
        $query = Query::instance()
        ->property('bob')
        ->equals('hoskins')
        ->property('abc')
        ->equals(12);
        $this->assertEquals($expected, $query->getRawQuery());
    }

    public function testFullFluency()
    {
        $expected = array('bob' => 'hoskins', 'abc' => 12);
        $query = Query::instance()
        ->limit(10)->skip(12)
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
}