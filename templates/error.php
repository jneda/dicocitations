<?php

$title = 'Erreur !';

ob_start();

?>

<div class="container border rounded my-5 p-3 alert alert-danger">
  <p><?= $errorMessage ?></p>
</div>

<?php

$content = ob_get_clean();
require('templates/layout.php');