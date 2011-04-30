<?php
class ComposeManyParent extends \morph\Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\ComposeMany('Children', 'Child'));
        $this->addProperty(new \morph\property\String('Name'));
    }

}