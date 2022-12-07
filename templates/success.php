<?php

$title = 'Succès !';

ob_start();

?>

<div class="container border rounded my-5 p-3">
  <p><?= $successMessage ?></p>
</div>

<?php

$content = ob_get_clean();
require('templates/layout.php');