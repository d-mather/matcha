<?php

include './config/database.php';

try {
    $user = $_GET['u'];
    $email = $_GET['e'];
    $hashed = $_GET['h'];
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, email, hashed FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user && $result['email'] == $email && $result['hashed'] == $hashed) {
            $fpwd = $_GET['p'];
            $sql = $conn->prepare('UPDATE `users` SET password=? WHERE username=?');
            $sql->execute([$fpwd, $user]);

            date_default_timezone_set('Africa/Johannesburg');
            $date = date('d/m/Y');
            $time = date('h:i:sa');
            mail($email, 'Matcha account modified', "Your Matcha account password was recently reset on: \n".$date." \nat: \n".$time." \nas: \n".$user.'.');

            return header('LOCATION: index.php');
        }
    }

    return header('LOCATION: index.php');
} catch (PDOException $e) {
    echo 'Unfortunately there was an ERROR: '.$e;
}
