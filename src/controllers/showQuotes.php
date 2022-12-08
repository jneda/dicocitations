<?php

namespace Application\Controller\ShowQuotes;

require_once('src/lib/database.php');
require_once('src/models/author.php');
require_once('src/models/quote.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\Quote\QuoteRepository;

class ShowQuotes
{
  public function buildQuery(array $postData): string
  {
    extract($postData);

    $sql = 'SELECT text, lastName, firstName, century FROM quote
      INNER JOIN author ON quote.authorId = author.id';

    // handle query search and selects

    $queries = [];

    if ($q !== '') {
      $queries[] = 'text LIKE "%' . $q . '%"';
    }
    if (isset($author) && !empty($author)) {
      $queries[] = 'author.id = ' . $author;
    }
    if (isset($century) && !empty($century)) {
      $queries[] = 'century = ' . $century;
    }

    if (count($queries) > 0) {
      $sql .= ' WHERE ' . implode(' AND ', $queries);
    }

    // assign sort by author as default
    if (!isset($sortBy) || $sortBy === 'author') {
      $sortBy = 'author.lastName, author.firstName';
    }
    $sql .=  ' ORDER BY ' . $sortBy;

    // var_dump($sql);
    return $sql;
  }

  public function execute(): void
  {
    $sql = $this->buildQuery($_POST);
    $quoteRepository = new QuoteRepository();
    $quoteRepository->connection = new DatabaseConnection();
    $quotes = $quoteRepository->getQuotes($sql);

    require('templates/showQuotes.php');
  }
}
