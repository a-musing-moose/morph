<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/mongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

/**
 * @package Morph
 */
class TestQuery extends \mongoUnit\TestCase
{

    public function setup()
    {
        parent::setup();
        \morph\Storage::init($this->getDatabase());
        //load data from file to query against
        $this->loadJsonFileDatasetIntoCollection('Child', dirname(__FILE__). '/fixtures/children.json');
    }

    public function tearDown()
    {
        parent::tearDown();
        \morph\Storage::deInit();
    }

    public function testCanFindByValue()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Name')->equals('a_musing_moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(1, $results->totalCount());
    }

    public function testCanFindByRegex()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Name')->regex('/.*moose.*/i');
        $results = $child->findByQuery($query);
        $this->assertEquals(4, $results->totalCount());
    }

    public function testCanFindByLike()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Name')->like('moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(4, $results->totalCount());
    }

    public function testCanFindByGreaterThan()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Age')->greaterThan(22);
        $results = $child->findByQuery($query);
        $this->assertEquals(2, $results->totalCount());
    }

    public function testCanFindByLessThan()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Age')->lessThan(22);
        $results = $child->findByQuery($query);
        $this->assertEquals(2, $results->totalCount());
    }

    public function testCanFindBetween()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Age')->greaterThan(22)->lessThan(40);
        $results = $child->findByQuery($query);
        $this->assertEquals(1, $results->totalCount());
    }

    public function testCanFindByNotValue()
    {
        $child = new Child();
        $query = new \morph\Query();
        $query->property('Name')->notEqualTo('a_musing_moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(3, $results->totalCount());
    }
}