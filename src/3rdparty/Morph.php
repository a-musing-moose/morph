<?php
/**
 * @package Morph
 * @author  Kanstantsin Kamkou <kkamkou@gmail.com>
 */

/**
 * @see Zend_Paginator_Adapter_Interface
 */
class Yourproject_Paginator_Adapter_Morph implements Zend_Paginator_Adapter_Interface
{
    /**
     * Item count
     * @var integer
     */
    protected $_count = null;

    /**
     * Stores the morph object
     * @var \morph\Iterator
     */
    protected $_iterator;

    /**
     * Constructor
     *
     * @param \morph\Iterator $iterator
     */
    public function __construct(\morph\Iterator $iterator)
    {
        $this->_iterator = $iterator;
        $this->_count = $iterator->totalCount();
    }

    /**
     * Returns an collection of items for a page.
     *
     * @param  integer $offset Page offset
     * @param  integer $itemCountPerPage Number of items per page
     * @return \morph\Iterator
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->_iterator->getCursor()
            ->skip($offset)
            ->limit($itemCountPerPage);
        return $this->_iterator;
    }

    /**
     * Returns count of entries
     * @return int
     */
    public function count()
    {
        return $this->_count;
    }
}
