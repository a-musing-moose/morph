<?php
//Create and instance of Morph_Storage passing in the appropriate database
$mongo = new Mongo();
$storage = new Morph_Storage($mongo->selectDb('myDB'));

//Find users with the userName = j.d.moss
$query = new Morph_Query();
$query->property('userName')->equals('j.d.moss');
$users = $storage->findByQuery(new User(), $query);
?>