<?php

include 'config/database.php';
session_start();

$response = array('status' => true);
die(json_encode($response));
