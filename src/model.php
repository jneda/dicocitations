<?php

require_once('src/models/author.php');
require_once('src/models/quote.php');

function getIndexData()
{
  $authorRepository = new AuthorRepository();
  $quoteRepository = new QuoteRepository();
  $indexData = [];
  $indexData['randomQuote'] = $quoteRepository->getRandomQuote();
  $indexData['authors'] = $authorRepository->getAuthors();
  $indexData['centuries'] = $authorRepository->getCenturies();

  return $indexData;
}

function buildQuery($postData)
{
  extract($_POST);

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
