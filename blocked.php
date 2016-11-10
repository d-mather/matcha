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
                $sql1 = $conn->prepare('UPDATE `public` SET blocked=? WHERE username=?');
                $sql1->execute([$blocked, $user]);
            } elseif ($status == 'unblock') {
                $blocked = $result['blocked'];
                if (!strstr($blocked, PHP_EOL)) {
                    $blocked = null;
                } else {
                    $blocked = str_replace($blocked_user, '', $blocked);
                }
                $blocked = trim($blocked);
                if ($blocked == '' || !$blocked) {
                    $blocked = null;
                }
                $sql1 = $conn->prepare('UPDATE `public` SET blocked=? WHERE username=?');
                $sql1->execute([$blocked, $user]);
            }
        }
        if ($result['username'] == $blocked_user) {
            $who_blocked = $result['who_blocked'];
            if ($status == 'block') {
                if ($who_blocked == '' || !$who_blocked) {
                    $who_blocked = $user;
                } else {
                    $who_blocked = $who_blocked."\n".$user;
                }
                $sql1 = $conn->prepare('UPDATE `public` SET who_blocked=? WHERE username=?');
                $sql1->execute([$who_blocked, $blocked_user]);
            } elseif ($status == 'unblock') {
                if (!strstr($who_blocked, PHP_EOL)) {
                    $who_blocked = null;
                } else {
                    $who_blocked = str_replace($user, '', $who_blocked);
                }
                $who_blocked = trim($who_blocked);
                if ($who_blocked == '' || !$who_blocked) {
                    $who_blocked = null;
                }
                $sql1 = $conn->prepare('UPDATE `public` SET who_blocked=? WHERE username=?');
                $sql1->execute([$who_blocked, $blocked_user]);
            }
        }
    }

    $chat_stat = '';
    $sql2 = $conn->prepare('SELECT username, who_liked, who_blocked, blocked FROM `public`');
    $sql2->execute();
    while ($result = $sql2->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $blocked_user) {
            if (strpos($result['who_liked'], $user) !== false && $status == 'unblock') {
                $chat_stat = 1;
            } else {
                $chat_stat = 0;
            }
        }
        if ($result['username'] == $user) {
            if ($result['who_blocked'] != null && !strstr($result['blocked'], $user)) {
                $chat_stat = 2;
                break;
            }
        }
    }

    $response = array('status' => true, 'chat_stat' => $chat_stat);
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
