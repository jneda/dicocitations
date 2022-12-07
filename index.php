<?php

include './templates/header.html';

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

  // get a random quote

  // get quotes count
  $statement = $connection->prepare('
    SELECT * FROM quote
    INNER JOIN author ON author.id = quote.authorId'
  );
  $statement->execute();
  $result = $statement->fetchAll();

  $randomQuote = $result[rand(0, count($result) - 1)];

  include('./templates/quote.php');

  // get all distinct author names
  $statement = $connection->prepare('
    SELECT DISTINCT id, lastName, firstName FROM author ORDER BY lastName'
  );
  $statement->execute();
  $authors = $statement->fetchAll();

  //var_dump($authors);

  // get all distinct centuries
  $statement = $connection->prepare('
    SELECT DISTINCT century FROM author ORDER BY century'
  );
  $statement->execute();
  $centuries = $statement->fetchAll();

  //var_dump($centuries);

  // close connection
  $connection = null;
} catch (Exception $e) {
  echo 'Connection failed: ' . $e->getMessage();
}

include './templates/searchQuoteForm.php';

include './templates/footer.html';
