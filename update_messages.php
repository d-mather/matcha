<?php

include 'config/database.php';

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reciever = stripslashes(htmlspecialchars($_POST['reciever']));
    $sender = stripslashes(htmlspecialchars($_POST['sender']));
    $message = stripslashes(htmlspecialchars($_POST['message']));
    if ($message == '' || $sender == '' || $reciever == '') {
        die();
    }

    $result = $db->prepare("INSERT INTO chat (sender, reciever, message) VALUES (?, ?, ?)");
    $result->execute([$sender, $reciever, $message]);

} catch (PDOException $e) {
    echo $e;
}
