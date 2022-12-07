<?php

require_once('src/model.php');
require_once('src/lib/database.php');

class Quote
{
  public string $text;
  public string $authorName;
  public int $century;
}

class QuoteRepository
{
  public DatabaseConnection $connection;

  public function getQuotes(string $sql): array
  {
    $statement = $this->getDb()->prepare($sql);
    $statement->execute();

    $quotes = [];
    while ($row = $statement->fetch()) {
      $quote = new Quote();
      $quote->text = $row['text'];
      $quote->authorName = $row['lastName']
        . ' ' . $row['firstName'];
      $quote->century = $row['century'];
      $quotes[] = $quote;
    }

    return $quotes;
  }

  public function getRandomQuote(): Quote
  {
    // get a random quote

    $sql = '
      SELECT * FROM quote
      INNER JOIN author ON author.id = quote.authorId
    ';

    $quotes = $this->getQuotes($sql);

    return $quotes[rand(0, count($quotes) - 1)];
  }

  public function quoteExists(string $quoteText): bool
  {
    // search for quote in database

    $statement = $this->getDb()->prepare('SELECT * FROM quote WHERE text=?');
    $statement->execute([$quoteText]);
    $result = $statement->fetchAll();

    return !empty($result);
  }

  public function insertQuote(string $quoteText, int $authorId): bool
  {
    // add quote to database

    if ($quoteText === '') {
      throw new Exception('Texte de la citation manquant.');
    }

    $statement = $this->getDb()->prepare('
    INSERT INTO quote (text, authorId) VALUES (?, ?)
  ');
    $statement->execute([$quoteText, $authorId]);

    if ($this->getDb()->lastInsertId()) {
      $ok = true;
    } else {
      $ok = false;
    }

    return $ok;
  }

  public function getDb(): PDO
  {
    return $this->connection->getDb();
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
