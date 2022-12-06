<div class="container">
  <form action="affichecit.php" method="POST">
    <div class="mb-3">
      <label for="q" class="form-label">Rechercher :</label>
      <input type="search" name="q" id="q" class="form-control">
    </div>
    <div class="mb-3">
      <label for="author" class="form-label">Auteur :</label>
      <select name="author" id="author">
        <option disabled selected value></option>
        <?php foreach ($authors as $author) { ?>
          <option value="<?= $author['id'] ?>">
            <?= $author['lastName'] . ' ' . $author['firstName'] ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-3">
      <label for="century" class="form-label">Siècle :</label>
      <select name="century" id="century">
        <option disabled selected value></option>
        <?php foreach ($centuries as $century) { ?>
          <option value="<?= $century[0] ?>">
            <?= $century[0] ?>
          </option>
        <?php } ?>
      </select>
    </div>
    <div class="mb-3 form-check">
      <label for="author" class=" form-check-label">Trier par auteur</label>
      <input type="radio" name="sortBy" value="author" class="form-check-input">
    </div>
    <div class="mb-3 form-check">
      <label for="century" class=" form-check-label">Trier par siècle</label>
      <input type="radio" name="sortBy" value="century" class="form-check-input">
    </div>
    <input type="submit" value="Rechercher">
  </form>
</div>
<div class="container">
  <nav>
    <ul>
      <li><a href="saisiecit.php">Ajouter une citation</a></li>
    </ul>
  </nav>
</div>