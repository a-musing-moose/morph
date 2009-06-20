<?php
/**
 * An example Morph_Object derived class
 *
 * @property string $userName
 * @property string $firstName
 * @property string $lastName
 * @property int $dateOfBirth
 */
class User extends Morph_Object
{

	public function __construct($id = null) {
	    parent::__construct($id);

	    $this->addProperty(new Morph_Property_String('userName'))
	         ->addProperty(new Morph_Property_String('firstName'))
	         ->addProperty(new Morph_Property_String('lastName'))
	         ->addProperty(new Morph_Property_Date('dateOfBirth'))
	         ->addProperty(new Morph_Property_Integer('numberOfPosts', 0));

	}

}
?>