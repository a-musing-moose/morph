<?php
class ComposeOneParent extends Morph_Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new Morph_Property_ComposeOne('Child', 'Child'));
        $this->addProperty(new Morph_Property_String('Name'));
    }

}