<?php
session_start();
require_once 'db.php';

$email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
$stmt = $db->prepare("SELECT Password, Name FROM Users WHERE Email=:email");
$stmt->execute(['email' => $email]);
$password = $stmt->fetch();
$hash = password_verify($_POST['password'], $password["Password"]);

if ($hash) {
    $_SESSION['user'] = $password["Name"];
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    die;
}
