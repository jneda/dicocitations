<?php

class DatabaseConnection
{
  public ?PDO $database = null;
  public ?array $config = null;

  private function init(): void
  {
    // get database config
    $dbConfigFile = file_get_contents('config/config.json');
    $dbConfig = json_decode($dbConfigFile);

    $this->config = get_object_vars($dbConfig->database);
  }

  public function getDb(): PDO
  {
    if ($this->config === null) {
      $this->init();
    }

    if ($this->database === null) {
      extract($this->config);
      $this->database = new PDO(
        'mysql:host=' . $host . ';dbname=' . $dbname,
        $login,
        $password
      );
      $this->database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    return $this->database;
  }
}
