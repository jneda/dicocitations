<?php

require_once('src/controllers/homePage.php');
require_once('src/controllers/showQuotes.php');
require_once('src/controllers/addQuote.php');

use function Application\Controller\AddQuote\addQuote;
use function Application\Controller\HomePage\homePage;
use function Application\Controller\ShowQuotes\showQuotes;

try {
  if (empty($_GET)) {
    homePage();
  } else {
    if ($_GET['action'] === 'showQuotes') {
      showQuotes();
    } else if ($_GET['action'] === 'addQuote') {
      addQuote();
    }
  }
} catch (Exception $e) {
  $errorMessage = $e->getMessage();
  require('templates/error.php');
}
