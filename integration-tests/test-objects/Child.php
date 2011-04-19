<?php

class Child extends \morph\Object
{
    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\String('Name'));
        $this->addProperty(new \morph\property\Integer('Age'));
    }

}