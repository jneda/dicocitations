<table>
  <thead>
    <th>Citation</th>
    <th>Auteur</th>
    <th>Siècle</th>
  </thead>
  <?php foreach($quotes as $quote) { ?>
    <tr>
      <td><?= $quote['text'] ?></td>
      <td><?= $quote['lastName'] . ' ' . $quote['firstName'] ?></td>
      <td><?= $quote['century']?></td>
    </tr>
  <?php } ?>
</table>