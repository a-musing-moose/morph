<?php
class Child extends Morph_Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new Morph_Property_String('Name'));
        $this->addProperty(new Morph_Property_Integer('Age'));
    }

}