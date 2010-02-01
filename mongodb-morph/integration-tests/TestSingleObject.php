<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/MongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

class TestSingleObject extends MongoUnit_TestCase
{

    public function setup()
    {
        parent::setup();
        Morph_Storage::init($this->getDatabase());
    }

    public function tearDown()
    {
        parent::tearDown();
        Morph_Storage::deInit();
    }

    public function testStoresObject()
    {
        $child = new Child();
        $child->Name = 'Child';
        $child->save();
        $this->assertCollectionExists('Child');
        $this->assertDocumentExists('Child', $child->id());
    }

    public function testEditStoredObject()
    {
        $child = new Child();
        $child->Name = 'Child';
        $child->save();

        $expected = 'Child with edit';
        $id = $child->id();
        $childFetched = new Child;
        $childFetched->loadById($id);
        $childFetched->Name = $expected;
        $childFetched->save();

        $this->assertDocumentPropertyEquals($expected, 'Child', 'Name', $id);
    }

}