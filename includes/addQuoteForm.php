<form action="saisiecit.php" method="POST">
  <label for="lastName">Nom de l'auteur :</label>
  <input type="text" name="lastName" id="lastName">
  <label for="firstName">Prénom de l'auteur :</label>
  <input type="text" name="firstName" id="firstName">
  <label for="century">Siècle :</label>
  <select name="century" id="century">
    <?php for ($i = 21; $i >= -10; $i--) {
      echo '<option value="' . $i . '">' . $i . '</option>';
    } ?>
  </select>
  <label for="quoteText">Texte de la citation :</label>
  <textarea name="quoteText" id="quoteText" cols="30" rows="10"></textarea>
  <input type="reset" value="Annuler">
  <input type="submit" value="Enregistrer">
</form>

<a href="index.php">Accueil</a>