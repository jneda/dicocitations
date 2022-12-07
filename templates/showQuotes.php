<?php

if (count($quotes) === 0) {
  require('./templates/noQuotesFound.html');
} else {
  require('./templates/quoteTable.php');
}

?>

<nav>
  <div class="list-group">
    <a href="index.php" class="list-group-item list-group-item-action">Accueil</a>
    <a href="addQuote.php" class="list-group-item list-group-item-action">Ajouter une citation</a>
  </div>
</nav>


<?php
require './templates/footer.html';
