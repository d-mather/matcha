<?php
  session_start();
  include './config/database.php';

  if (!$_POST['oldpw'] || !$_POST['newpw']) {
      $response = array('status' => false, 'statusMsg' => '<p class="warning">Please fill out all the required information</p>');
      die(json_encode($response));
  }

  if ($_POST['oldpw'] == $_POST['newpw']) {
      $response = array('status' => false, 'statusMsg' => '<p class="info">Do you get the point of CHANGEing a password?</p>');
      die(json_encode($response));
  }

  if (strlen($_POST['newpw']) < 4) {
      $response = array('status' => false, 'statusMsg' => '<p class="warning">Password must be at least 4 characters long</p>');
      die(json_encode($response));
  }

  $user = $_SESSION['logged_on_user'];
  $email = $_SESSION['email'];
  $newpwd = hash('whirlpool', $_POST['newpw']);
  $oldpwd = hash('whirlpool', $_POST['oldpw']);
  try {
      $DB_DSN = $DB_DSN.';dbname=matcha';
      $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = $conn->prepare('SELECT username, password FROM `users`');
      $sql->execute();
      while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
          if ($result['username'] == $_SESSION['logged_on_user'] && $result['password'] == $oldpwd) {
              $sql = $conn->prepare('UPDATE `users` SET password=? WHERE username=?');
              $sql->execute([$newpwd, $user]);

              date_default_timezone_set('Africa/Johannesburg');
              $date = date('d/m/Y');
              $time = date('h:i:sa');
              mail($email, 'Matcha account modified', "Your Matcha account password was recently changed on: \n".$date." \nat: \n".$time." \nas: \n".$user.'.');

              $response = array('status' => true, 'statusMsg' => '<p class="success">Password Successfully Changed.<br /> A notification email as also been sent ;)</p>');
              die(json_encode($response));
          }
      }
      $response = array('status' => false, 'statusMsg' => '<p class="danger">Incorrect Password</p>');
      die(json_encode($response));
  } catch (PDOException $e) {
      $response = array('status' => false, 'statusMsg' => '<p class="danger">Unfortunately, There was an error. <br /><b><u>Error Message :</u></b><br />'.$e.'</p>');
      die(json_encode($response));
  }
