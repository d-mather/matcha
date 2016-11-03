<?php

session_start();
include './config/database.php';

$user = $_SESSION['logged_on_user'];
$psswd = hash('whirlpool', $_POST['delAccPwd']);

if (!$_POST['delAccPwd']) {
    $response = array('status' => false, 'statusMsg' => '<p class="info">Please enter your password</p>');
    die(json_encode($response));
}
if ($user === 'admin') {
    $response = array('status' => false, 'statusMsg' => '<p class="success">You Are Admin</p>');
    die(json_encode($response));
}
  try {
      $DB_DSN = $DB_DSN.';dbname=matcha';
      $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = $conn->prepare('SELECT username, password FROM `users`');
      $sql->execute();
      while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
          if ($result['username'] == $user && $result['password'] == $psswd) {
              $sql = $conn->prepare('DELETE FROM `users` WHERE username=?;');
              $sql->execute([$user]);

              $sql = $conn->prepare('DELETE FROM `profiles` WHERE username=?;');
              $sql->execute([$user]);

              $sql = $conn->prepare('DELETE FROM `public` WHERE username=?;');
              $sql->execute([$user]);

              $sql = $conn->prepare('DELETE FROM `pictures` WHERE username=?;');
              $sql->execute([$user]);

              date_default_timezone_set('Africa/Johannesburg');
              $date = date('d/m/Y');
              $time = date('h:i:sa');
              mail($_SESSION['email'], 'Matcha account deleted', "Matcha account successfully deleted\n on: \n".$date." \nat: \n".$time." \nas: \n".$user.'.');

              $_SESSION['logged_on_user'] = '';
              $_SESSION['fname'] = '';
              $_SESSION['lname'] = '';
              $_SESSION['email'] = '';

              $response = array('status' => true, 'statusMsg' => '<p class="danger">Account Deleted</p>');
              die(json_encode($response));
          }
      }
      $response = array('status' => false, 'statusMsg' => '<p class="danger">Incorrect Password, Account not deleted.</p>');
      die(json_encode($response));
  } catch (PDOException $e) {
      $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately, There was an error</p>');
      die(json_encode($response));
  }
