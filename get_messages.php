<?php

include 'config/database.php';

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $reciever = stripslashes(htmlspecialchars($_POST['reciever']));
    $sender = stripslashes(htmlspecialchars($_POST['sender']));

    $sender = "admin";
    $reciever = "zxcv";

    $result = $db->prepare('SELECT * FROM chat WHERE (sender = ? OR reciever = ?) AND (sender = ? OR reciever = ?)');
    $result->execute([$sender, $reciever, $sender, $reciever]);
    $result = $result->get_result();
    while ($r = $result->fetch_row()) {
        echo $r[1];
        echo '\\';
        echo $r[2];
        echo "\n";
    }

} catch (PDOException $e) {
    echo $e;
}
