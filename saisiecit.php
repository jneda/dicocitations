<?php

include './templates/header.html';

include './templates/addQuoteForm.php';

if (isset($_POST) && !empty($_POST)) {
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

    // search for author in database
    extract($_POST);
    $statement = $connection->prepare('
      SELECT * FROM author WHERE lastName=? AND firstName=?'
    );
    $statement->execute([$lastName, $firstName]);

    // show result
    $result = $statement->fetchAll();
    // var_dump($result);
    // var_dump($result[0]['id']);

    $authorId = null;
    if (empty($result)) {
      //echo 'Auteur inconnu - ';      

      // add author to database
      $statement = $connection->prepare('
        INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)'
      );
      $statement->execute([$lastName, $firstName, $century]);

      $lastInsertId = $connection->lastInsertId();
      if ($lastInsertId) {
        echo 'Auteur ajouté à la base de données<br/>';
        $authorId = $lastInsertId;
      }
    } else {
      $authorId = $result[0]['id'];
    }

    //echo 'Id de l\'auteur : ' . $authorId . '<br/>';

    // search for quote in database
    $statement = $connection->prepare('SELECT * FROM quote WHERE text=?');
    $statement->execute([$quoteText]);

    // show result
    $result = $statement->fetchAll();
    //var_dump($result);

    if (!empty($result)) {
      echo 'Cette citation existe déjà<br/>';
    } else {
      // add quote to database
      $statement = $connection->prepare('
        INSERT INTO quote (text, authorId) VALUES (?, ?)'
      );
      $statement->execute([$quoteText, $authorId]);

      if ($connection->lastInsertId()) {
        echo '<br/>Citation ajoutée à la base de données<br/>';
      }
    }

    // close connection
    $connection = null;
  } catch (Exception $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}

include './templates/footer.html';
