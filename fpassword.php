<?php

include './config/database.php';

if (!$_POST['fuser'] || !$_POST['femail'] || !$_POST['fpwd1'] || !$_POST['fpwd2']) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Please fill out all the required information</p>');
    die(json_encode($response));
}

if ($_POST['fpwd1'] != $_POST['fpwd2']) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Passwords do not match</p>');
    die(json_encode($response));
}

if (strlen($_POST['fpwd1']) < 4) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Password must be at least 4 characters long</p>');
    die(json_encode($response));
}

$user = $_POST['fuser'];
$email = $_POST['femail'];
$fpwd = hash('whirlpool', $_POST['fpwd1']);
try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, email, meta, fname, lname, hashed FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user && $result['email'] == $email) {
            if ($result['meta'] == 0) {
                $response = array('status' => false, 'statusMsg' => '<p class="info">Please verify your account via email first</p>');
                die(json_encode($response));
            } else {
                $fname = $result['fname'];
                $lname = $result['lname'];
                $hashed = $result['hashed'];
                mail($email, 'Matcha account Reset', "Hi $fname $lname,\nPlease click on this link to reset your password as ".$user.":\nhttp://localhost:8080/matcha/resetpwd_email.php?h=$hashed&u=$user&e=$email&p=$fpwd");

                $response = array('status' => true, 'statusMsg' => '<p class="success">Password Change Request.<br /> An email as been sent.</p>');
                die(json_encode($response));
            }
        }
    }
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Invalid Input, Please fill out the required information correctly.</p>');
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Please run config/setup.php file to create database</p>');
    die(json_encode($response));
}
