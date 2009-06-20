<?php
require_once 'PHPUnit/Framework.php';

require_once dirname(__FILE__).'/TestPropertySet.php';
require_once dirname(__FILE__).'/TestUtils.php';
require_once dirname(__FILE__).'/Property/TestProperties.php';
require_once dirname(__FILE__).'/Query/TestProperty.php';
require_once dirname(__FILE__).'/TestQuery.php';
require_once dirname(__FILE__).'/TestObject.php';

class MorphTests
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
        return $suite;
    }
}
