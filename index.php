<?php

require_once('src/controllers/homePage.php');
require_once('src/controllers/showQuotes.php');
require_once('src/controllers/addQuote.php');

use Application\Controller\AddQuote\AddQuote;
use Application\Controller\HomePage\HomePage;
use Application\Controller\ShowQuotes\ShowQuotes;

try {
  if (empty($_GET)) {
    (new HomePage())->execute();
  } else {
    if ($_GET['action'] === 'showQuotes') {
      (new ShowQuotes())->execute();
    } else if ($_GET['action'] === 'addQuote') {
      (new AddQuote())->execute();
    }
  }
} catch (Exception $e) {
  $errorMessage = $e->getMessage();
  require('templates/error.php');
}
