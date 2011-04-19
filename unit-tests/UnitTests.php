<?php

require_once dirname(__FILE__).'/TestPropertySet.php';
require_once dirname(__FILE__).'/TestUtils.php';
require_once dirname(__FILE__).'/property/TestProperties.php';
require_once dirname(__FILE__).'/query/TestProperty.php';
require_once dirname(__FILE__).'/TestQuery.php';
require_once dirname(__FILE__).'/TestObject.php';

class AllTests
{
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('\morph\TestPropertySet');
        $suite->addTestSuite('\morph\TestUtils');
        $suite->addTestSuite('\morph\property\TestProperties');
        $suite->addTestSuite('\morph\query\TestProperty');
        $suite->addTestSuite('\morph\TestQuery');
        $suite->addTestSuite('\morph\TestObject');
        return $suite;
    }
}
