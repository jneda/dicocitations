<?php

require_once('src/model.php');

function addQuote()
{
  require('templates/addQuote.php');

  if (isset($_POST) && !empty($_POST)) {
    extract($_POST);

    $authorId = getAuthorId([$lastName, $firstName]);
    if ($authorId === null) {
      $authorId = insertAuthor([$lastName, $firstName, $century]);
    }

    if (quoteExists($quoteText)) {
      throw new Exception('Cette citation existe déjà.');
    } else {
      $ok = insertQuote($quoteText, $authorId);
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
