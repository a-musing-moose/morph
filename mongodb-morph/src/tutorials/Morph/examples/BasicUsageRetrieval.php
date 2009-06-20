<?php

//Create and instance of Morph_Storage passing in the appropriate database
$mongo = new Mongo();
$storage = new Morph_Storage($mongo->selectDb('myDB'));

//Fetches and instance of User by its id
$user = $storage->fetchById(new User(), 1234);

//do something with it's properties
echo $user->firstName;
?>