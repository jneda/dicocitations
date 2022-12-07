<?php

require_once('src/lib/database.php');
require_once('src/model.php');
require_once('src/models/quote.php');

function showQuotes()
{
  $sql = buildQuery($_POST);
  $quoteRepository = new QuoteRepository();
  $quoteRepository->connection = new DatabaseConnection();
  $quotes = $quoteRepository->getQuotes($sql);

  require('templates/showQuotes.php');
}
