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

function getQuotes($connection, $sql)
{
  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = $statement->fetchAll();

  // var_dump($quotes);
  return $quotes;
}

$connection = getConnection();
$sql = buildQuery($_POST);
$quotes = getQuotes($connection, $sql);

// close connection
$connection = null;

require('./templates/afficheCit.php');
