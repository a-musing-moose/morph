<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright 2009 Jonathan Moss
 */
namespace morph;
/**
 * A query class to represent queries to the database
 *
 * This class provides a uber fluent interface so you can do
 * cool stuff like:
 *
 * <code>
 *
 * $query = new Morph_Query();
 * $query->limit(10)
 *       ->property('name')
 *       ->equals('jon')
 *       ->property('age')
 *       ->greaterThanOrEqualTo('30')
 *       ->lessThan('40');
 *
 * $results = $MorphStorage->findByQuery($anObject, $query);
 *
 * </code>
 *
 * The above example is roughly eqivalent to the SQL query:
 *
 * <code>
 * SELECT * FROM `$anObject->collection()` WHERE
 *     `name` = 'jon'
 *     AND `age` >= 30
 *     AND `age` <  40
 * LIMIT 10;
 * </code>
 *
 * Yeah, it looks like more code but with autocomplete it is super
 * fast to type in!
 *
 * @package Morph
 */
class Query implements IQuery
{

    /**
     * @var array
     */
	private $criteria;

	/**
	 * @var int
	 */
	private $limit;

	/**
	 * @var int
	 */
	private $numberToSkip;

	/**
	 * Static function to create and instance of Morph_Query
	 *
	 * This just makes it easier to chain both creation and the
	 * actual criteria:
	 * <code>
	 * $query = Morph_Query::instance()
	 *   ->limit(10)
     *   ->property('name')
     *   ->equals('jon');
	 * </code>
	 *
	 * @return Morph_Query
	 */
	public static function instance()
	{
	    return new self;
	}

	/**
	 * Creates a new Morph query object
	 *
	 * @return Morph_Query
	 */
	public function __construct()
	{
	    $this->criteria = array();
	}

	/**
	 * Sets the maximum number of results to return
	 *
	 * @param int $limit
	 * @return Morph_Query
	 */
	public function limit($limit)
	{
	    $this->limit = (int) $limit;
	    return $this;
	}

	/**
	 * Returns the limit
	 *
	 * or null if no limit has been set
	 *
	 * @return int
	 */
	public function getLimit()
	{
	    return $this->limit;
	}

	/**
     * Sets the number of results to skip
     *
     * @param int $numberToSkip
     * @return Morph_Query
     */
	public function skip($numberToSkip)
	{
	    $this->numberToSkip = (int) $numberToSkip;
	    return $this;
	}

	/**
	 * Returns the number to skip
	 *
	 * Or null if skip has not been set
	 *
	 * @return int
	 */
	public function getSkip()
	{
	    return $this->numberToSkip;
	}

	/**
	 * adds a new property criteria
	 *
	 * @param string $propertyName
	 * @return Morph_Query_Property
	 */
	public function property($propertyName)
	{
	    $property = new \morph\query\Property($this);
	    $this->criteria[$propertyName] = $property;
	    return $property;
	}



	/**
	 * Returns a query suitable for passing to MongoDB
	 *
	 * @return array
	 */
	public function getRawQuery()
	{
	    $query = array();

	    if (count($this->criteria) > 0) {
            foreach ($this->criteria as $propertyName => $criteria) {
                $constraints = $criteria->getConstraints();
                if (isset($constraints)) {
                    $query[$propertyName] = $constraints;
                }
            }
	    }

	    return $query;
	}

	/**
	 * Returns sorting details suitable for passing to MongoDB
	 *
	 * @return array
	 */
	public function getRawSort()
	{
	    $sort = array();
	    if (count($this->criteria) > 0) {
            foreach ($this->criteria as $propertyName => $criteria) {
                $direction = $criteria->getSort();
                if (null !== $direction) {
                    $sort[$propertyName] = $direction;
                }
            }
        }
        if (empty($sort)) {
            $sort = null;
        }
	    return $sort;
	}
}
