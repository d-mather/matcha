<?php

$target_dir = 'uploads/';
$uploadOk = 1;
$imageFileType = pathinfo($_FILES['userfile']['name'], PATHINFO_EXTENSION);
$target_file = $target_dir.uniqid().'.'.$imageFileType;

if (!file_exists('uploads')) {
    mkdir('uploads');
}
if (isset($_POST['submit'])) {
    $check = getimagesize($_FILES['userfile']['tmp_name']);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        die('<p class="warning">Select a valid image to upload. <br />E.G:   JPG, JPEG, PNG or GIF.</p>');
    }
}
if (!$imageFileType) {
    die('<p class="info">Please select an image</p>');
}
if (file_exists($target_file)) {
    echo '<p class="messages">Sorry, file already exists</p>';
    $uploadOk = 0;
}
if ($_FILES['fileToUpload']['size'] > 1000000) {
    echo '<p class="messages">Sorry, your file is too large</p>';
    $uploadOk = 0;
}

if ($imageFileType != 'jpg' && $imageFileType != 'png' && $imageFileType != 'jpeg' && $imageFileType != 'gif') {
    echo '<p class="messages">Sorry, only JPG, JPEG, PNG & GIF files are allowed</p>';
    $uploadOk = 0;
}
if ($uploadOk == 0) {
    echo '<p class="messages">Sorry, your file was not uploaded</p>';
} else {
    if (move_uploaded_file($_FILES['userfile']['tmp_name'], $target_file)) {
        echo '<p class="success">The file has been uploaded</p>';
    } else {
        echo '<p class="danger">Sorry, there was an error uploading your file</p>';
    }
}
