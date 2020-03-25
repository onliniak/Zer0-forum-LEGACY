<?php
session_start();
require_once 'db.php';

$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$password = password_hash($_POST['password'], PASSWORD_ARGON2ID);
$username = filter_var($_POST['username'], FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW);

$statement = $db->prepare('INSERT INTO Users (Name, Email, Password)
    VALUES (:name, :email, :pass)');

$statement->execute([
    'name' => $username,
    'email' => $email,
    'pass' => $password,
]);

$_SESSION['user'] = $username;

header('Location: ' . $_SERVER['HTTP_REFERER']);
die;
