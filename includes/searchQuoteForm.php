<form action="affichecit.php" method="POST">
  <label for="q">Rechercher :</label>
  <input type="search" name="q" id="q">
  <label for="author">Auteur :</label>
  <select name="author" id="author">
    <option disabled selected value></option>
    <?php foreach ($authors as $author) { ?>
      <option value="<?= $author['id'] ?>">
        <?= $author['lastName'] . ' ' . $author['firstName'] ?>
      </option>
    <?php } ?>
  </select>
  <label for="century">Siècle :</label>
  <select name="century" id="century">
    <option disabled selected value></option>
    <?php foreach ($centuries as $century) { ?>
      <option value="<?= $century[0] ?>">
        <?= $century[0] ?>
      </option>
    <?php } ?>
  </select>
  <label for="author">Trier par auteur</label>
  <input type="radio" name="sortBy" value="author">
  <label for="century">Trier par siècle</label>
  <input type="radio" name="sortBy" value="century">
  <input type="submit" value="Rechercher">
</form>

<a href="saisiecit.php">Ajouter une citation</a>