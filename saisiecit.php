<?php

require('./src/model.php');

require('./templates/header.html');
require('./templates/addQuoteForm.php');
require('./templates/footer.html');

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
