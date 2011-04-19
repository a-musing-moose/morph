<?php
/**
 * @author Jonathan Moss
 * @copyright 2010 Jonathan Moss <xirisr@gmail.com>
 * @package MongoUnit
 * @subpackage Constraint
 */
namespace mongoUnit\constraint;

require_once 'PHPUnit/Util/Type.php';
/**
 * A PHP Unit constraint that checks to ensure a specific document property
 * equals an expected value
 *
 * @package MongoUnit
 * @subpackage Constraint
 */
class DocumentPropertyEquals extends \PHPUnit_Framework_Constraint
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

    private $found;

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
        $this->found = $data[$this->property];
        return ($this->found == $this->expected);
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "expected:\n" . \print_r($this->expected, true);
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function customFailureDescription($other, $description, $not)
    {
        return \sprintf(
          "Failed asserting that the property %s in document \n%s %s\nactual: ",
           $this->property,
           $other,
           $this->toString(),
           \print_r($this->found)
        );
    }
}
