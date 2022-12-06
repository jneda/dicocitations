<?php

include './includes/header.php';

var_dump($_POST);

// establish connection
$connection = null;
try {
  // get database config
  $dbConfigFile = file_get_contents('./config/config.json');
  $dbConfig = json_decode($dbConfigFile);
  //var_dump(get_object_vars($dbConfig->database));
  extract(get_object_vars($dbConfig->database));

  $connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $login, $password);
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo 'Connection established';

  extract($_POST);

  // handle query string
  if ($q === '') {
    $q = '*';
  }

  // assign sort by author as default
  if (!isset($sortBy) || $sortBy === 'author') {
    $sortBy = 'author.lastName, author.firstName';
  }

  var_dump($sortBy);

  $sql = 'SELECT text, lastName, firstName, century FROM quote INNER JOIN author ON quote.authorId = author.id';
  if (isset($author) && isset($century)) {
    $sql .= ' WHERE author.id = ' . $author . ' AND century = ' . $century;
  } else if (isset($author)) {
    $sql .= ' WHERE author.id = ' . $author;
  } else if (isset($century)) {
    $sql .= ' WHERE century = ' . $century;
  }
  $sql .=  ' ORDER BY ' . $sortBy;

  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = $statement->fetchAll();

  // var_dump($quotes);

  // close connection
  $connection = null;
} catch (Exception $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

include './includes/quoteTable.php';

?>
<a href="index.php">Accueil</a>
<a href="saisiecit.php">Ajouter une citation</a>

<?php
include './includes/footer.php';
