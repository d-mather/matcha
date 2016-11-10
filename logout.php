<?php

include 'config/database.php';
session_start();

$login = $_SESSION['logged_on_user'];

date_default_timezone_set('Africa/Johannesburg');
$date = date('d/m/Y');
$time = date('h:i:sa');
$active = $date . " " . $time;

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = $conn->prepare('SELECT username, active FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login) {
            $sql = $conn->prepare('UPDATE `users` SET active=? WHERE username=?');
            $sql->execute([$active, $login]);
            break;
        }
    }

} catch (PDOException $e) {
    file_put_contents("logout_error", $e);
}

$_SESSION['logged_on_user'] = '';
$_SESSION['email'] = '';
$_SESSION['first_name'] = '';
$_SESSION['last_name'] = '';
$_SESSION['pro_pic'] = '';

header('LOCATION: index.php');
