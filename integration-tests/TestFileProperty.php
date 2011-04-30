<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 * @package Morph
 */

require_once dirname(__FILE__).'/../Morph.phar';
require_once dirname(__FILE__).'/mongoUnit/TestCase.php';
require_once dirname(__FILE__).'/test-objects/User.php';

/**
 * @package Morph
 */
class TestFileProperty extends \mongoUnit\TestCase
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
        unlink('test.png');
    }

    public function testStoresUserAndFile()
    {
        $user = new User();
        $user->Username = 'a_musing_moose';
        $user->Avatar = dirname(__FILE__).'/fixtures/flask.png';
        $user->save();
        $this->assertCollectionExists('User');
        $this->assertDocumentExists('User', $user->id());
        $user->Avatar->write("test.png");
        $this->assertFileExists('test.png');
        $originalFileHash = \md5_file(dirname(__FILE__).'/fixtures/flask.png');
        $retrievedFileHash = \md5_file('test.png');
        $this->assertEquals($originalFileHash, $retrievedFileHash);
    }

}