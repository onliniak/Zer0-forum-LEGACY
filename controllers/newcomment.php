<?php
session_start();
require_once 'db.php';
require_once __DIR__ . '/../markdown.php';

$url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH);
$a = "$url";

//$input = file_get_contents("php://input");
//$json = json_decode($input, true);

$statement = $db->prepare('INSERT INTO Comments (PostID, Author, Content)
    VALUES (:id, :author, :content)');

    if (!isset($_SESSION['user'])) {
        $author = $_POST['author'];
    } else {
        $author = $_SESSION['user'];
    }

$statement->execute([
    'id'        => substr($a, -1),
    'author'    => $md->render($author),
    'content'   => $md->render($_POST['text']),
]);

header('Location: ' . $_SERVER['HTTP_REFERER']);
die;
