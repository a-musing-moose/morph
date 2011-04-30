<?php
class HasOneParent extends \morph\Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\HasOne('Child', 'Child'));
        $this->addProperty(new \morph\property\String('Name'));
    }

}