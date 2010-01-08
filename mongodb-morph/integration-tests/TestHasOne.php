<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once 'MongoTestCase.php';
require_once 'test-objects/HasOneParent.php';
require_once 'test-objects/Child.php';

class TestHasOne extends MongoTestCase
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
        $parent = new HasOneParent();
        $parent->Name = 'Has One Parent';

        $child = new Child();
        $child->Name = 'Child';

        $parent->Child = $child;

        $this->storage->save($parent);
        sleep(1); //MongoDB can take a sec to allocate the collection files
        $this->assertCollectionExists('HasOneParent');
        $this->assertCollectionExists('Child');

        $this->assertDocumentExists('HasOneParent', $parent->id());
        $this->assertDocumentExists('Child', $child->id());

    }

}