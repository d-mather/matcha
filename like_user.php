<?php

include 'config/database.php';
session_start();
$liked = $_POST['liked'];
$user = $_SESSION['logged_on_user'];
$status = $_POST['status'];
$cont = 0;

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = $conn->prepare('SELECT username, likes, who_liked FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $liked) {
            if ($status == 'like') {
                $likes = $result['likes'];
                $likes = $likes + 1;
                $sql = $conn->prepare('UPDATE `public` SET likes=? WHERE username=?');
                $sql->execute([$likes, $liked]);

                $who_liked = $result['who_liked'];
                if ($who_liked == '' || !$who_liked) {
                    $who_liked = $user;
                } else {
                    $who_liked = $who_liked."\n".$user;
                }
                $sql = $conn->prepare('UPDATE `public` SET who_liked=? WHERE username=?');
                $sql->execute([$who_liked, $liked]);

                $cont = 1;
                break;
            } elseif ($status == 'unlike') {
                $likes = $result['likes'];
                $likes = $likes - 1;
                $sql = $conn->prepare('UPDATE `public` SET likes=? WHERE username=?');
                $sql->execute([$likes, $liked]);

                $who_liked = $result['who_liked'];
                if (!strstr($who_liked, PHP_EOL)) {
                    $who_liked = null;
                } else {
                    $who_liked = str_replace($user, '', $who_liked);
                }
                $who_liked = trim($who_liked);
                if ($who_liked == '' || !$who_liked) {
                    $who_liked = null;
                }
                $sql = $conn->prepare('UPDATE `public` SET who_liked=? WHERE username=?');
                $sql->execute([$who_liked, $liked]);

                $notification = '<option value="http://localhost:8080/matcha/view_page_user.php?viewing='.$user.'">'.$user." has just unliked you!</option>\n";
                //$notification = $user.' has just unliked you!';
                $notify = $conn->prepare('INSERT INTO notifications (username, notify, seen, printed) VALUES (?, ?, 0, 0)');
                $notify->execute([$liked, $notification]);

                $response = array('status' => true);
                die(json_encode($response));
            }
        }
    }

    $sql = $conn->prepare('SELECT username, who_liked FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user && $cont == 1) {
            if (strpos($result['who_liked'], $liked) !== false) {
                $notification = '<option value="http://localhost:8080/matcha/view_page_user.php?viewing='.$user.'">'.$user." has just liked you back!</option>\n";
                //$notification = $user.' has just liked you back!';
                $notify = $conn->prepare('INSERT INTO notifications (username, notify, seen, printed) VALUES (?, ?, 0, 0)');
                $notify->execute([$liked, $notification]);
            } else {
                $notification = '<option value="http://localhost:8080/matcha/view_page_user.php?viewing='.$user.'">'.$user." has just liked you!</option>\n";
                //$notification = $user.' has just liked you!';
                $notify = $conn->prepare('INSERT INTO notifications (username, notify, seen, printed) VALUES (?, ?, 0, 0)');
                $notify->execute([$liked, $notification]);
            }
            $response = array('status' => true);
            die(json_encode($response));
        }
    }
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
