<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2010 Jonathan Moss
 */
require_once 'MongoTestCase.php';
require_once 'test-objects/User.php';

class TestFileProperty extends MongoTestCase
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

    public function tearDown()
    {
        parent::tearDown();
        unlink('test.png');
    }

    public function testStoresUserAndFile()
    {
        $user = new User();
        $user->Username = 'a_musing_moose';
        $user->Avatar = realpath('resources/flask.png');
        $this->storage->save($user);
        sleep(1); //MongoDB can take a sec to allocate the collection files
        $this->assertCollectionExists('User');
        $this->assertDocumentExists('User', $user->id());
        $user->Avatar->write("test.png");
        $this->assertFileExists('test.png');
        $originalFileHash = md5_file('resources/flask.png');
        $retrievedFileHash = md5_file('test.png');
        $this->assertEquals($originalFileHash, $retrievedFileHash);
    }

}