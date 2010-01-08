<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once 'MongoTestCase.php';
require_once 'test-objects/HasOneParent.php';
require_once 'test-objects/Child.php';

class TestSingleObject extends MongoTestCase
{

    /**
     * @var Morph_Storage
     */
    private $storage;

    public function setUp()
    {
        parent::setUp();
        $mongo = new Mongo();
        $this->storage = new Morph_Storage($mongo->selectDB(self::TEST_DB_NAME));
    }

    public function testStoresParentAndChild()
    {
        $child = new Child();
        $child->Name = 'Child';
        $this->storage->save($child);
        sleep(1); //MongoDB can take a sec to allocate the collection files
        $this->assertCollectionExists('Child');
        $this->assertDocumentExists('Child', $child->id());
    }

}