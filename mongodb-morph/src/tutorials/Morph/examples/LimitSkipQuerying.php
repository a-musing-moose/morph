<?php
//Initialise Morph_Storage passing in the appropriate database
$mongo = new Mongo();
Morph_Storage::init($mongo->selectDb('myDB'));

//Find a maximum of 10 users that has more than 15 posts, skipping the first 10
$user = new User();
$query = new Morph_Query();
$query->limit(10)
      ->skip(10)
      ->property('numberOfPosts')
      ->greaterThan(15);
$users = $user->findByQuery($query);
?>