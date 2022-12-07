<?php

require('./templates/header.html');

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

function getRandomQuote($connection)
{
  // get a random quote

  // get quotes count
  $statement = $connection->prepare('
    SELECT * FROM quote
    INNER JOIN author ON author.id = quote.authorId
  ');
  $statement->execute();
  $result = $statement->fetchAll();

  return $result[rand(0, count($result) - 1)];
}

function getAuthors($connection)
{
  // get all distinct author names
  $statement = $connection->prepare('
    SELECT DISTINCT id, lastName, firstName FROM author ORDER BY lastName
  ');
  $statement->execute();
  return $statement->fetchAll();
}

function getCenturies($connection)
{
  // get all distinct centuries
  $statement = $connection->prepare('
    SELECT DISTINCT century FROM author ORDER BY century
  ');
  $statement->execute();
  return $statement->fetchAll();
}

$connection = getConnection();
$randomQuote = getRandomQuote($connection);
$authors = getAuthors($connection);
$centuries = getCenturies($connection);
$connection = null;

require('./templates/index.php');
require('./templates/footer.html');
