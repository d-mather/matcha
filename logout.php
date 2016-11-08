<?php

session_start();
$_SESSION['logged_on_user'] = '';
$_SESSION['email'] = '';
$_SESSION['first_name'] = '';
$_SESSION['last_name'] = '';
$_SESSION['pro_pic'] = '';
header('LOCATION: index.php');
