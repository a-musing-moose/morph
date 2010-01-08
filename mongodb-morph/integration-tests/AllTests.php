<?php
/**
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2010
 */
require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/TestComposeMany.php';
require_once dirname(__FILE__).'/TestComposeOne.php';
require_once dirname(__FILE__).'/TestFileProperty.php';
require_once dirname(__FILE__).'/TestHasMany.php';
require_once dirname(__FILE__).'/TestHasOne.php';
require_once dirname(__FILE__).'/TestSingleObject.php';

/**
 * Bootstraps all integration tests
 */
class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Integration Tests');
        $suite->addTestSuite('TestComposeMany');
        $suite->addTestSuite('TestComposeOne');
        $suite->addTestSuite('TestFileProperty');
        $suite->addTestSuite('TestHasMany');
        $suite->addTestSuite('TestHasOne');
        $suite->addTestSuite('TestSingleObject');

        return $suite;
    }
}
