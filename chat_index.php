<?php

session_start();

if ($_SESSION['logged_on_user'] == '' || !$_SESSION['logged_on_user']) {
    return(header("LOCATION: index.php"));
}

$login = $_SESSION['logged_on_user'];
$chat_with = $_GET['chat_with'];

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

<body onload="focus(); update();">
	<div id="login" style="display:hidden"><?php echo $login; ?></div>
	<div id="chat_with" style="display:hidden"><?php echo $chat_with; ?></div>

<div class="msg-container">

	<header id="header">
	  <p style="margin-left:10px;margin-top:10px;"> <a href="home.php"> <img id="pro_pic" src="<?php if ($_SESSION['pro_pic']) {
	    echo $_SESSION['pro_pic'];
	} ?>"> </a> Chat with <?php echo $chat_with.':'; ?> </p>
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

	<div class="msg-area" id="msg-area">
	</div>
	<div class="bottom"><input type="text" name="msginput" class="msginput" id="msginput" onkeydown="if (event.keyCode == 13) sendmsg()" value="" placeholder="Enter your message here ... (Press enter to send message)">
	</div>
</div>

<script type="text/javascript">
var msginput = document.getElementById("msginput");
var msgarea = document.getElementById("msg-area");

function focus() {
  document.getElementById("msginput").focus();
}

function escapehtml(text) {
  return text
      .replace(/&/g, "&amp;")
      .replace(/</g, "&lt;")
      .replace(/>/g, "&gt;")
      .replace(/"/g, "&quot;")
      .replace(/'/g, "&#039;");
}

function update() {
	var xmlhttp = new XMLHttpRequest();
	var username = document.getElementById("login").value;console.log(username);
	var output = "";
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				var response = xmlhttp.responseText.split("\n")
				var rl = response.length
				var item = "";
				for (var i = 0; i < rl; i++) {
					item = response[i].split("\\")
					if (item[1] != undefined) {
						if (item[0] == username) {
							output += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + item[1] + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + item[0] + "</div> </div>";
						} else {
							output += "<div class=\"msgc\"> <div class=\"msg\">" + item[1] + "</div> <div class=\"msgarr\"></div> <div class=\"msgsentby\">Sent by " + item[0] + "</div> </div>";
						}
					}
				}
				msgarea.innerHTML = output;
				msgarea.scrollTop = msgarea.scrollHeight;
			}
		}
	      xmlhttp.open("POST", "get_messages.php?sender=" + username + "&reciever=" + reciever, true);
	      xmlhttp.send();
}

function sendmsg() {
	var message = msginput.value;
	if (message != "") {
		var username = document.getElementById("chat_with").value;console.log(username);
		var xmlhttp=new XMLHttpRequest();
		xmlhttp.onreadystatechange=function() {
			if (xmlhttp.readyState==4 && xmlhttp.status==200) {
				message = escapehtml(message)
				msgarea.innerHTML += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\">" + message + "</div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by " + username + "</div> </div>";
				msgarea.scrollTop = msgarea.scrollHeight;
				msginput.value = "";
			}
		}
	      xmlhttp.open("POST", "update_messages.php?sender=" + username + "&reciever=" + reciever + "&message=" + message, true);
	      xmlhttp.send();
  	}
}

//setInterval(function(){ update() }, 2500);

</script>

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
