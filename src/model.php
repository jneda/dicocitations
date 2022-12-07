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

function getAuthorId($authorNames)
{
  // search for author in database

  $connection = getConnection();

  $statement = $connection->prepare('
    SELECT * FROM author WHERE lastName=? AND firstName=?
  ');
  $statement->execute($authorNames);
  $result = $statement->fetchAll();

  $connection = null;

  $authorId = null;
  if (!empty($result)) {
    $authorId = $result[0]['id'];
  }

  return $authorId;
}

function insertAuthor($authorData)
{
  // add author to database

  $connection = getConnection();

  $statement = $connection->prepare('
    INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)
  ');
  $statement->execute($authorData);

  $lastInsertId = $connection->lastInsertId();

  $connection = null;

  if ($lastInsertId) {
    echo 'Auteur ajouté à la base de données<br/>';
    return $lastInsertId;
  } else {
    die("Failed to insert author into database");
  }
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
    echo '<br/>Citation ajoutée à la base de données<br/>';
  }

  $connection = null;
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

function getQuotes($sql)
{
  $connection = getConnection();

  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = $statement->fetchAll();

  $connection = null;
  
  return $quotes;
}
