<?php

require_once('src/model.php');

class Quote
{
  public string $text;
  public string $authorName;
  public int $century;
}


function getQuotes(string $sql): array
{
  $connection = getConnection();

  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = [];
  while ($row = $statement->fetch()) {
    $quote = new Quote();
    $quote->text = $row['text'];
    $quote->authorName = $row['lastName'] . ' ' . $row['firstName'];
    $quote->century = $row['century'];
    $quotes[] = $quote;
  }

  $connection = null;
  
  return $quotes;
}

function getRandomQuote(): Quote
{
  // get a random quote

  $sql = '
    SELECT * FROM quote
    INNER JOIN author ON author.id = quote.authorId
  ';

  $quotes = getQuotes($sql);

  return $quotes[rand(0, count($quotes) - 1)];
}

function quoteExists(string $quoteText): bool
{
  // search for quote in database

  $connection = getConnection();

  $statement = $connection->prepare('SELECT * FROM quote WHERE text=?');
  $statement->execute([$quoteText]);
  $result = $statement->fetchAll();

  $connection = null;

  return !empty($result);
}

function insertQuote(string $quoteText, int $authorId): bool
{
  // add quote to database

  $connection = getConnection();

  if ($quoteText === '') {
    throw new Exception('Texte de la citation manquant.');
  }

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
