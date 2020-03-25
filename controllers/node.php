<?php
require_once 'db.php';
$result = $db->query("SELECT * FROM Categories WHERE Type = 'Main' ");
?>

  <?php
  while ($node = $result->fetch(PDO::FETCH_ASSOC)) {
      echo '<article id="firstpost" class="node">'; ?>
        <a onclick="needReload()" href="category/<?= $node["Name"] ?>"><?= $node["Name"] ?></a>
      <?php
      echo '<br />';
      echo $node["Opis"];
      echo '<br />';
      echo '<br />';
      echo '</article>';
  }
   ?>
