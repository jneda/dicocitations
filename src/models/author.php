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

class AuthorRepository
{
  public ?PDO $database = null;

  public function getAuthors(): array
  {
    // get all distinct author names

    $this->dbConnect();

    $statement = $this->database->prepare('
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

    $this->dbConnect();

    $statement = $this->database->prepare('
      SELECT DISTINCT century FROM author ORDER BY century
    ');
    $statement->execute();

    return $statement->fetchAll();
  }

  public function getAuthorId(array $authorNames): ?int
  {
    // search for author in database

    $this->dbConnect();

    $statement = $this->database->prepare('
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

    $this->dbConnect();

    $statement = $this->database->prepare('
      INSERT INTO author (lastName, firstName, century) VALUES (?, ?, ?)
    ');
    $statement->execute($authorData);

    $lastInsertId = $this->database->lastInsertId();

    if ($lastInsertId) {
      return $lastInsertId;
    } else {
      throw new Exception("L'ajout de l'auteur à la base de données a échoué.");
    }
  }

  public function dbConnect(): void
  {
    // establish connection
    if ($this->database === null) {
      // get database config
      $dbConfigFile = file_get_contents('./config/config.json');
      $dbConfig = json_decode($dbConfigFile);

      extract(get_object_vars($dbConfig->database));

      $this->database = new PDO(
        'mysql:host=' . $host . ';dbname=' . $dbname,
        $login,
        $password
      );
      $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
  }
}
