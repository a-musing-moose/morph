<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/mongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/Aliased.php';

/**
 * @package Morph
 */
class TestAliased extends \mongoUnit\TestCase
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

    public function testDataUsesStorageName()
    {
    	$expected = array(
    		'_ns' => 'Aliased',
    		'n' => 'Chris'
    	);
        $aliased = new Aliased();
        $aliased->Name = 'Chris';
        $this->assertSame($expected, $aliased->__getData());
    }
    
    public function testAliasedPropertyIsRestoredCorrectly()
    {
    	$data = array(
    		'_ns' => 'Aliased',
    		'n' => 'Chris'
    	);
        $aliased = new Aliased();
        $aliased->__setData($data);
        $this->assertSame('Chris', $aliased->Name);
    }
}