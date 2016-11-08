<?php

session_start();
include './config/database.php';

$user = $_SESSION['logged_on_user'];

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, gender, sex_pref, age, biography, interests, latitude, longitude FROM `profiles`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user) {
            $gender = $result['gender'];
            $sex_pref = $result['sex_pref'];
            $age = $result['age'];
            $bio = $result['biography'];
            $lat = $result['latitude'];
            $long = $result['longitude'];
            $interests = $result['interests'];
        }
    }
    $sql = $conn->prepare('SELECT username, fname, lname, email, meta FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $user) {
            $fname = $result['fname'];
            $lname = $result['lname'];
            $email = $result['email'];
            $meta = $result['meta'];
        }
    }

    if ($_POST['fname'] != $fname) {
        $newfname = $_POST['fname'];
        $_SESSION['first_name'] = $newfname;
        $sql = $conn->prepare('UPDATE `users` SET fname=? WHERE username=?');
        $sql->execute([$newfname, $user]);
    }

    if ($_POST['lname'] != $lname) {
        $newlname = $_POST['lname'];
        $_SESSION['last_name'] = $newlname;
        $sql = $conn->prepare('UPDATE `users` SET lname=? WHERE username=?');
        $sql->execute([$newlname, $user]);
    }

    if ($_POST['email'] != $email) {
        $email = $_POST['email'];
        $_SESSION['email'] = $email;
        $sql = $conn->prepare('UPDATE `users` SET email=? WHERE username=?');
        $sql->execute([$email, $user]);
    }

    if ($_POST['genderm'] == 'true' || $_POST['genderf'] == 'true') {
        if ($_POST['genderm'] == 'true' && $_POST['genderf'] == 'true') {
            $gender_finished = 'male+female';
        } elseif ($_POST['genderm'] == 'true') {
            $gender_finished = 'male';
        } elseif ($_POST['genderf'] == 'true') {
            $gender_finished = 'female';
        }
    } else {
        $gender_finished = 'male+female';
    }

    if ($gender_finished != $gender) {
        $gender = $gender_finished;
        $sql = $conn->prepare('UPDATE `profiles` SET gender=? WHERE username=?');
        $sql->execute([$gender, $user]);
    }

    if ($_POST['sex_prefm'] == 'true' || $_POST['sex_preff'] == 'true') {
        if ($_POST['sex_prefm'] == 'true' && $_POST['sex_preff'] == 'true') {
            $sex_pref_finished = 'male+female';
        } elseif ($_POST['sex_prefm'] == 'true') {
            $sex_pref_finished = 'male';
        } elseif ($_POST['sex_preff'] == 'true') {
            $sex_pref_finished = 'female';
        }
    } else {
        $sex_pref_finished = 'male+female';
    }

    if ($sex_pref_finished != $sex_pref) {
        $sex_pref = $sex_pref_finished;
        $sql = $conn->prepare('UPDATE `profiles` SET sex_pref=? WHERE username=?');
        $sql->execute([$sex_pref, $user]);
    }

    if ($_POST['age'] != $age) {
        $age = intval($_POST['age']);
        $sql = $conn->prepare('UPDATE `profiles` SET age=? WHERE username=?');
        $sql->execute([$age, $user]);
    }

    if ($_POST['biography'] != $bio) {
        $bio = $_POST['biography'];
        $sql = $conn->prepare('UPDATE `profiles` SET biography=? WHERE username=?');
        $sql->execute([$bio, $user]);
    }

    if ($_POST['interests'] != $interests) {
        $interests = $_POST['interests'];
        $interests = preg_split("/[\s,#]+/", $interests);
        $interests = array_filter($interests);
        $interests = implode(", \n", $interests);
        $sql = $conn->prepare('UPDATE `profiles` SET interests=? WHERE username=?');
        $sql->execute([$interests, $user]);
    }

    if ($_POST['longitude'] != $long) {
        $long = $_POST['longitude'];
        $sql = $conn->prepare('UPDATE `profiles` SET longitude=? WHERE username=?');
        $sql->execute([$long, $user]);
    }

    if ($_POST['latitude'] != $lat) {
        $lat = $_POST['latitude'];
        $sql = $conn->prepare('UPDATE `profiles` SET latitude=? WHERE username=?');
        $sql->execute([$lat, $user]);
    }

    if ($_POST['hidden'] == 'yes') {
        $yes = 'yes';
        $sql = $conn->prepare('UPDATE `profiles` SET hidden=? WHERE username=?');
        $sql->execute([$yes, $user]);
    } else {
        $no = 'no';
        $sql = $conn->prepare('UPDATE `profiles` SET hidden=? WHERE username=?');
        $sql->execute([$no, $user]);
    }

    $sql = $conn->prepare('UPDATE `users` SET meta=2 WHERE username=?');
    $sql->execute([$user]);
} catch (PDOException $e) {
    $response = array('status' => false, 'statusMsg' => '<p class="danger">I\'m extremely sorry, but there was an unexpected ERROR: '.$e.'</p>');
    die(json_encode($response));
}
$response = array('status' => true, 'statusMsg' => '<p class="success">Details have been Successfully saved</p>');
die(json_encode($response));
