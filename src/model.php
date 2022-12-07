<?php

require_once('src/models/author.php');
require_once('src/models/quote.php');

function getConnection()
{
  // establish connection
  
  // get database config
  $dbConfigFile = file_get_contents('./config/config.json');
  $dbConfig = json_decode($dbConfigFile);

  extract(get_object_vars($dbConfig->database));

  $connection = new PDO(
    'mysql:host=' . $host . ';dbname=' . $dbname,
    $login,
    $password
  );
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  return $connection;
}

function getIndexData()
{
  $quoteRepository = new QuoteRepository();
  $indexData = [];
  $indexData['randomQuote'] = $quoteRepository->getRandomQuote();
  $indexData['authors'] = getAuthors();
  $indexData['centuries'] = getCenturies();

  return $indexData;
}

function buildQuery($postData)
{
  extract($_POST);

  $sql = 'SELECT text, lastName, firstName, century FROM quote
    INNER JOIN author ON quote.authorId = author.id';

  // handle query search and selects

  $queries = [];

  if ($q !== '') {
    $queries[] = 'text LIKE "%' . $q . '%"';
  }
  if (isset($author) && !empty($author)) {
    $queries[] = 'author.id = ' . $author;
  }
  if (isset($century) && !empty($century)) {
    $queries[] = 'century = ' . $century;
  }

  if (count($queries) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $queries);
  }

  // assign sort by author as default
  if (!isset($sortBy) || $sortBy === 'author') {
    $sortBy = 'author.lastName, author.firstName';
  }
  $sql .=  ' ORDER BY ' . $sortBy;

  // var_dump($sql);
  return $sql;
}
