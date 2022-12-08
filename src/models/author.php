<?php

namespace Application\Model\Author;

require_once('src/lib/database.php');

use Application\Lib\Database\DatabaseConnection;

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

class AuthorRepository
{
  public DatabaseConnection $connection;

  public function getAuthors(): array
  {
    // get all distinct author names

    $statement = $this->getDb()->prepare('
      SELECT DISTINCT id, lastName, firstName, century
      FROM author ORDER BY lastName
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

    return $authors;
  }

  public function getCenturies(): array
  {
    // get all distinct centuries

    $statement = $this->getDb()->prepare('
      SELECT DISTINCT century FROM author ORDER BY century
    ');
    $statement->execute();

    return $statement->fetchAll();
  }

  public function getAuthorId(array $authorNames): ?int
  {
    // search for author in database

    $statement = $this->getDb()->prepare('
      SELECT * FROM author WHERE lastName=? AND firstName=?
    ');
    $statement->execute($authorNames);

    $result = $statement->fetch();

    $authorId = null;
    if (!empty($result)) {
      $authorId = $result['id'];
    }

    return $authorId;
  }

  public function insertAuthor(array $authorData): int
  {
    // add author to database

    $statement = $this->getDb()->prepare('
      INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)
    ');
    $statement->execute($authorData);

    $lastInsertId = $this->getDb()->lastInsertId();

    if ($lastInsertId) {
      return $lastInsertId;
    } else {
      throw new \Exception("L'ajout de l'auteur à la base de données a échoué.");
    }
  }

  public function getDb(): \PDO
  {
    return $this->connection->getDb();
  }
}
