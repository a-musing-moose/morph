<?php
class ComposeOneParent extends \morph\Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new \morph\property\ComposeOne('Child', 'Child'));
        $this->addProperty(new \morph\property\String('Name'));
    }

}