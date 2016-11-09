<?php

include 'config/database.php';
session_start();

try {
    $login = $_SESSION['logged_on_user'];
    $v_username = $_GET['viewing'];
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, pic_path_and_name, pic_number FROM `pictures`');
    $sql->execute();
    $_SESSION['pro_pic'] = '';
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login && $result['pic_number'] == 1) {
            $profile_pic = $result['pic_path_and_name'];
            $_SESSION['pro_pic'] = $profile_pic;
        }
    }
    $sql = $conn->prepare('SELECT username, age, biography, interests, gender, sex_pref, latitude, longitude, hidden FROM `profiles`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $v_username) {
            $v_age = $result['age'];
            $v_biography = $result['biography'];
            $v_gender = $result['gender'];
            $v_sex_pref = $result['sex_pref'];
            $v_latitude = $result['latitude'];
            $v_longitude = $result['longitude'];
            $v_interests = $result['interests'];
        }
    }
    $sql = $conn->prepare('SELECT username, likes, views FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $v_username) {
            $v_likes = $result['likes'];
            $v_views = $result['views'];
            $v_fame = $v_likes + $v_views;
        }
    }
    $sql = $conn->prepare('SELECT username, fname, lname FROM `users`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $v_username) {
            $v_fname = $result['fname'];
            $v_lname = $result['lname'];
        }
    }
    $v_pic_path_and_name1 = '';
    $v_pic_path_and_name2 = '';
    $v_pic_path_and_name3 = '';
    $v_pic_path_and_name4 = '';
    $v_pic_path_and_name5 = '';
    $sql = $conn->prepare('SELECT username, pic_path_and_name, pic_number FROM `pictures`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $v_username && $result['pic_number'] == 1) {
            $v_pic_path_and_name1 = $result['pic_path_and_name'];
        }
        if ($result['username'] == $v_username && $result['pic_number'] == 2) {
            $v_pic_path_and_name2 = $result['pic_path_and_name'];
        }
        if ($result['username'] == $v_username && $result['pic_number'] == 3) {
            $v_pic_path_and_name3 = $result['pic_path_and_name'];
        }
        if ($result['username'] == $v_username && $result['pic_number'] == 4) {
            $v_pic_path_and_name4 = $result['pic_path_and_name'];
        }
        if ($result['username'] == $v_username && $result['pic_number'] == 5) {
            $v_pic_path_and_name5 = $result['pic_path_and_name'];
        }
    }
} catch (PDOException $e) {
    file_put_contents('error_log', $e);
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
	</head>

	<body>
<header id="header">
  <p style="margin-left:10px;margin-top:10px;"> <a href="home.php"> <img id="pro_pic" src="<?php if ($profile_pic) {
    echo $profile_pic;
} ?>"> </a> You are viewing <?php echo $v_username.'\'s Profile:'; ?> </p>
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
    <div id="viewer">
      <p style="line-height: 50px;">
        <u>Username:</u> &nbsp <?php echo $v_username; ?> <br />
        <u>Fame Rating:</u> &nbsp <?php echo $v_fame.' awesomness!'; ?> <br />
        <u>Full Name:</u> &nbsp <?php echo $v_fname.' '.$v_lname; ?> <br />
        <u>Age:</u> &nbsp <?php echo $v_age; ?> <br />
        <u>Gender:</u> &nbsp <?php echo $v_gender; ?> <br />
        <u>Sexual Preferences:</u> &nbsp <?php echo $v_sex_pref; ?> <br />
        <u>Biography:</u> <br /> <?php echo $v_biography; ?> <br />
        <u>Interests:</u> <br /><size style="max-width: 20%;"> <?php echo $v_interests; ?></size> <br />
        <u>Location:</u><br />
        latitude coords: <br /> <?php if ($v_latitude) {
    echo $v_latitude;
} else {
    echo 'Unknown. Please Report User!';
} ?> <br />
        longitude coords: <br /> <?php if ($v_longitude) {
    echo $v_longitude;
} else {
    echo 'Unknown. Please Report User!';
} ?> <br />
        <u>Pictures:</u> <br />
      </p>
      <?php if ($v_pic_path_and_name1 != '') {
    echo '<img src="'.$v_pic_path_and_name1.'" style="max-width: 50%;" />';
}?>
<?php if ($v_pic_path_and_name2 != '') {
    echo '<img src="'.$v_pic_path_and_name2.'" style="max-width: 50%;" />';
}?>
<?php if ($v_pic_path_and_name3 != '') {
    echo '<img src="'.$v_pic_path_and_name3.'" style="max-width: 50%;" />';
}?>
<?php if ($v_pic_path_and_name4 != '') {
    echo '<img src="'.$v_pic_path_and_name4.'" style="max-width: 50%;" />';
}?>
<?php if ($v_pic_path_and_name5 != '') {
    echo '<img src="'.$v_pic_path_and_name5.'" style="max-width: 50%;" />';
}?>
    </div>
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
       <a class="links" href="setup_profile.php">Account Setup</a>
       <a class="links" href="home.php">Home</a>
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
