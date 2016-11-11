<?php

include 'config/database.php';
session_start();

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reciever = stripslashes(htmlspecialchars($_GET['reciever']));
    $sender = stripslashes(htmlspecialchars($_SESSION['logged_on_user']));

    $result = $conn->prepare('SELECT * FROM chat WHERE (sender = (?)  AND reciever = (?)) OR (sender = (?)  AND reciever = (?))');
    $result->execute([$sender, $reciever, $reciever, $sender]);
    while ($r = $result->fetch(PDO::FETCH_BOTH)) {
        echo $r[1] . '\\' . $r[2] . '\\' . $r[3] . "\n";
    }

} catch (PDOException $e) {
    echo $e;
}
