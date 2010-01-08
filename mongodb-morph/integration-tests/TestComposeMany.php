<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */

require_once 'MongoTestCase.php';
require_once 'test-objects/ComposeManyParent.php';
require_once 'test-objects/Child.php';

class TestComposeMany extends MongoTestCase
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
        $parent = new ComposeManyParent();
        $parent->Name = 'Compose Many Parent';

        $child1 = new Child();
        $child1->Name = 'Child1';

        $child2 = new Child();
        $child2->Name = 'Child2';

        $parent->Children[] = $child1;
        $parent->Children[] = $child2;

        $this->storage->save($parent);
        sleep(1); //MongoDB can take a sec to allocate the collection files
        $this->assertCollectionExists('ComposeManyParent');
        $this->assertCollectionDoesNotExist('Child');

        $this->assertDocumentExists('ComposeManyParent', $parent->id());

        $expected = array (
            array('_ns'=>'Child', 'Name'=>'Child1'),
            array('_ns'=>'Child', 'Name'=>'Child2')
        );

        $this->assertDocumentPropertyEquals($expected, 'ComposeManyParent', 'Children', $parent->id());
    }

}