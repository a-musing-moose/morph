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
     * @var MongoDB
     */
    private $Db;

    /**
     *
     * @param MongoDB $db
     * @return Morph_Mapper
     */
    public function __construct(MongoDB $db)
    {
        $this->Db = $db;
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
        $object->__setData($data, Morph_Object::STATE_CLEAN);
        $object->storage($this);
        return $object;
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
     * Saves the provided object if necessary
     *
     * @param $object
     * @return Morph_Object
     */
    public function save(Morph_Object $object)
    {
        $response = $object;
        $object->__setStorage($this); //ensure that the storage object is set
        if ($object->state() == Morph_Object::STATE_DIRTY){
            $response = $this->update($object);
        } elseif ($object->state() == Morph_Object::STATE_NEW) {
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
                '_id'=> md5(uniqid(rand(), true))
            );
            $data = array_merge($id, $data);
        }
        $savedOk = $this->Db->selectCollection($object->collection())->save($data);
        if($savedOk){
            $object->__setData($data, Morph_Object::STATE_CLEAN);
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
     * @param Morph_Query $query
     * @return Morph_Iterator
     */
    public function findByQuery(Morph_Object $object, Morph_Query $query = null)
    {
        $class = get_class($object);

        $query = (is_null($query)) ? new Morph_Query() : $query;
        $cursor = $this->Db->selectCollection($object->collection())->find($query->getRawQuery());
        if (!is_null($query->getLimit())) {
            $results->limit($query->getLimit());
        }

        if (!is_null($query->getSkip())) {
            $results->skip($query->getSkip());
        }

        $iterator = new Morph_Iterator($object, $this, $cursor);

        return $iterator;
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
}