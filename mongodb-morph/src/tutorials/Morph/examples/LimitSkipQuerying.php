<?php
//Create and instance of Morph_Storage passing in the appropriate database
$mongo = new Mongo();
$storage = new Morph_Storage($mongo->selectDb('myDB'));

//Find a maximum of 10 users that has more than 15 posts, skipping the first 10
$query = new Morph_Query();
$query->limit(10)
      ->skip(10)
      ->property('numberOfPosts')
      ->greaterThan(15);
$users = $storage->findByQuery(new User(), $query);
?>