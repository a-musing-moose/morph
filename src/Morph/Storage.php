<?php
/**
 * @package Morph
 * @author Jonathan Moss <xirisr@gmail.com>
 * @copyright Jonathan Moss 2009
 */

/**
 * This class provides the Morph_Object <--> MongoDB
 * storage provider.
 *
 * @package Morph
 */
class Morph_Storage
{

	/**
	 * @var Morph_Storage
	 */
	private static $instance;

	/**
	 * @var MongoDB
	 */
	private $Db;

	/**
	 * Returns the singleton instance of this class
	 * @return Morph_Storage
	 */
	public static function instance()
	{
		if (!isset(self::$instance)) {
			throw new RuntimeException("Morph_Storage has not been initialised");
		}
		return self::$instance;
	}

	/**
	 * Initialises the storage object
	 *
	 * @param MongoDB $db
	 * @return Morph_Storage
	 */
	public static function init(MongoDB $db)
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
	private function __construct(MongoDB $db)
	{
		$this->Db = $db;
	}

	/**
	 * Returns the associated MongoDB object
	 *
	 * @return MongoDB
	 */
	public function getDatabase()
	{
		return $this->Db;
	}

	/**
	 * Retrieves the contents of the specified $id
	 * and assigns them into $object
	 *
	 * @param Morph_Object $object
	 * @param mixed $id
	 * @return Morph_Object
	 */
	public function fetchById(Morph_Object $object, $id)
	{
		$query = array('_id' => $id);
		$data = $this->Db->selectCollection($object->collection())->findOne($query);
        return $this->setData($object, $data);
	}

	/**
	 * Returns all objects with an _id in $ids
	 *
	 * @param Morph_Object $object
	 * @param array $Ids
	 * @return Morph_Iterator
	 */
	public function fetchByIds(Morph_Object $object, array $ids)
	{
		$query = new Morph_Query();
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
	public function fetchByDbRef(Morph_Object $object, array $dbRef)
	{
		$data = $this->Db->getDBRef($dbRef);
        return $this->setData($object, $data);
	}

	/**
	 * Saves the provided object if necessary
	 *
	 * @param $object
	 * @return Morph_Object
	 */
	public function save(Morph_Object $object)
	{
		$response = $object;
		if ($object->state() == Morph_Enum::STATE_DIRTY){
			$response = $this->update($object);
		} elseif ($object->state() == Morph_Enum::STATE_NEW) {
			$response = $this->insert($object);
		}
		return $response;
	}

	/**
	 * Inserts a new object into the database
	 *
	 * @param Morph_Object $object
	 * @return Morph_Object
	 */
	private function insert(Morph_Object $object)
	{
		$data = $object->__getData();

		//set an id if we do not have one
		if(!array_key_exists('_id', $data)){
			$id = array(
                '_id' => md5(uniqid(rand(), true))
			);
			$data = array_merge($id, $data);
		}
		$savedOk = $this->Db->selectCollection($object->collection())->save($data);
		if($savedOk){
			$object->__setData($data, Morph_Enum::STATE_CLEAN);
		}
		return $object;
	}

	/**
	 * Updates object in the database
	 *
	 * @param Morph_Object $object
	 * @return Morph_Object
	 */
	private function update(Morph_Object $object)
	{
		return $this->insert($object);
	}


	/**
	 * Deletes the object passed in from the database
	 * @param Morph_Object $object
	 * @return boolean
	 */
	public function delete(Morph_Object $object)
	{
		$query = array('_id' => $object->id());
		return $this->Db->selectCollection($object->collection())->remove($query, true);
	}

	/**
	 * Runs query against the database
	 *
	 * The results come packages up in a Morph_Iterator object
	 *
	 * @param Morph_Object $object Required to determine the correct collection query against
	 * @param Morph_IQuery $query
	 * @return Morph_Iterator
	 */
	public function findByQuery(Morph_Object $object, Morph_IQuery $query = null)
	{
		$query = (is_null($query)) ? new Morph_Query() : $query;

        $cursor = $this->Db
            ->selectCollection($object->collection())
            ->find($query->getRawQuery());

		$limit = $query->getLimit();
		if (!is_null($limit)) {
			$cursor->limit($limit);
		}

		$skip = $query->getSkip();
		if (!is_null($skip)) {
			$cursor->skip($skip);
		}

		$sort = $query->getRawSort();
		if (!is_null($sort)) {
			$cursor->sort($sort);
		}

		return new Morph_Iterator($object, $cursor);
	}

	/**
	 * Finds one object matching the passed in query
	 *
	 * @param Morph_Object $object
	 * @param Morph_IQuery $query
	 * @return Morph_Object
	 */
	public function findOneByQuery(Morph_Object $object, Morph_IQuery $query = null)
	{
		$query = (is_null($query)) ? new Morph_Query() : $query;

        $data = $this->Db
            ->selectCollection($object->collection())
            ->findOne($query->getRawQuery());

        return (count($data) ? $this->setData($object, $data) : array());
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
		return $this->Db->getGridFS()->findOne($query);
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
				$this->Db->getGridFS()->remove(array('_id'=>$oldReference), true); //remove existing file
			}
			$id = $this->Db->getGridFS()->storeFile($filePath);
		} else {
			throw new InvalidArgumentException("The file $filePath does not exist");
		}
		return $id;
	}

    /**
     * @param Morph_Object $object
     * @param array $data
     * @return Morph_Object
     */
    private function setData(Morph_Object $object, $data)
    {
        if (empty($data)) {
            echo '!empty!';
            #throw new Morph_Exception_ObjectNotFound();
        }
        $object->__setData($data, Morph_Enum::STATE_CLEAN);
		return $object;
    }
}