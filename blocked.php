<?php

include 'config/database.php';
session_start();
$blocked_user = $_POST['blocked'];
$user = $_SESSION['logged_on_user'];
$status = $_POST['status'];
try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, blocked, who_blocked FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user) {
            if ($status == 'block') {
                $blocked = $result['blocked'];
                if ($blocked == '' || !$blocked) {
                    $blocked = $blocked_user;
                } else {
                    $blocked = $blocked."\n".$blocked_user;
                }
                $sql = $conn->prepare('UPDATE `public` SET blocked=? WHERE username=?');
                $sql->execute([$blocked, $user]);

                $sql = $conn->prepare('UPDATE `public` SET who_blocked=? WHERE username=?');
                $sql->execute([$user, $blocked]);

                $response = array('status' => true);
                die(json_encode($response));
            } elseif ($status == 'unblock') {
                $blocked = $result['blocked'];
                if (strspn($blocked, "\n") <= 0) {
                    $blocked = str_replace($blocked_user, '', $blocked);
                } else {
                    $blocked_user = $blocked_user."\n";
                    $blocked = str_replace($blocked_user, '', $blocked);
                }
                $sql = $conn->prepare('UPDATE `public` SET blocked=? WHERE username=?');
                $sql->execute([$blocked, $user]);

                $who_blocked = $result['who_blocked'];
                if (strspn($who_blocked, "\n") <= 0) {
                    $who_blocked = str_replace($blocked_user, '', $who_blocked);
                } else {
                    $who_blocked = str_replace($blocked_user, '', $who_blocked);
                }
                $sql = $conn->prepare('UPDATE `public` SET who_blocked=? WHERE username=?');
                $sql->execute([$who_blocked, $blocked_user]);

                $response = array('status' => true);
                die(json_encode($response));
            }
        }
    }
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
