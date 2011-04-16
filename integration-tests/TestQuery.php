<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/MongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

/**
 * @package Morph
 */
class TestQuery extends MongoUnit_TestCase
{

    public function setup()
    {
        parent::setup();
        Morph_Storage::init($this->getDatabase());
        //load data from file to query against
        $this->loadJsonFileDatasetIntoCollection('Child', dirname(__FILE__). '/fixtures/children.json');
    }

    public function tearDown()
    {
        parent::tearDown();
        Morph_Storage::deInit();
    }

    public function testCanFindByValue()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Name')->equals('a_musing_moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(1, $results->totalCount());
    }

    public function testCanFindByRegex()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Name')->regex('/.*moose.*/i');
        $results = $child->findByQuery($query);
        $this->assertEquals(4, $results->totalCount());
    }

    public function testCanFindByLike()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Name')->like('moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(4, $results->totalCount());
    }

    public function testCanFindByGreaterThan()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Age')->greaterThan(22);
        $results = $child->findByQuery($query);
        $this->assertEquals(2, $results->totalCount());
    }

    public function testCanFindByLessThan()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Age')->lessThan(22);
        $results = $child->findByQuery($query);
        $this->assertEquals(2, $results->totalCount());
    }

    public function testCanFindBetween()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Age')->greaterThan(22)->lessThan(40);
        $results = $child->findByQuery($query);
        $this->assertEquals(1, $results->totalCount());
    }

    public function testCanFindByNotValue()
    {
        $child = new Child();
        $query = new Morph_Query();
        $query->property('Name')->notEqualTo('a_musing_moose');
        $results = $child->findByQuery($query);
        $this->assertEquals(3, $results->totalCount());
    }
}