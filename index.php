<!DOCTYPE html>
<html lang="pl">
<?php session_start();?>
<head>
  <title>Sen o Potędze</title>
  <meta charset="utf-8">
  <link rel="stylesheet" href="/css/master.css">
  <meta name="generator" content="Zer0 Forum v. 0.1" />
  <script src="https://cdn.jsdelivr.net/combine/npm/tail.writer@0.4.1/langs/tail.writer-pl.min.js,npm/tail.writer@0.4.1/js/tail.writer.min.js,npm/tail.writer@0.4.1/js/tail.writer-markdown.min.js,npm/page.js@4.13.3/page.min.js,npm/uglipop@1.0.0" charset="utf-8"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tail.writer@0.4.1/css/tail.writer-white.min.css">
</head>
<body>
  <main>
    <div class="main">
      <header>
      <?php  if (!isset($_SESSION['user'])) { ?>
        <form action="controllers/login.php" id="login" method="post">
          <input type="text" name="username" placeholder="pseudonim" style="display:none">
          <input type="email" name="email" placeholder="email">
          <input type="password" name="password" placeholder="password">
          <input type="submit">
          <input type="button" value="Zarejestruj się" onclick="register();">
        </form>
      <?php } else {
    echo "Witaj, ".$_SESSION['user'];
    //echo '    <input type="button" onclick="enterAlliance()" value="Dołącz do sojuszu">    ';
    //echo '    <input type="button" onclick="newAlliance()" value="Stwórz nowy sojusz">';
    echo '<a style="float:right" href="controllers/logout.php">Wyloguj się</a>';
} ?>
      </header>
      <section id="main"><noscript>Włącz obsługę javascriptu</noscript></section>
    </div>
    <div class="aside">

      <aside>
    </aside>
    </div>
  </main>
  <script src="/js/router.js" charset="utf-8"></script>
</body>

</html>
