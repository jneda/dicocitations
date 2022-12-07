<?php

require_once('src/model.php');


function getQuotes($sql)
{
  $connection = getConnection();

  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = $statement->fetchAll();

  $connection = null;
  
  return $quotes;
}

function getRandomQuote()
{
  // get a random quote

  $connection = getConnection();

  // get quotes count
  $statement = $connection->prepare('
    SELECT * FROM quote
    INNER JOIN author ON author.id = quote.authorId
  ');
  $statement->execute();
  $result = $statement->fetchAll();

  $connection = null;

  return $result[rand(0, count($result) - 1)];
}

function quoteExists($quoteText)
{
  // search for quote in database

  $connection = getConnection();

  $statement = $connection->prepare('SELECT * FROM quote WHERE text=?');
  $statement->execute([$quoteText]);
  $result = $statement->fetchAll();

  $connection = null;

  return !empty($result);
}

function insertQuote($quoteText, $authorId)
{
  // add quote to database

  $connection = getConnection();

  $statement = $connection->prepare('
    INSERT INTO quote (text, authorId) VALUES (?, ?)
  ');
  $statement->execute([$quoteText, $authorId]);

  if ($connection->lastInsertId()) {
    $ok = true;
  } else {
    $ok = false;
  }

  $connection = null;

  return $ok;
}
