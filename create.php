<?php

include './config/database.php';
session_start();

if (!$_POST['userup'] || !$_POST['emailup'] || !$_POST['pwd1up'] || !$_POST['pwd2up'] || !$_POST['fnameup'] || !$_POST['lnameup']) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Please fill out all the required information correctly</p>');
    die(json_encode($response));
}
if ($_POST['pwd1up'] != $_POST['pwd2up']) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Passwords Do Not Match!</p>');
    die(json_encode($response));
}
if (strlen($_POST['userup']) > 30) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Usename can\'t exceed 30 characters</p>');
    die(json_encode($response));
}
if (strlen($_POST['pwd1up']) < 4) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Password must be at least 4 characters long</p>');
    die(json_encode($response));
}
if (!filter_var($_POST['emailup'], FILTER_VALIDATE_EMAIL)) {
    $response = array('status' => false, 'statusMsg' => '<p class="warning">Incorrect Email! Stop Messing Around! :(</p>');
    die(json_encode($response));
}

$login = $_POST['userup'];
$pwd = hash('whirlpool', $_POST['pwd1up']);
$email = $_POST['emailup'];
$fname = $_POST['fnameup'];
$lname = $_POST['lnameup'];
$hashed = md5("$login");

mail('radc@hotmail.co.za', 'Matcha Site', "new user signed up. emailup: ".$_POST['emailup']." \nuserup: ".$_POST['userup']." \n");
try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, email FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login) {
            $response = array('status' => false, 'statusMsg' => '<p class="warning">Sorry, That Username Already Exists</p>');
            die(json_encode($response));
        } elseif ($result['email'] == $email) {
            $response = array('status' => false, 'statusMsg' => '<p class="warning">Sorry, That Email Already Exists</p>');
            die(json_encode($response));
        }
    }
    $sql = $conn->prepare('INSERT INTO users (username, fname, lname, password, email, hashed, meta) VALUES (?, ?, ?, ?, ?, ?, 0);');
    $sql->execute([$login, $fname, $lname, $pwd, $email, $hashed]);

    $sql = $conn->prepare('INSERT INTO profiles (username) VALUES (?);');
    $sql->execute([$login]);

    $sql = $conn->prepare('INSERT INTO public (username) VALUES (?);');
    $sql->execute([$login]);

    mail($email, 'Matcha account', "Hi $fname $lname,\nPlease verify your Matcha account as ".$login.":\nhttp://localhost:8080/matcha/verify.php?hashed=$hashed");

    $response = array('status' => true, 'statusMsg' => '<p class="success">Check your email to verify your account.</p>');
    die(json_encode($response));
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">Please run config/setup.php file to create database</p>');
    die(json_encode($response));
}
