<?php

include 'config/database.php';
session_start();

header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

$login = $_SESSION['logged_on_user'];

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, notify, seen FROM `notifications`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login && $result['seen'] == 0) {
            $update = $conn->prepare('UPDATE `notifications` SET seen=1 WHERE username=?');
            $update->execute([$login]);
            $text = $result['notify'];
            echo 'data: '.$text."\n\n";
            flush();
        }
    }
} catch (PDOException $e) {
    file_put_contents('notify_error', $e);
}
