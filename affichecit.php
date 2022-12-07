<?php

include './templates/header.html';

//var_dump($_POST);

// establish connection
$connection = null;
try {
  // get database config
  $dbConfigFile = file_get_contents('./config/config.json');
  $dbConfig = json_decode($dbConfigFile);
  //var_dump(get_object_vars($dbConfig->database));
  extract(get_object_vars($dbConfig->database));

  $connection = new PDO(
    'mysql:host=' . $host . ';dbname=' . $dbname,
    $login,
    $password
  );
  $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo 'Connection established';

  extract($_POST);

  $sql = '
    SELECT text, lastName, firstName, century FROM quote
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

  //var_dump($queries);

  if (count($queries) > 0) {
    $sql .= ' WHERE ' . implode(' AND ', $queries);
  }

  // assign sort by author as default
  if (!isset($sortBy) || $sortBy === 'author') {
    $sortBy = 'author.lastName, author.firstName';
  }

  // var_dump($sortBy);
  $sql .=  ' ORDER BY ' . $sortBy;

  // var_dump($sql);

  $statement = $connection->prepare($sql);
  $statement->execute();

  $quotes = $statement->fetchAll();

  // var_dump($quotes);

  // close connection
  $connection = null;
} catch (Exception $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

if (count($quotes) === 0) {
  include './templates/noQuotesFound.html';
} else {
  include './templates/quoteTable.php';
}

?>

<div class="container">
  <nav>
    <div class="list-group">
      <a href="index.php" class="list-group-item list-group-item-action">Accueil</a>
      <a href="saisiecit.php" class="list-group-item list-group-item-action">Ajouter une citation</a>
    </div>
  </nav>
</div>


<?php
include './templates/footer.html';
