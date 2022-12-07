<?php

require('./templates/header.html');
require('./templates/addQuoteForm.php');
require('./templates/footer.html');

function getConnection()
{
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

function getAuthorId($connection, $authorNames)
{
  // search for author in database
  $statement = $connection->prepare('
    SELECT * FROM author WHERE lastName=? AND firstName=?
  ');
  $statement->execute($authorNames);
  $result = $statement->fetchAll();

  $authorId = null;
  if (!empty($result)) {
    $authorId = $result[0]['id'];
  }

  return $authorId;
}

function insertAuthor($connection, $authorData)
{
  // add author to database
  $statement = $connection->prepare('
    INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)
  ');
  $statement->execute($authorData);

  $lastInsertId = $connection->lastInsertId();
  if ($lastInsertId) {
    echo 'Auteur ajouté à la base de données<br/>';
    return $lastInsertId;
  } else {
    die("Failed to insert author into database");
  }
}

function quoteExists($connection, $quoteText)
{
  // search for quote in database
  $statement = $connection->prepare('SELECT * FROM quote WHERE text=?');
  $statement->execute([$quoteText]);
  $result = $statement->fetchAll();

  return !empty($result);
}

function insertQuote($connection, $quoteText, $authorId)
{
  // add quote to database
  $statement = $connection->prepare('
    INSERT INTO quote (text, authorId) VALUES (?, ?)
  ');
  $statement->execute([$quoteText, $authorId]);

  if ($connection->lastInsertId()) {
    echo '<br/>Citation ajoutée à la base de données<br/>';
  }
}

if (isset($_POST) && !empty($_POST)) {
  extract($_POST);
  $connection = getConnection();
  
  $authorId = getAuthorId($connection, [$lastName, $firstName]);
  if ($authorId === null) {
    $authorId = insertAuthor($connection, [$lastName, $firstName, $century]);
  }

  if (quoteExists($connection, $quoteText)) {
    echo 'Cette citation existe déjà<br/>';
  } else {
    insertQuote($connection, $quoteText, $authorId);
  }

  // close connection
  $connection = null;
}
