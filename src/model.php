<?php


function getConnection()
{
  // establish connection
  try {
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
  } catch (Exception $e) {
    die('Connection failed: ' . $e->getMessage());
  }
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

function getAuthors()
{
  // get all distinct author names

  $connection = getConnection();

  $statement = $connection->prepare('
    SELECT DISTINCT id, lastName, firstName FROM author ORDER BY lastName
  ');
  $statement->execute();

  $connection = null;

  return $statement->fetchAll();
}

function getCenturies()
{
  // get all distinct centuries

  $connection = getConnection();

  $statement = $connection->prepare('
    SELECT DISTINCT century FROM author ORDER BY century
  ');
  $statement->execute();

  $connection = null;

  return $statement->fetchAll();
}

function getIndexData()
{
  $indexData = [];
  $indexData['randomQuote'] = getRandomQuote();
  $indexData['authors'] = getAuthors();
  $indexData['centuries'] = getCenturies();

  return $indexData;
}