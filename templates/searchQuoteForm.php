<div class="container border rounded my-5 p-3">
  <form action="affichecit.php" method="POST">
    <div class="mb-3">
      <label for="q" class="form-label">Rechercher :</label>
      <input type="search" name="q" id="q" class="form-control">
    </div>
    <div class="row">
      <div class="mb-3 col">
        <label for="author" class="form-label">Auteur :</label>
        <select name="author" id="author" class="form-select">
          <option selected value></option>
          <?php foreach ($authors as $author) { ?>
            <option value="<?= $author['id'] ?>">
              <?= $author['lastName'] . ' ' . $author['firstName'] ?>
            </option>
          <?php } ?>
        </select>
      </div>
      <div class="mb-3 col">
        <label for="century" class="form-label">Siècle :</label>
        <select name="century" id="century" class="form-select">
          <option selected value></option>
          <?php foreach ($centuries as $century) { ?>
            <option value="<?= $century[0] ?>">
              <?= $century[0] ?>
            </option>
          <?php } ?>
        </select>
      </div>
    </div>
    <div class="row">
      <div class="mb-3 form-check col ms-2">
        <label for="author" class=" form-check-label">Trier par auteur</label>
        <input type="radio" name="sortBy" value="author" class="form-check-input">
      </div>
      <div class="mb-3 form-check col ms-2">
        <label for="century" class=" form-check-label">Trier par siècle</label>
        <input type="radio" name="sortBy" value="century" class="form-check-input">
      </div>
    </div>
    <input type="submit" value="Rechercher" class="btn btn-primary mb-3">
  </form>
</div>
<div class="container">
  <nav>
    <div class="list-group">
      <a href="saisiecit.php" class="list-group-item list-group-item-action">Ajouter une citation</a>
          </div>
  </nav>
</div>