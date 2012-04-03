<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */
namespace morph;
/**
 * This class provides the Morph_Object <--> MongoDB
 * storage provider.
 *
 * @package Morph
 */
class Storage
{

	/**
	 * @var Morph_Storage
	 */
	private static $instance;

	/**
	 * @var MongoDB
	 */
	private $db;

	private $useSafe = false;

	/**
	 * Returns the singleton instance of this class
	 * @return Morph_Storage
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			throw new \RuntimeException("Morph Storage has not been initialised");
		}
		return self::$instance;
	}

	/**
	 * Initialises the storage object
	 *
	 * @param MongoDB $db
	 * @return Morph_Storage
	 */
	public static function init(\MongoDB $db)
	{
		self::$instance = new self($db);
		return self::$instance;
	}

	/**
	 * De-initilises the storage object
	 *
	 * @return void
	 */
	public static function deInit()
	{
		self::$instance = null;
	}

	/**
	 *
	 * @param MongoDB $db
	 * @return Morph_Mapper
	 */
	private function __construct(\MongoDB $db)
	{
		$this->db = $db;
	}

	/**
	 * If set to true then the 'safe' option for saves is used
	 *
	 * @param boolean $useSafe
	 */
	public function useSafe($useSafe)
	{
		$this->useSafe = (bool)$useSafe;
		return $this;
	}

	/**
	 * Returns the associated MongoDB object
	 *
	 * @return MongoDB
	 */
	public function getDatabase()
	{
		return $this->db;
	}

	/**
	 * Retrieves the contents of the specified $id
	 * and assigns them into $object
	 *
     * By default Morph sets the id to be an instance of MongoId().  When searching you need
     * to ensure you do the same by wrapping your id string in a MongoId object
	 *
	 * @param Morph\\Object $object
	 * @param mixed $id
	 * @return Morph\\Object
	 */
	public function fetchById(Object $object, $id)
	{
		$query = array('_id' => $id);
		$data = $this->db->selectCollection($object->collection())->findOne($query);
        return $this->setData($object, $data);
	}

	/**
	 * Returns all objects with an _id in $ids
	 *
     * By default Morph sets the id to be an instance of MongoId().  When searching you need
     * to ensure you do the same by wrapping your id string in a MongoId object
	 *
	 * @param Morph\\Object $object
	 * @param array $Ids
	 * @return Morph\\Iterator
	 */
	public function fetchByIds(Object $object, array $ids)
	{
		$query = new Query();
		$query->property('_id')->in($ids);
		return $this->findByQuery($object, $query);
	}

	/**
	 * Retrieves the contents of the specified $dbRef
	 * and assigns them into $object
	 *
	 * @param Morph_Object $object
	 * @param array $dbRef
	 * @return Morph_Object
	 */
	public function fetchByDbRef(Object $object, array $dbRef)
	{
		$data = $this->db->getDBRef($dbRef);
        return $this->setData($object, $data);
	}

	/**
	 * Saves the provided object if necessary
	 *
	 * @param $object
	 * @return Morph_Object
	 */
	public function save(Object $object)
	{
		$response = $object;
		if ($object->state() == Enum::STATE_DIRTY){
			$response = $this->update($object);
		} elseif ($object->state() == Enum::STATE_NEW) {
			$response = $this->insert($object);
		}
		return $response;
	}

	/**
	 * Inserts a new object into the database
	 *
	 * @param \morph\Object $object
	 * @param array $options
	 * @return \morph\Object
	 */
	private function insert(Object $object, array $options = array())
	{
		$data = $object->__getData();

		//set an id if we do not have one
		if(!\array_key_exists('_id', $data)){
			$id = array(
                '_id' => new \MongoId()
			);
			$data = \array_merge($id, $data);
		}

		$options = array_merge(array('safe'=>$this->useSafe), $options);

		$savedOk = $this->db->selectCollection($object->collection())->save($data, $options);
		if($savedOk){
			$object->__setData($data, Enum::STATE_CLEAN);
		}
		return $object;
	}

	/**
	 * Updates object in the database
	 *
	 * @param Morph_Object $object
	 * @return Morph_Object
	 */
	private function update(Object $object)
	{
		return $this->insert($object);
	}


	/**
	 * Deletes the object passed in from the database
	 * @param Morph_Object $object
	 * @return boolean
	 */
	public function delete(Object $object)
	{
		$query = array('_id' => $object->id());
		return $this->db->selectCollection($object->collection())->remove($query, array('justOne' => true));
	}

	/**
	 * Runs query against the database
	 *
	 * The results come packages up in a \morph\Iterator object
	 *
	 * @param  Object $object Required to determine the correct collection query against
	 * @param  IQuery $query
	 * @return \morph\Iterator
	 */
	public function findByQuery(Object $object, IQuery $query = null)
	{
		$class = get_class($object);

		$query = (is_null($query)) ? new Query() : $query;

		$rawQuery = $this->getRawQuery($object, $query);
		$cursor = $this->db->selectCollection($object->collection())->find($rawQuery);

		$limit = $query->getLimit();
		if (!\is_null($limit)) {
			$cursor->limit($limit);
		}

		$skip = $query->getSkip();
		if (!\is_null($skip)) {
			$cursor->skip($skip);
		}

		$sort = $query->getRawSort();
		if (!\is_null($sort)) {
			$cursor->sort($sort);
		}

		$iterator = new Iterator($object, $cursor);

		return $iterator;
	}

	/**
	 * Finds one object matching the passed in query
	 *
	 * @param Morph_Object $object
	 * @param Morph_IQuery $query
	 * @return Morph_Object
	 */
	public function findOneByQuery(Object $object, IQuery $query = null)
	{
		$result = null;
		$class = \get_class($object);

		$query = (is_null($query)) ? new Query() : $query;
		$rawQuery = $this->getRawQuery($object, $query);
		$data = $this->db->selectCollection($object->collection())->findOne($rawQuery);
        return $this->setData($object, $data);
	}

	/**
	 * Ensures that aliased properties are correctly converted in query
	 *
	 * @param Object $object
	 * @param IQuery $query
	 */
	private function getRawQuery(Object $object, IQuery $query)
	{
		$rawQuery = array();
		foreach ($query->getRawQuery() as $field => $value) {
			$storageName = $object->__getPropertySet()->getStorageName($field);
			$rawQuery[$storageName] = $value;
		}
		return $rawQuery;
	}

	/**
	 * Fetches a object representing a file in MongoDB
	 *
	 * @param mixed $id
	 * @return MongoGridFSFile
	 */
	public function fetchFile($id)
	{
		$query = array('_id' => $id);
		return $this->db->getGridFS()->findOne($query);
	}

	/**
	 * Saves a file to MongoDB
	 *
	 * @param string $filePath
	 * @param array $oldReference
	 * @return mixed The id of the stored file
	 */
	public function saveFile($filePath, $oldReference = null)
	{
		$id = null;
		if (file_exists($filePath)) {
			if (!empty($oldReference)) {
				$this->db->getGridFS()->remove(array('_id' => $oldReference), true); //remove existing file
			}
			$id = $this->db->getGridFS()->storeFile($filePath);
		} else {
			throw new \InvalidArgumentException("The file $filePath does not exist");
		}
		return $id;
	}

    /**
     * Sets data to the morph object
     *
     * @param  Object $object
     * @param  mixed  $data
     * @return Object
     * @throw  ObjectNotFound if data is empty
     */
    private function setData(Object $object, $data)
    {
        if (empty($data)) {
            throw new \morph\exception\ObjectNotFound();
        }
        $object->__setData($data, Enum::STATE_CLEAN);
		return $object;
    }
}
