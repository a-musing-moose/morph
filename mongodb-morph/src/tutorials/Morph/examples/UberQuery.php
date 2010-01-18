<?php
//Initialise Morph_Storage passing in the appropriate database
$mongo = new Mongo();
Morph_Storage::init($mongo->selectDb('myDB'));

$query = new Morph_Query();
$query->property('createdDate')
      ->greaterThan(MongoDate(time() - 604800)) //last week
      ->lessThan(new MondoDate(time())) //today
      ->property('cost')
      ->greaterThanOrEqualTo(12.99)
      ->property('publisher')
      ->in(array('publisherA', 'publisherB', 'publisherC'));
/**
 * This query is roughly equivalent to the sql:
 * SELECT * FROM `ABook` WHERE
 *     `createdDate` > DATE_SUB(now(), INTERVAL 1 WEEK)
 * AND `createdDate` < now()
 * AND `cost` >= 12.99
 * AND `publisher` in ('publisherA', 'publisherB', 'publisherC');
 */
$book = new A_Book();
$books = $book->findByQuery($query);
?>