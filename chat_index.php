<?php

include 'config/database.php';
session_start();

if ($_SESSION['logged_on_user'] == '' || !$_SESSION['logged_on_user']) {
    return header('LOCATION: index.php');
}

try {
    $login = $_SESSION['logged_on_user'];
    $DB_DSN = $DB_DSN.';dbname=matcha';
    $conn = new PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare('SELECT username, notify, seen FROM `notifications`');
    $sql->execute();
    $seen = '';
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login) {
            if ($seen == '') {
                $seen = $result['notify'];
            } else {
                $seen = $seen.'<br>'.$result['notify'];
            }
        }
    }
    $sql = $conn->prepare('SELECT username, visited FROM `public`');
    $sql->execute();
    while ($result = $sql->fetch(PDO::FETCH_ASSOC)) {
        if ($result['username'] == $login) {
            $visited = $result['visited'];
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

<body onload="focus(); update();">

<div class="msg-container">

	<header id="header">
	  <p style="margin-left:10px;margin-top:10px;"> <a href="home.php"> <img id="pro_pic" src="<?php if ($_SESSION['pro_pic']) {
    echo $_SESSION['pro_pic'];
} ?>"> </a> Chat with <?php echo $_GET['chat_with']; ?>:
   </p>
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
          function view_dropdown() {
              document.getElementById("visitDropdown").classList.toggle("show");
          }
          function notify_dropdown() {
              document.getElementById("notifyDropdown").classList.toggle("show");
          }
          function mark_read() {
              document.getElementById("notifybtn").style.backgroundColor = "transparent";
          }

    setInterval(function() {
          if(typeof(EventSource) !== "undefined") {
              var source = new EventSource("notify.php");
              source.onmessage = function(event) {
                if (event.data == "\\") {
                  var notifybtn = document.getElementById("notifybtn");
                  notifybtn.style.backgroundColor = "#e8d1d0";
                } else {
                  document.getElementById("notifyDropdown").innerHTML += event.data + "<br>";
                  if (event.data) {
                      var notifybtn = document.getElementById("notifybtn");
                      notifybtn.style.backgroundColor = "#e8d1d0";
                  }
                }
              };
          } else {
              document.getElementById("notifyDropdown").innerHTML = "Sorry, your browser does not support server-sent events...";
          }
        }, 5000);
        </script>

        <div class="dropdown">
        <button onclick="view_dropdown();" class="dropbtn">Visit History</button>
          <select size="10" onchange="location = this.value;" id="visitDropdown" class="dropdown-content">
              <?php echo $visited; ?>
          </select>
        </div>

        <div class="dropdown">
        <button onclick="notify_dropdown(); mark_read();" id="notifybtn" class="dropbtns">Notifications</button>
          <select size="20" onchange="location = this.value;" id="notifyDropdown" class="dropdown-contents">
              <?php echo $seen; ?>
          </select>
        </div>

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
    var chat_with = window.location.search.substring(1).split('=')[1];

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
        var output = "";
        xmlhttp.onreadystatechange = function() {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                var response = xmlhttp.responseText.split("\n")
                var rl = response.length
                var item = "";
                for (var i = 0; i < rl; i++) {
                    item = response[i].split("\\");
                    if (item[2] != undefined && item[1] != undefined && item[0] != undefined && chat_with) {
                        if (item[0] == chat_with) {
                            output += "<div class=\"msgc\"> <div class=\"msg\"><strong>" + item[2] + "</strong></div> <div class=\"msgarr\"></div> <div class=\"msgsentby\">Sent by " + item[0] + "</div> </div>";
                        } else {
                            output += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\"><strong>" + item[2] + "</strong></div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by You</div> </div>";
                        }
                    }
                }
                if (output)
                    msgarea.innerHTML = output;
                msgarea.scrollTop = msgarea.scrollHeight;
            }
        }
        xmlhttp.open("GET", "get_messages.php?reciever=" + chat_with, true);
        xmlhttp.send();
    }

    function sendmsg() {
        var message = msginput.value;
        if (message != "" && chat_with) {
            var xmlhttp = new XMLHttpRequest();
            xmlhttp.onreadystatechange = function() {
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                    message = escapehtml(message)
                    msgarea.innerHTML += "<div class=\"msgc\" style=\"margin-bottom: 30px;\"> <div class=\"msg msgfrom\"><strong>" + message + "</strong></div> <div class=\"msgarr msgarrfrom\"></div> <div class=\"msgsentby msgsentbyfrom\">Sent by You</div> </div>";
                    msgarea.scrollTop = msgarea.scrollHeight;
                    msginput.value = "";
                }
            }
            xmlhttp.open("GET", "update_messages.php?reciever=" + chat_with + "&message=" + message, true);
            xmlhttp.send();
        }
    }

    setInterval(function() { update() }, 5000);

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
