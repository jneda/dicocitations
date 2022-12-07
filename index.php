<?php

require('./src/model.php');

extract(getIndexData());

require('./templates/header.html');
require('./templates/index.php');
require('./templates/footer.html');
