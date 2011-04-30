<?php
class HasManyParent extends \morph\Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\HasMany('Children', 'Child'));
        $this->addProperty(new \morph\property\String('Name'));
    }

}