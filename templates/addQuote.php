<?php

$title = 'Dictionnaire de citations | Ajouter une citation';
ob_start();

?>

<div class="container border rounded my-5 p-3">
  <form action="saisiecit.php" method="POST">
    <div class="mb-3">
      <label for="lastName" class="form-label">Nom de l'auteur :</label>
      <input type="text" name="lastName" id="lastName" class="form-control">
    </div>
    <div class="mb-3">
      <label for="firstName" class="form-label">Prénom de l'auteur :</label>
      <input type="text" name="firstName" id="firstName" class="form-control">
    </div>
    <div class="mb-3">
      <label for="century" class="form-label">Siècle :</label>
      <select name="century" id="century" class="form-select" required>
        <option disabled selected value></option>
        <?php for ($i = 21; $i >= -10; $i--) {
          echo '<option value="' . $i . '">' . $i . '</option>';
        } ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="quoteText" class="form-label">Texte de la citation :</label>
      <textarea name="quoteText" id="quoteText" cols="30" rows="10" class="form-control"></textarea>
    </div>
    <div class="mb-3">
      <input type="reset" value="Annuler" class="btn">
      <input type="submit" value="Enregistrer" class="btn btn-primary">
    </div>
  </form>
</div>

<nav>
  <div class="list-group">
    <a href="index.php" class="list-group-item list-group-item-action">Accueil</a>
  </div>
</nav>

<?php

$content = ob_get_clean();
require('layout.php');
