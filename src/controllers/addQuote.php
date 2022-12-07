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
      echo 'Cette citation existe déjà<br/>';
    } else {
      insertQuote($quoteText, $authorId);
    }
  }
}
