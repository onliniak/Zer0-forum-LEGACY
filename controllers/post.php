<?php
require_once 'db.php';
$url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH);
$a = "$url";
$stmt = $db->prepare('SELECT * FROM Posts WHERE CategoryID =:post ');
$stmt->execute(['post' => substr($a, -1)]);
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $stmt23 = $db->prepare('SELECT * FROM Comments WHERE PostID =:post23 ');
    $stmt23->execute(['post23' => substr($a, -1)]);
    $sth = $db->prepare('SELECT * FROM Characters WHERE Owner LIKE :ID ');
    $sth->execute(['ID' => '%%'.$row['Author'].'%%']);
    $a = $sth->fetch() ?>
    <article id="firstpost" class="post">
    <h1 id="postTitle"><?= $row['Title']?></h1>

<?php if ($a['Owner'] === $row['Author']) {
        ?>
  <details>
    <summary>Zobacz kartę postaci</summary>
    <?php var_dump($a); ?>
  </details>
  <?php
    } ?>
    <span id="author">Rozpoczęte przez <?= $row['Author'] ?></span>
    <br />
    <br />
    <?= $row['Content'] ?>
        </article>
    <?php
    while ($comment = $stmt23->fetch(PDO::FETCH_ASSOC)) {
        $sth2 = $db->prepare('SELECT * FROM Characters WHERE Owner LIKE :ID ');
        $sth2->execute(['ID' => '%%'.$comment['Author'].'%%']);
        $a2 = $sth2->fetch()
      ?>
      <article class="comment">
        <?php if ($a2['Owner'] === $comment['Author']) {
          ?>
          <details>
            <summary>Zobacz kartę postaci</summary>
            <?php var_dump($a2); ?>
          </details>
          <?php
      } ?>
        <span>Odpowiada <?= $comment['Author'] ?></span>
        <?= $comment['Content'] ?>
        </article>
      <?php
    }
}
