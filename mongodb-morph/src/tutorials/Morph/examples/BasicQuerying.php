<?php
//Initialise Morph_Storage passing in the appropriate database
$mongo = new Mongo();
Morph_Storage::init($mongo->selectDb('myDB'));;

//Find users with the userName = j.d.moss
$user = new User();
$query = new Morph_Query();
$query->property('userName')->equals('j.d.moss');
$users = $user->findByQuery($query);
?>