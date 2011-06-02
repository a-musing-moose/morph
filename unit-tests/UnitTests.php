<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 */

require_once dirname(__FILE__).'/TestPropertySet.php';
require_once dirname(__FILE__).'/TestUtils.php';
require_once dirname(__FILE__).'/Property/TestProperties.php';
require_once dirname(__FILE__).'/Query/TestProperty.php';
require_once dirname(__FILE__).'/TestQuery.php';
require_once dirname(__FILE__).'/TestObject.php';
require_once dirname(__FILE__).'/TestResult.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('TestPropertySet');
        $suite->addTestSuite('TestUtils');
        $suite->addTestSuite('TestProperties');
        $suite->addTestSuite('TestProperty');
        $suite->addTestSuite('TestQuery');
        $suite->addTestSuite('TestObject');
        $suite->addTestSuite('TestResult');
        return $suite;
    }
}
