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

    $sql = $conn->prepare('SELECT username, likes, who_liked, connected FROM `public`');
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

                $cont = 2;
                break;
            }
        }
    }

    if ($cont == 2) {
        $sql = $conn->prepare('SELECT username, connected FROM `public`');
        $sql->execute();
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
            if ($result['username'] == $user) {
                $connected_user = $result['connected'];

                if (!strstr($connected_user, PHP_EOL)) {
                    $connected_user = null;
                } else {
                    $connected_user = str_replace($liked, '', $connected_user);
                }

                $connected_user = trim($connected_user);
                if ($connected_user == '' || !$connected_user) {
                    $connected_user = null;
                }

                $sql = $conn->prepare('UPDATE `public` SET connected=? WHERE username=?');
                $sql->execute([$connected_user, $user]);
                break;
            }
        }

        $sql = $conn->prepare('SELECT username, connected FROM `public`');
        $sql->execute();
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
            if ($result['username'] == $liked) {
                $connected_liked = $result['connected'];

                if (!strstr($connected_liked, PHP_EOL)) {
                    $connected_liked = null;
                } else {
                    $connected_liked = str_replace($user, '', $connected_liked);
                }

                $connected_liked = trim($connected_liked);
                if ($connected_liked == '' || !$connected_liked) {
                    $connected_liked = null;
                }

                $sql = $conn->prepare('UPDATE `public` SET connected=? WHERE username=?');
                $sql->execute([$connected_liked, $liked]);
                break;
            }
        }

        $response = array('status' => true, 'connected' => false);
        die(json_encode($response));
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

                break;
            } else {
                $notification = '<option value="http://localhost:8080/matcha/view_page_user.php?viewing='.$user.'">'.$user." has just liked you!</option>\n";
                //$notification = $user.' has just liked you!';
                $notify = $conn->prepare('INSERT INTO notifications (username, notify, seen, printed) VALUES (?, ?, 0, 0)');
                $notify->execute([$liked, $notification]);

                break;
            }
        }
    }

    $connected = false;
    $flag = 0;

    $sql = $conn->prepare('SELECT username, who_liked, connected FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user) {
            if (strpos($result['who_liked'], $liked) !== false) {
                $flag = 1;
                $user_connected = $result['connected'];
            }
        }
    }

    $sql = $conn->prepare('SELECT username, who_liked, connected FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $liked) {
            if (strpos($result['who_liked'], $user) !== false && $flag == 1) {
                $connected = true;
                $liked_connected = $result['connected'];

                if ($liked_connected == '' || !$liked_connected) {
                    $liked_connected = $user;
                } else {
                    $liked_connected = $liked_connected."\n".$user;
                }

                if ($user_connected == '' || !$user_connected) {
                    $user_connected = $liked;
                } else {
                    $user_connected = $user_connected."\n".$liked;
                }

                $s = $conn->prepare('UPDATE `public` SET connected=? WHERE username=?');
                $s->execute([$user_connected, $user]);
                $s = $conn->prepare('UPDATE `public` SET connected=? WHERE username=?');
                $s->execute([$liked_connected, $liked]);
            }
        }
    }

    $response = array('status' => true, 'connected' => $connected);
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately there was an error: '.$e.'</p>');
    die(json_encode($response));
}
