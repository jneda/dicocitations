<?php

require_once('src/model.php');

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
    throw new Exception("L'ajout de l'auteur à la base de données a échoué.");
  }
}
