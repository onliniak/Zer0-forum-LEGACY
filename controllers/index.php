<!DOCTYPE html>
<html lang="pl">
<?php session_start(); ?>
<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" href="css/master.css">
</head>

<body>
  <main>
    <div class="main">
      <header>
      <?php  if (!isset($_SESSION['user'])) { ?>
        <form action="login.php" id="login" method="post">
          <input type="text" name="username" placeholder="pseudonim" style="display:none">
          <input type="email" name="email" placeholder="email">
          <input type="password" name="password" placeholder="password">
          <input type="submit">
          <input type="button" value="Zarejestruj się" onclick="register();">
        </form>
      <?php } else {
    echo "Witaj, ".$_SESSION['user'];
    echo '<a style="float:right" href="logout.php">Wyloguj się</a>';
} ?>
      </header>
      <section id="nodee"><noscript>Włącz obsługę javascriptu</noscript></section>
    </div>
    <div class="aside">

      <aside>
    </aside>
    <small>Zer0 Forum 0.01 alpha by Luunube</small>
    </div>
  </main>
  <script src="js/main.js" charset="utf-8"></script>
  <script src="js/uglipop.min.js" charset="utf-8"></script>
  <script src="https://cdn.jsdelivr.net/npm/markdown-it@10.0.0/dist/markdown-it.min.js" charset="utf-8"></script>
</body>

</html>
