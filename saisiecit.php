<?php include './includes/header.php'; ?>

<form action="saisiecit.php" method="POST">
  <label for="lastName">Nom de l'auteur :</label>
  <input type="text" name="lastName" id="lastName">
  <label for="firstName">Prénom de l'auteur :</label>
  <input type="text" name="firstName" id="firstName">
  <label for="century">Siècle :</label>
  <select name="century" id="century">
    <?php for ($i = 21; $i >= -10; $i--) {
      echo '<option value="' . $i . '">' . $i . '</option>';  
    }?>
  </select>
  <label for="quoteText">Texte de la citation :</label>
  <textarea name="quoteText" id="quoteText" cols="30" rows="10"></textarea>
  <input type="reset" value="Annuler">
  <input type="submit" value="Enregistrer">
</form>

<a href="index.php">Accueil</a>

<?php

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
    
    $connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $login, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo 'Connection established';

    // search for author in database
    extract($_POST);
    $statement = $connection->prepare('SELECT * FROM author WHERE lastName=? AND firstName=?');
    $statement->execute([$lastName, $firstName]);

    // show result
    $result = $statement->fetchAll();
    // var_dump($result);
    // var_dump($result[0]['id']);

    $authorId = null;
    if (empty($result)) {
      //echo 'Auteur inconnu - ';      

      // add author to database
      $statement = $connection->prepare('INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)');
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
      $statement = $connection->prepare('INSERT INTO quote (text, authorId) VALUES (?, ?)');
      $statement->execute([$quoteText, $authorId]);

      if ($connection->lastInsertId()) {
        echo 'Citation ajoutée à la base de données<br/>';
      }
    }

    // close connection
    $connection = null;
  } catch (Exception $e) {
    echo 'Connection failed: ' . $e->getMessage();
  }
}

include './includes/footer.php';
