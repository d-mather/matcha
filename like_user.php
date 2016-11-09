<?php

include 'config/database.php';
session_start();
$liked = $_POST['liked'];
$user = $_SESSION['logged_on_user'];
$status = $_POST['status'];

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

                $response = array('status' => true);
                die(json_encode($response));
            } elseif ($status == 'unlike') {
                $likes = $result['likes'];
                $likes = $likes - 1;
                $sql = $conn->prepare('UPDATE `public` SET likes=? WHERE username=?');
                $sql->execute([$likes, $liked]);

                $who_liked = $result['who_liked'];
                if (!strstr($who_liked, PHP_EOL)) {
                    $who_liked = NULL;
                } else {
                    $who_liked = str_replace($user, '', $who_liked);
                }
                $who_liked = trim($who_liked);
                if ($who_liked == '' || !$who_liked) {
                    $who_liked = NULL;
                }
                $sql = $conn->prepare('UPDATE `public` SET who_liked=? WHERE username=?');
                $sql->execute([$who_liked, $liked]);

                $response = array('status' => true);
                die(json_encode($response));
            }
        }
    }

} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
