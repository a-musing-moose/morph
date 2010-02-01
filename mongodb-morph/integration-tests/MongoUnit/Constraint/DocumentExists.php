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
 * A PHP Unit constraint that checks to ensure a specific document exists
 *
 * @package MongoUnit
 * @subpackage Constraint
 */
class MongoUnit_Constraint_DocumentExists extends PHPUnit_Framework_Constraint
{
    /**
     * @var MongoDB
     */
    private $db;

    private $collection;

    /**
     * @param integer|string $key
     */
    public function __construct($db, $collection)
    {
        $this->db = $db;
        $this->collection = $collection;
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
        $documentExists = false;
        $query = array('_id' => $other);
        $data = $this->db->selectCollection($this->collection)->findOne($query);
        if (!empty($data)) {
            $documentExists = true;
        }
        return $documentExists;
    }

    /**
     * Returns a string representation of the constraint.
     *
     * @return string
     */
    public function toString()
    {
        return "exists in the collection {$this->collection}";
    }

    /**
     * @param mixed   $other
     * @param string  $description
     * @param boolean $not
     */
    protected function customFailureDescription($other, $description, $not)
    {
        return sprintf(
          'Failed asserting that the document %s %s',
           $other,
           $this->toString()
        );
    }
}
?>
