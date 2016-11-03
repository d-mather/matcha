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
				<p style="margin-left:20px;margin-top:20px;">Reset Password:</p>
			</header>

			<div id="ind">

			<form class="p" id="pwdreset" method="post" enctype="application/x-www-form-urlencoded">
				Username: <input type="text" id="fuser" name="login" maxlength="30" required placeholder="Username"><br />
				Email: <input type="email" id="femail" name="email" maxlength="50" required placeholder="Email"><br />
				New Password: <input type="password" id="fpwd1" name="fpasswd1" required placeholder="New Password"><br />
				Confirm Password: <input type="password" id="fpwd2" name="fpasswd2" required placeholder="Confirm Password"><br />
				<input type="submit" style="background-color: #47ed6d;" name="create" value="Reset Password"><br />
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
