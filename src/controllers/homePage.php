<?php

namespace Application\Controller\HomePage;

require_once('src/lib/database.php');
require_once('src/models/author.php');
require_once('src/models/quote.php');

use Application\Lib\Database\DatabaseConnection;
use Application\Model\Author\AuthorRepository;
use Application\Model\Quote\QuoteRepository;

function getIndexData()
{
  $authorRepository = new AuthorRepository();
  $authorRepository->connection = new DatabaseConnection();
  $quoteRepository = new QuoteRepository();
  $quoteRepository->connection = new DatabaseConnection();

  $indexData = [];
  $indexData['randomQuote'] = $quoteRepository->getRandomQuote();
  $indexData['authors'] = $authorRepository->getAuthors();
  $indexData['centuries'] = $authorRepository->getCenturies();

  return $indexData;
}

function homePage()
{
  extract(getIndexData());

  require('templates/index.php');
}
