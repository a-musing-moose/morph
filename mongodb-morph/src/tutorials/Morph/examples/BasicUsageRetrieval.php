<?php

//Initialise Morph_Storage passing in the appropriate database
$mongo = new Mongo();
Morph_Storage::init($mongo->selectDb('myDB'));

//Fetches and instance of User by its id
$user = new User();
$user = $storage->loadById(1234);

//do something with it's properties
echo $user->firstName;
?>