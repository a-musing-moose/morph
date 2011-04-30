<?php
class User extends \morph\Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\String('Username'))
             ->addProperty(new \morph\property\File('Avatar'));
    }

}