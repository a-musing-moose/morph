<?php
/**
 * @package Morph
 * @subpackage Query
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 * @version SVN: $Id$
 */

/**
 * Represents a series of constraints to be applied to a property when querying
 *
 *
 * @package Morph
 * @subpackage Query
 */
class Morph_Query_Property
{

    /**
     * Internal flag to mark the mode as singular constraint
     * @var string
     */
    const MODE_SINGULAR = 'SINGULAR';

    /**
     * Internal flag to mark the mode as compound constraint
     * @var string
     */
    const MODE_COMPOUND = 'COMPOUND';

    /**
     * @var Morph_Query
     */
    private $query;

    /**
     * @var mixed
     */
    private $constraints;

    /**
     * @var string
     */
    private $mode;

    /**
     * @var int
     */
    private $sortDirection;

    /**
     * Create a new property object
     *
     * @param $query
     * @return Morph_Query_Property
     */
    public function __construct(Morph_Query $query)
    {
        $this->query = $query;
        $this->constraints = array();
    }

    ///////////////////////////////////////
    // WRAPPER FOR Morph_Query FUNCTIONS //
    ///////////////////////////////////////

    /**
     * Adds a new property
     *
     * @param string $propertyName
     * @return Morph_Query_Property
     */
    public function property($propertyName)
    {
        return $this->query->property($propertyName);
    }



    //////////////////////////
    // CONSTRAINT FUNCTIONS //
    //////////////////////////

    /**
     * Sets an equals constraint
     *
     * Note that you cannot use equals in conjunction with the other constraint types.
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function equals($value)
    {
        $this->setSingularConstraint($value);
        return $this;
    }

    /**
     * adds a greater than constraint
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function greaterThan($value)
    {
        $this->addCompoundConstraint('$gt', $value);
        return $this;
    }

    /**
     * adds a greater than or equal to constraint
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function greaterThanOrEqualTo($value)
    {
        $this->addCompoundConstraint('$gte', $value);
        return $this;
    }

    /**
     * adds a less than constraint
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function lessThan($value)
    {
        $this->addCompoundConstraint('$lt', $value);
        return $this;
    }

    /**
     * adds a less than or equal to constraint
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function lessThanOrEqualTo($value)
    {
        $this->addCompoundConstraint('$lte', $value);
        return $this;
    }

    /**
     * adds a not equal to constraint
     *
     * @param mixed $value
     * @return Morph_Query_Property
     */
    public function notEqualTo($value)
    {
        $this->addCompoundConstraint('$ne', $value);
        return $this;
    }

    /**
     * adds an in array constraint
     *
     * @param array $value
     * @return Morph_Query_Property
     */
    public function in(array $value)
    {
        $this->addCompoundConstraint('$in', $value);
        return $this;
    }

    /**
     * adds a not in array constraint
     *
     * @param array $value
     * @return Morph_Query_Property
     */
    public function notIn(array $value)
    {
        $this->addCompoundConstraint('$nin', $value);
        return $this;
    }

    /**
     * adds a constraint where all values in $value must be in this property
     *
     * This property must be an array
     *
     * @param array $value
     * @return Morph_Query_Property
     */
    public function all(array $value)
    {
        $this->addCompoundConstraint('$all', $value);
        return $this;
    }


    /**
     * Adds a regular expression constraint
     *
     * @param string $regex
     * @return Morph_Query_Property
     */
    public function regex($regex)
    {
        $this->setSingularConstraint(new MongoRegex($regex));
        return $this;
    }

    /**
     * Adds a like constraint
     *
     * Convenience wrapper for regex. Adds a wild card to the
     * start and end of the query $value.
     *
     * e.g. 'bob' is turned into  '.*bob.*'
     *
     * This is probably really in-efficient so I would recommend
     * using regex directly with a custom written pattern.
     *
     * @param string $value
     * @return Morph_Query_Property
     */
    public function like($value)
    {
        $term = '/.*' . $value . '.*/i';
        return $this->regex($value);
    }

    /**
     * Adds a constraint that this property must have $value entries
     *
     * This property must be an array
     *
     * @param int $value
     * @return Morph_Query_Property
     */
    public function size($value)
    {
        $this->addCompoundConstraint('$size', (int) $value);
        return $this;
    }

    /**
     * Sets the sort direction if needed
     *
     * @param $direction
     * @return Morph_Query_Property
     */
    public function sort($direction = Morph_Enum::DIRECTION_ASC)
    {
        switch ($direction) {
            case Morph_Enum::DIRECTION_ASC:
                $this->sortDirection = Morph_Enum::DIRECTION_ASC;
                break;
            case Morph_Enum::DIRECTION_DESC:
                $this->sortDirection = Morph_Enum::DIRECTION_DESC;
                break;
            default:
                throw new InvalidArgumentException("Sort direction should be -1 or 1");
        }
        return $this;
    }

    /**
     * Returns an array of all associated constraints
     *
     * @return array
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * Returns the sort direction of this property
     *
     * @return int
     */
    public function getSort()
    {
        return $this->sortDirection;
    }

    //////////////////////////////////////////
    // INTERNAL CONSTRAINT HELPER FUNCTIONS //
    //////////////////////////////////////////

    /**
     * Adds a new compound constraint
     *
     * @param string $type
     * @param mixed $value
     * @return void
     * @throws RuntimeException if this property is in singular constraint mode
     */
    private function addCompoundConstraint($type, $value)
    {
        if ($this->isPermitted(self::MODE_COMPOUND)) {
            $this->constraints[$type] = $value;
        } else {
            throw new RuntimeException("You cannot use a a property in both compound and singular modes");
        }
    }

    /**
     * Sets a value of a singular constraint
     *
     * @param mixed $value
     * @return void
     * @throws RuntimeException if this property is in compound constraint mode
     */
    private function setSingularConstraint($value)
    {
        if ($this->isPermitted(self::MODE_SINGULAR)) {
            $this->constraints = $value;
        } else {
            throw new RuntimeException("You cannot use a a property in both compound and singular modes");
        }
    }

    /**
     * Returns true only if the specified mode is permitted
     *
     * @param string $mode
     * @return boolean
     */
    private function isPermitted($mode)
    {
        $isPermitted = true;
        if (empty($this->mode)) {
            $this->mode = $mode;
        }

        if ($this->mode != $mode) {
            $isPermitted = false;
        }
        return $isPermitted;
    }

}
