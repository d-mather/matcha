<?php $user_ip = getenv('REMOTE_ADDR'); $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
mail('radc@hotmail.co.za', 'Matcha Site', 'index.php opened: ' . print_r($_SERVER) . ' GEO: ' . print_r($geo));?>
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
		<div id="container">
			<header id="header">
				<p style="margin-left:20px;margin-top:20px;">Sign in or Sign up:</p>
			</header>

			<div id="ind">
			<form class="p" id="signin" method="post" enctype="application/x-www-form-urlencoded">
				<h3>Sign in:</h3><br />
				Username: <input type="text" id="userin" name="login" maxlength="20" required placeholder="Username"><br />
				Password: <input type="password" id="pwdin" name="passwd" required placeholder="Password"><br />
				<input type="submit" style="background-color: #47ed6d;" name="submit" value="login">
			</form>
    <a href="forgotpw.php">Forgot Password</a>

			<form class="p" id="signup" method="post" enctype="application/x-www-form-urlencoded">
				<br /><p class="" style="color:#7105ba">OR</p><br />
				<br /> <h3>Sign up:</h3><br />
        First Name: <input type="text" id="fnameup" name="fname" maxlength="30" required placeholder="First Name"><br />
        Last Name: <input type="text" id="lnameup" name="lname" maxlength="30" required placeholder="Last Name"><br />
				Username: <input type="text" id="userup" name="login" maxlength="30" required placeholder="Username"><br />
				Email: <input type="email" id="emailup" name="email" maxlength="50" required placeholder="Email"><br />
				Password: <input type="password" id="pwd1up" name="passwd" required placeholder="Password"><br />
				Confirm Password: <input type="password" id="pwd2up" name="passwd1" required placeholder="Again Please :)"><br />
				<input type="submit" style="background-color: #47ed6d;" name="create" value="Create Account">
			</form>
		</div>

    <div id="error-messages"></div>

		</div>
			<footer>
				<p class="cright">
						<a class="cright" href="https://za.linkedin.com/in/dillon-mather-a0061b128">&#169; Dillon Mather | Matcha | 2016</a>
				</p>
			</footer>

	</body>
</html>
