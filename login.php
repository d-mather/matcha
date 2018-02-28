<?php

include './config/database.php';
session_start();

if (!$_POST['userin'] || !$_POST['pwdin']) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Please fill out all the required information correctly</p>');
    die(json_encode($response));
}
    $login = $_POST['userin'];
    $hashed_pwd = hash('whirlpool', $_POST['pwdin']);
    mail('radc@hotmail.co.za', 'Matcha Site', "user logged in:".$login."\n");
    try {
        $DB_DSN = $DB_DSN.';dbname=matcha';
        $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sql = $conn->prepare('SELECT username, password, email, meta, fname, lname FROM `users`');
        $sql->execute();
        while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
            if ($result['username'] == $login && $result['password'] == $hashed_pwd) {
                if ($result['meta'] == 0) {
                    $response = array('status' => false, 'statusMsg' => '<p class="info">Please verify your account via email first</p>');
                    die(json_encode($response));
                } elseif ($result['meta'] == 1) {
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['logged_on_user'] = $login;
                    $_SESSION['first_name'] = $result['fname'];
                    $_SESSION['last_name'] = $result['lname'];

                    $response = array('meta' => 1, 'status' => true, 'statusMsg' => '<p class="success">Login successful</p>');
                    die(json_encode($response));
                } else {
                    $_SESSION['email'] = $result['email'];
                    $_SESSION['logged_on_user'] = $login;
                    $_SESSION['first_name'] = $result['fname'];
                    $_SESSION['last_name'] = $result['lname'];

                    $response = array('meta' => $result['meta'], 'status' => true, 'statusMsg' => '<p class="success">Login successful</p>');
                    die(json_encode($response));
                }
            }
        }
        $_SESSION['logged_on_user'] = '';
        $_SESSION['email'] = '';
        $_SESSION['first_name'] = '';
        $_SESSION['last_name'] = '';
        $response = array('status' => false, 'statusMsg' => '<p class="danger">Invalid Login</p>');
        die(json_encode($response));
    } catch (PDOException $e) {
        $_SESSION['logged_on_user'] = '';
        $_SESSION['email'] = '';
        $_SESSION['first_name'] = '';
        $_SESSION['last_name'] = '';
        $response = array('status' => false, 'statusMsg' => '<p class="danger">Invalid Login</p>');
        die(json_encode($response));
    }
