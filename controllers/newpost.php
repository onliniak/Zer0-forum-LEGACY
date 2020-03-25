<?php
session_start();
require_once 'db.php';
require_once __DIR__ . '/../markdown.php';

$url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_PATH);
$a = "$url";

//$input = file_get_contents("php://input");
//$json = json_decode($input, true);

$statement = $db->prepare('INSERT INTO Categories (Name, Opis, Node)
    VALUES (:name1, :opis1, :node1)');

$statement->execute([
    'name1'    => $md->render($_POST['title']),
    'opis1'  => $md->render($_POST['text']),
    'node1' => $a,
]);

$ID = $db->prepare('SELECT ID FROM Categories WHERE Name= :title2 and Opis= :content2 and Node= :Node2');

$ID->execute([
    'title2'    => $md->render($_POST['title']),
    'content2'  => $md->render($_POST['text']),
    'Node2' => $a,
]);
$number = $ID->fetch();

$post = $db->prepare('INSERT INTO Posts (Title, Author, Content, CategoryID)
    VALUES (:title3, :author3, :content3, :category3)');

    if (!isset($_SESSION['user'])) {
        $author = $_POST['author'];
    } else {
        $author = $_SESSION['user'];
    }

$post->execute([
    'title3'    => $md->render($_POST['title']),
    'author3'   => $md->render($author),
    'content3'  => $md->render($_POST['text']),
    'category3' => $number['ID'],
]);

//print_r($post->errorInfo());

header('Location: ' . $_SERVER['HTTP_REFERER']);
die;
