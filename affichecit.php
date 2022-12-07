<?php

require('./src/model.php');

$sql = buildQuery($_POST);
$quotes = getQuotes($sql);

require('./templates/header.html');
require('./templates/afficheCit.php');
