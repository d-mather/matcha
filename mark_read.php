<?php

include 'config/database.php';
session_start();

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
        }
    }
    $response = array('status' => true);
    die(json_encode($response));

} catch (PDOException $e) {
  $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an ERROR: ' . $e . '</p>');
  die(json_encode($response));
}

?>
