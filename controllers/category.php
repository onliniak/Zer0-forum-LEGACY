<?php
require_once 'db.php';
$url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH);
$a = "$url";
$stmt = $db->prepare("SELECT * FROM Categories WHERE Node=:node");
$stmt->execute(['node' => $a]);

while ($node = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo '<article id="firstpost" class="category">'; ?>
    <a onclick="needReload()" href="/post/<?= $node["ID"] ?>"><?= $node["Name"] ?></a>
    <?php
    #echo $node["Opis"];
    echo '</article>';
}
