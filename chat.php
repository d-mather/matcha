<?php

include 'config/database.php';
session_start();

$chat_with = $_POST['chat_with'];
$login = $_SESSION['logged_on_user'];

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('show tables like "%+%"');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['Tables_in_matcha (%+%)']) {
            $table_name = $result['Tables_in_matcha (%+%)'];
            echo $table_name.'<br>';
            $user_names = explode('+', $table_name);
            if (in_array($chat_with, $user_names) && in_array($login, $user_names)) {
                $table_exists = true;
                break;
            }
        }
    }

    $response = array('status' => true, 'target_table' => $target_table);
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
