<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/MongoTestCase.php';
require_once dirname(__FILE__).'/test-objects/HasOneParent.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

class TestSingleObject extends MongoTestCase
{

    public function setUp()
    {
        parent::setUp();
        $mongo = new Mongo();
        Morph_Storage::init($mongo->selectDB(self::TEST_DB_NAME));
    }

    public function tearDown()
    {
        parent::tearDown();
        Morph_Storage::deInit();
    }

    public function testStoresParentAndChild()
    {
        $child = new Child();
        $child->Name = 'Child';
        $child->save();
        sleep(1); //MongoDB can take a sec to allocate the collection files
        $this->assertCollectionExists('Child');
        $this->assertDocumentExists('Child', $child->id());
    }

}