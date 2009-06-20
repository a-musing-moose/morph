<?php

//Create and instance of Morph_Storage passing in the appropriate database
$mongo = new Mongo();
$storage = new Morph_Storage($mongo->selectDb('myDB'));

//Create and instance of our user class
$user = new User();
$user->firsName = 'Jonathan';
$user->lastName = 'Moss';
$user->userName = 'j.d.moss';
$user->dateOfBirth = strtotime('1978-09-12');

//Store that baby!
$storage->save($user);

?>