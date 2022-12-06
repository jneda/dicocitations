<?php

include './includes/header.php';

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

  include('./includes/quote.php');

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

?>

<form action="affichecit.php" method="POST">
  <label for="q">Rechercher :</label>
  <input type="search" name="q" id="q">
  <label for="author">Auteur :</label>
  <select name="author" id="author">
    <option disabled selected value></option>
    <?php foreach ($authors as $author) { ?>
      <option value="<?= $author['id'] ?>">
        <?= $author['lastName'] . ' ' . $author['firstName'] ?>
      </option>
    <?php } ?>
  </select>
  <label for="century">Siècle :</label>
  <select name="century" id="century">
    <option disabled selected value></option>
    <?php foreach ($centuries as $century) { ?>
      <option value="<?= $century[0] ?>">
        <?= $century[0] ?>
      </option>
    <?php } ?>
  </select>
  <label for="author">Trier par auteur</label>
  <input type="radio" name="sortBy" value="author">
  <label for="century">Trier par siècle</label>
  <input type="radio" name="sortBy" value="century">
  <input type="submit" value="Rechercher">
</form>

<a href="saisiecit.php">Ajouter une citation</a>

<?php
include './includes/footer.php';
