<?php

session_start();
include './config/database.php';

try {
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, age, biography, interests, gender, sex_pref, latitude, longitude, hidden FROM `profiles`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $_SESSION['logged_on_user']) {
            $age = $result['age'];
            $bio = $result['biography'];
            $gender = $result['gender'];
            $sex_pref = $result['sex_pref'];
            $lat = $result['latitude'];
            $long = $result['longitude'];
            $hidden = $result['hidden'];
            $interests = $result['interests'];
        }
    }
    $sql = $conn->prepare('SELECT username, meta FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $_SESSION['logged_on_user']) {
            $meta = $result['meta'];
        }
    }
} catch (PDOException $e) {
    echo 'I\'m extremely sorry, but there was an unexpected ERROR: '.$e;
}

?>

<html>
	<head>
		<title>Matcha</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<link href="https://fonts.googleapis.com/css?family=Baloo+Bhai|Lalezar|Ruslan+Display" rel="stylesheet">
		<link href="https://fonts.googleapis.com/css?family=Bungee+Inline|Chewy|Russo+One" rel="stylesheet">
	  <link rel="stylesheet" href="css/modulr.css" />
		<link rel="stylesheet" type="text/css" href="css/all_styles.css">
		<script type="text/javascript" src="script.js"></script>
	</head>

	<body>

<header id="header">
  <p style="margin-left:20px;margin-top:20px;">Account Setup:</p>
  <div id="header" style="height:35px;top:65px;">
  <button class="w3-btn" onclick="goBack()" style="font-size:20px">Go Back</button>
  <button class="w3-btn" onclick="goForward()" style="font-size:20px">Forward</button>
  <script>
  function goForward() {
      window.history.forward();
  }
  function goBack() {
    window.history.back();
  }
  </script>
  </div>
</header>

<section id="container">

  		<div id="error-messages"></div>

  <form id="imageUploadForm" method="post" enctype="multipart/form-data">
    <progress class="during-upload" id="progress" max="100" value="0">
    </progress>
    <div class="image-upload-fields">
        <strong style="font-size:20px;">Upload up to 5 pictures, first one as profile picture</strong>:
        <br />
        <input type="file" name="userfile" id="file">
        <br />
        <input type="submit" value="Upload Image" name="submit">
        <br />
    </div>
    <button type="button" name="cancelUpload" id="cancelUploadBtn" class="during-upload btn icon l round danger">
      <i aria-hidden="true" title="Cancel Upload">Cancel Upload</i>
    </button>
  </form>

  <br />

    <form id="profile" name="SetupProfile" method="post" enctype="multipart/form-data">
      <p style="color:#9C0234">First Name:</p><br />
      <input type="text" name="fname" id="fname" maxlength="30" required value="<?php echo $_SESSION['first_name']; ?>">
      <br />
      <br />
      <p style="color:#9C0234">Last Name:</p><br />
      <input type="text" name="lname" id="lname" maxlength="30" required value="<?php echo $_SESSION['last_name']; ?>">
      <br />
      <br />
      <p style="color:#9C0234">Email:</p><br />
      <input type="email" name="email" id="email" maxlength="50" required value="<?php echo $_SESSION['email']; ?>">
      <br />
      <br />
      <p style="color:#9C0234">Gender:</p><br />
      <input type="checkbox" name="genderm" id="genderm" <?php if ($gender == 'male' || $gender == 'male+female'): ?>checked<?php endif ?>>male<br />
      <input type="checkbox" name="genderf" id="genderf" <?php if ($gender == 'female' || $gender == 'male+female'): ?>checked<?php endif ?>>female<br />
      <br />
      <p style="color:#9C0234">Sexual preferences:</p><br />
      <input type="checkbox" name="sex_prefm" id="sex_prefm" <?php if ($sex_pref == 'male' || $sex_pref == 'male+female'): ?>checked<?php endif ?>>male<br />
      <input type="checkbox" name="sex_preff" id="sex_preff" <?php if ($sex_pref == 'female' || $sex_pref == 'male+female'): ?>checked<?php endif ?>>female<br />
      <br />
      <p style="color:#9C0234">Age:</p><br />
      <input type="number" name="age" id="age" min="18" max="100" required value="<?php echo $age; ?>">
      <br />
      <br />
      <p style="color:#9C0234">Biography:</p><br />
      <textarea rows="3" cols="30" name="biography" id="biography" maxlength="10000" form="profile" required placeholder="Biography"><?php echo $bio; ?></textarea>
      <br />
      <br />
      <p style="color:#9C0234">Interests:</p><br />
      <textarea rows="3" cols="30" name="interests" id="interests" maxlength="10000" form="profile" required placeholder="e.g. #blonde#fat#gorgeous"><?php echo $interests; ?></textarea>
      <br />
      <br />
      <p style="color:#9C0234">Location:</p><br />
      <button type="button" id="get_pos" onclick="get_coords()" class="sugar_bay_glasses_style">Get Current Coords</button>
      <br />
      latitude:
      <input type="text" id="latitude" name="latitude" maxlength="4" value="<?php if ($hidden == 'yes') {
    echo '';
} else {
    echo $lat;
} ?>">
      <br />
      longitude:
      <input type="text" id="longitude" name="longitude" maxlength="4" value="<?php if ($hidden == 'yes') {
    echo '';
} else {
    echo $long;
} ?>">
      <br />
      <!--<div id="mapholder"></div>-->
      <br />
      <br />
      <input class="subbtn" type="submit" value="Save">
    </form>

</section>


<footer id="footer">

 <button onclick="document.getElementById('id01').style.display='block'"
 class="w3-btn">Options</button>
  <div id="id01" class="w3-modal" style="display: none">
   <div class="w3-modal-content">

     <div class="w3-container">
       <button onclick="document.getElementById('id01').style.display='none'" class="w3-closebtn">Close tray</button>
       <div>

                <div style="float: left; width: 400px;">
                    <form method="post" id="delAccForm" enctype="application/x-www-form-urlencoded">
                        Delete Account:
                        <input type="password" style="background-color: Yellow;" id="delAccPwd" placeholder="password">
                        <input id="delacc" type="submit" style="background-color: #FE0001;" name="delaccount" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account?')">
                    </form>
                </div>
                <div style="float: left; width: 550px;">
                    <form id="modifyForm" method="post" enctype="application/x-www-form-urlencoded">
                      Change Password:
                      <input type="password" style="background-color: #015a5b;" id="oldpw" name="oldpwd" placeholder="old password">
                      <input type="password" style="background-color: #073d00;" id="newpw" name="newpwd" placeholder="new password">
                      <input type="submit" style="background-color: #FE0001;" name="submit" value="Change Password">
                    </form>
                </div>

                <a class="links" <?php if ($meta == 2): ?> href="home.php"<?php endif ?>>Home</a>

                <div style="float: right; width: 170px;">
                  <form method="get" action="logout.php">
                     <?php echo $_SESSION['logged_on_user'].':'; ?>
                     <input type="submit" style="background-color: #FE0001;" name="lout" value="logout">
                  </form>
                  <p class="cright">
                         <a class="cright" href="https://za.linkedin.com/in/dillon-mather-a0061b128">&#169; Dillon Mather | Matcha | 2016</a>
                 </p>
       </div>
     </div>

 </div>
</div>

</footer>

	</body>
</html>
