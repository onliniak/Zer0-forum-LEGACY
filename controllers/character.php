<?php
require_once 'db.php';
require_once __DIR__ . '/../markdown.php';

$statement = $db->prepare('INSERT INTO Characters (PostID, Name, Owner)
    VALUES (:id, :name, :owner)');

$statement->execute([
    'id'    => $_POST['ID'],
    'name'  => $md->render($md->render($_POST['imie'])),
    'owner' => $md->render($_POST['owner']),
]);

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
