<?php
require_once 'db.php';
require_once __DIR__ . '/../markdown.php';

$statement = $db->prepare('INSERT INTO CharactersList (Nazwa, imie, nazwisko, wiek, plec, wyglad, charakter, umiejetnosci, inne)
    VALUES (:id, :imie, :nazwisko, :wiek, :plec, :wyglad, :charakter, :umiejetnosci, :inne)');

$statement->execute([
    'id'    => $md->render($_POST['ID']),
    'imie'  => $md->render($_POST['imie']),
    'nazwisko' => $md->render($_POST['nazwisko']),
    'wiek' => $_POST['wiek'],
    'plec' => $_POST['plec'],
    'wyglad' => $md->render($_POST['wyglad']),
    'charakter' => $md->render($_POST['charakter']),
    'umiejetnosci' => $md->render($_POST['umiejetnosci']),
    'inne' => $md->render($_POST['inne'])
]);

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
