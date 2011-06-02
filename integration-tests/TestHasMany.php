<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/mongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/HasManyParent.php';
require_once dirname(__FILE__).'/test-objects/Child.php';

/**
 * @package Morph
 */
class TestHasMany extends \mongoUnit\TestCase
{

    public function setup()
    {
        parent::setup();
        \morph\Storage::init($this->getDatabase());
    }

    public function tearDown()
    {
        parent::tearDown();
        \morph\Storage::deInit();
    }

    public function testStoresParentAndChildren()
    {
        $parent = new HasManyParent();
        $parent->Name = 'Has Many Parent';

        $child1 = new Child();
        $child1->Name = 'Child1';

        $parent->Children[] = $child1;

        $child2 = new Child();
        $child2->Name = 'Child2';

        $parent->Children[] = $child2;

        $parent->save();
        $this->assertCollectionExists('HasManyParent');
        $this->assertCollectionExists('Child');

        $this->assertDocumentExists('HasManyParent', $parent->id());
        $this->assertDocumentExists('Child', $child1->id());
        $this->assertDocumentExists('Child', $child2->id());

    }
    
    public function testStoresAddChild()
    {
        $parent = new HasManyParent();
        $parent->Name = 'Has Many Parent';

        $child1 = new Child();
        $child1->Name = 'Child1';

        $parent->Children[] = $child1;

        $parent->save();
        $this->assertCollectionExists('HasManyParent');
        $this->assertCollectionExists('Child');

        $this->assertDocumentExists('HasManyParent', $parent->id());
        $this->assertDocumentExists('Child', $child1->id());
        
        $child2 = new Child();
        $child2->Name = 'Child2';
        
        $parent->Children[] = $child2;
        $parent->save();
        $this->assertDocumentExists('Child', $child2->id());

    }

}