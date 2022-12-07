<?php

require_once('src/model.php');

function showQuotes()
{
  $sql = buildQuery($_POST);
  $quotes = getQuotes($sql);

  require('templates/showQuotes.php');
}
