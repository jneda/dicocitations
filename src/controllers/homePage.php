<?php

require_once('src/model.php');

function homePage()
{
  extract(getIndexData());

  require('templates/index.php');
}
