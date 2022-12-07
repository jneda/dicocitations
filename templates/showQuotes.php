<?php

$title = 'Dictionnaire de citations | Résultats';

ob_start();

if (count($quotes) === 0) {
  require('templates/noQuotesFound.html');
} else {
  require('templates/quoteTable.php');
}

?>

<nav>
  <div class="list-group">
    <a href="/" class="list-group-item list-group-item-action">Accueil</a>
    <a href="/?action=addQuote" class="list-group-item list-group-item-action">Ajouter une citation</a>
  </div>
</nav>


<?php

$content = ob_get_clean();
require 'layout.php';
