<?php
session_start();
require_once 'db.php';
require_once __DIR__ . '/../markdown.php';

$statement = $db->prepare('INSERT INTO Alliance (Name, Description, Kingdom)
    VALUES (:name, :body, :citizien)');

    $statement->execute([
        'name'        => $md->render($_POST['name']),
        'citizien'    => $md->render($_POST['citizien']),
        'body'   => $md->render($_POST['body']),
    ]);

    $statement = $db->prepare('INSERT INTO Categories (Name, Opis, Type, Node)
        VALUES (:name1, :opis1, :type, :node1)');

    $statement->execute([
        'name1'    => $md->render($_POST['name']),
        'opis1'  => $md->render($_POST['body']),
        'type' => "Alliance",
        'node1' => "/category/Pałac/". $_POST['name'] ,
    ]);

    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die;
