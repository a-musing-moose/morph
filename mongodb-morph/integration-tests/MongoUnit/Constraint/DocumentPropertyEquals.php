<?php
/**
 * @author Jonathan Moss
 * @copyright 2010 Jonathan Moss <xirisr@gmail.com>
 * @package MongoUnit
 * @subpackage Constraint
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Util/Filter.php';
require_once 'PHPUnit/Util/Type.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__, 'PHPUNIT');

/**
 * A PHP Unit constraint that checks to ensure a specific document property
 * equals an expected value
 *
 * @package MongoUnit
 * @subpackage Constraint
 */
class MongoUnit_Constraint_DocumentPropertyEquals extends PHPUnit_Framework_Constraint
{
    /**
     * @var MongoDB
     */
    private $db;

    /**
     * @var string
     */
    private $collection;

    /**
     * @var string
     */
    private $property;

    private $expected;

    /**
     * @param integer|string $key
     */
    public function __construct($db, $collection, $property, $expected)
    {
        $this->db = $db;
        $this->collection = $collection;
        $this->property = $property;
        $this->expected = $expected;
    }

    /**
     * Evaluates the constraint for parameter $other. Returns TRUE if the
     * constraint is met, FALSE otherwise.
     *
     * @param mixed $other Value or object to evaluate.
     * @return bool
     */
    public function evaluate($other)
    {
        $query = array('_id' => $other);
        $data = $this->db->selectCollection($this->collection)->findOne($query);
        return ($data[$this->property] == $this->expected);
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "equals: " . print_r($this->expected, true);
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function customFailureDescription($other, $description, $not)
    {
        return sprintf(
          'Failed asserting that the property %s in document %s %s',
           $this->property,
           $other,
           $this->toString()
        );
    }
}
?>
