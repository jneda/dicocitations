<?php

require_once('src/lib/database.php');
require_once('src/models/author.php');
require_once('src/models/quote.php');

function addQuote()
{
  require('templates/addQuote.php');
  
  if (isset($_POST) && !empty($_POST)) {
    extract($_POST);

    $authorRepository = new AuthorRepository();
    $authorRepository->connection = new DatabaseConnection();

    $authorId = $authorRepository->getAuthorId([$lastName, $firstName]);
    if ($authorId === null) {
      $authorId = $authorRepository->insertAuthor(
        [$lastName, $firstName, $century]
      );
    }

    $quoteRepository = new QuoteRepository();
    $quoteRepository->connection = new DatabaseConnection();

    if ($quoteRepository->quoteExists($quoteText)) {
      throw new Exception('Cette citation existe déjà.');
    } else {
      $ok = $quoteRepository->insertQuote($quoteText, $authorId);
      if ($ok) {
        $successMessage = 'Citation ajoutée à la base de données';
        require('templates/success.php');
      } else {
        $failureMessage = 'La citation n\'a pas pu être ajoutée à la base de données';
        require('templates/failure.php');
      }
    }
  }
}
