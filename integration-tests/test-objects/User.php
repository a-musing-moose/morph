<?php
class User extends Morph_Object
{

    public function __construct($id = null)
    {
        parent::__construct($id);
        $this->addProperty(new Morph_Property_String('Username'))
             ->addProperty(new Morph_Property_File('Avatar'));
    }

}