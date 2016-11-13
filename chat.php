<?php

include 'config/database.php';
session_start();

$chat_with = $_POST['chat_with'];
$login = $_SESSION['logged_on_user'];
$stat = 0;

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, who_liked FROM public');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (strpos($result['username'], $login) !== false && strpos($result['who_liked'], $chat_with) !== false) {
            $stat = 1;
            break;
        }
    }

    $sql = $conn->prepare('SELECT username, who_liked FROM public');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if (strpos($result['username'], $chat_with) !== false && strpos($result['who_liked'], $login) !== false && $stat == 1) {
            $response = array('status' => true);
            die(json_encode($response));
        }
    }

    $response = array('status' => false, 'statusMsg' => '<p class="info">Sorry, '.$chat_with.' has not liked you back yet.<br>We will send you a notification when he/she does!</p>');
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
