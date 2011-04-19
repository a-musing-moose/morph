<?php
/**
 * @package MongoUnit
 * @author Jonathan Moss <jonathan.moss@tangentlabs.co.uk>
 * @copyright 2010 Tangent Labs
 * @version SVN: $Id$
 */
namespace mongoUnit;
/**
 * Loads data into a collection
 *
 * @package MongoUnit
 */
class JsonLoader
{

    private $collection;

    public function __construct(\MongoCollection $collection)
    {
        $this->collection = $collection;
    }

    public function loadFromJsonFile($filePath)
    {
        if (!\file_exists($filePath)) {
            throw new InvalidArgumentException("Could not locate $filePath");
        }
        $json = \file_get_contents($filePath);
        return $this->loadJsonFromString($json);
    }

    public function loadJsonFromString($json)
    {
        if (!$this->isValidJson($json)) {
            throw new \InvalidArgumentException("Invalid Json");
        }
        $data = \json_decode($json, true);
        $this->collection->drop();
        return $this->collection->batchInsert($data);
    }

    private function isValidJson($json)
    {
        return (\json_decode($json) !== null) ? true : false;
    }
}