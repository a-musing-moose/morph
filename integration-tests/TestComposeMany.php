<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/mongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/ComposeManyParent.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

/**
 * @package Morph
 */
class TestComposeMany extends \mongoUnit\TestCase
{

    public function setup()
    {
        parent::setUp();
        \morph\Storage::init($this->getDatabase());
    }

    public function tearDown()
    {
        parent::tearDown();
        \morph\Storage::deInit();
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

        $parent->save();
        $this->assertCollectionExists('ComposeManyParent');
        $this->assertCollectionDoesNotExist('Child');

        $this->assertDocumentExists('ComposeManyParent', $parent->id());

        $expected = array (
            array('_ns'=>'Child', 'Name'=>'Child1', 'Age' => null),
            array('_ns'=>'Child', 'Name'=>'Child2', 'Age' => null)
        );

        $this->assertDocumentPropertyEquals($expected, 'ComposeManyParent', 'Children', $parent->id());
    }

}