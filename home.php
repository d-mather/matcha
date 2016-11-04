<?php

include 'config/database.php';
session_start();

try {
    $login = $_SESSION['logged_on_user'];
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, pic_path_and_name, pic_number FROM `pictures`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login && $result['pic_number'] == 1) {
            $profile_pic = $result['pic_path_and_name'];
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
		<script type="text/javascript" src="script.js"></script>
		<script type="text/javascript" src="get_users.js"></script>
	</head>

	<body>
<header id="header">
  <p style="margin-left:10px;margin-top:10px;"> <img id="pro_pic" src="<?php if ($profile_pic) {
    echo $profile_pic;
} ?>"> Hi <?php echo $_SESSION['first_name'].'!'; ?> </p>
</header>

<section id="container">
		<div id="error-messages"></div>
    <div id="profile_list">
    </div>
</section>

<footer id="footer">

				<div style="float: left;">
					<form method="post" id="delAccForm" enctype="application/x-www-form-urlencoded">
						Delete Account:
						<input type="password" style="background-color: Yellow;" id="delAccPwd" placeholder="password">
						<input id="delacc" type="submit" style="background-color: #FE0001;" name="delaccount" value="Delete Account" onclick="return confirm('Are you sure you want to delete your account?')">
					</form>
				</div>

				<div style="float: left;">
					<form id="modifyForm" method="post" enctype="application/x-www-form-urlencoded">
          	Change Password:
          	<input type="password" style="background-color: #015a5b;" id="oldpw" name="oldpwd" placeholder="old password">
          	<input type="password" style="background-color: #073d00;" id="newpw" name="newpwd" placeholder="new password">
          	<input type="submit" style="background-color: #FE0001;" name="submit" value="Change Password">
        	</form>
				</div>

        <a class="links" href="setup_profile.php">Account Setup</a>

				<div style="float: right;">
          <form method="get" action="logout.php">
  					<?php session_start(); echo $_SESSION['logged_on_user'].':'; ?>
            <input type="submit" style="background-color: #FE0001;" name="lout" value="logout">
				  </form>

          <p class="cright">
  						<a class="cright" href="https://za.linkedin.com/in/dillon-mather-a0061b128">&#169; Dillon Mather | Matcha | 2016</a>
  				</p>

        </div>

</footer>

	</body>
</html>
