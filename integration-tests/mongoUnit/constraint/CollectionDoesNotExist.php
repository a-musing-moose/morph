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
 * A PHP Unit constraint that checks a collection does not exist
 *
 * @package MongoUnit
 * @subpackage Constraint
 */
class CollectionDoesNotExist extends \PHPUnit_Framework_Constraint
{
    /**
     * @var MongoDB
     */
    private $db;

    /**
     * @param integer|string $key
     */
    public function __construct($db)
    {
        $this->db = $db;
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
        $exists = false;
        $collections = $this->db->listCollections();
        $collectionNames = array();
        foreach ($collections as $collection) {
            $collectionNames[] = $collection->getName();
        }
        return !\in_array($other, $collectionNames);
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return 'collection does not exist';
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function customFailureDescription($other, $description, $not)
    {
        return \sprintf(
          'Failed asserting that the %s %s',
           $other,
           $this->toString()
        );
    }
}
