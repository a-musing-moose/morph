<?php
class HasManyParent extends Morph_Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new Morph_Property_HasMany('Children', 'Child'));
        $this->addProperty(new Morph_Property_String('Name'));
    }

}