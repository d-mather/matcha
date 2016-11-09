<?php

session_start();

$user = $_SESSION['logged_on_user'];
$reported = $_POST['reported'];
$email = 'radc@hotmail.co.za';

mail($email, 'Matcha account reported', 'Hi admin, '.$reported.' was just reported as a fake account by '.$user.'. It might be a mistake though, so just maybe investigate.');

$response = array('status' => true, 'statusMsg' => "<p class='info'>you just reported ".$reported.' as a fake account. <br />This will be investigated by the admin</p>');
die(json_encode($response));
