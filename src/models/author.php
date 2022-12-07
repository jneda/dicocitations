<?php

require_once('src/model.php');

class Author
{
  public int $id;
  public string $lastName;
  public string $firstName;
  public int $century;

  public function getFullName(): string
  {
    return $this->lastName . ' ' . $this->firstName;
  }
}

function getAuthors(): array
{
  // get all distinct author names

  $connection = getConnection();

  $statement = $connection->prepare('
    SELECT DISTINCT id, lastName, firstName, century FROM author ORDER BY lastName
  ');
  $statement->execute();

  $authors = [];
  while ($row = $statement->fetch()) {
    $author = new Author();

    $author->id = $row['id'];
    $author->lastName = $row['lastName'];
    $author->firstName = $row['firstName'];
    $author->century = $row['century'];

    $authors[] = $author;
  }

  $connection = null;

  return $authors;
}

function getCenturies(): array
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

function getAuthorId(array $authorNames): ?int
{
  // search for author in database

  $connection = getConnection();

  $statement = $connection->prepare('
    SELECT * FROM author WHERE lastName=? AND firstName=?
  ');
  $statement->execute($authorNames);

  $result = $statement->fetch();

  $connection = null;

  $authorId = null;
  if (!empty($result)) {
    $authorId = $result['id'];
  }

  return $authorId;
}

function insertAuthor(array $authorData): int
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
    return $lastInsertId;
  } else {
    throw new Exception("L'ajout de l'auteur à la base de données a échoué.");
  }
}
