// A lightweight function for ajax POST
function ajax_post(url, data, callback) {
    var httpRequest = new XMLHttpRequest();
    httpRequest.addEventListener("error", function(event) {
        console.log("An error has occured. ERROR : " + event.message);
    });
    httpRequest.addEventListener("readystatechange", function() {
        if (httpRequest.readyState == 4 && httpRequest.status == 200) {
            callback(httpRequest);
        }
    });
    httpRequest.open("POST", url, true);
    httpRequest.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    httpRequest.send(data);
}

// Function to display messages
function displayError(errMsg) {
    let errDiv = document.getElementById("error-messages");
    clearTimeout(addClass_timeout);
    clearTimeout(removeError_timeout);
    if (errDiv) {
        errDiv.innerHTML = errMsg;
        let msgs = errDiv.childNodes;
        for (let msg of msgs) {
            addClass(msg, "scale-in");
            addClass(msg, "slow");
        }
    }
    let tmp = document.createElement("div");
    tmp.innerHTML = errMsg;
    errMsg = tmp.textContent || tmp.innerText || "No error message found.";
    console.log(errMsg);
}

function view_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var temp = String("you viewed: " + user);
            console.log(temp);

            let data = "viewed=" + user;
            ajax_post("view_user.php", data, function(httpRequest) {
                let response = JSON.parse(httpRequest.responseText);
                if (response.status == true) {
                    var url = "view_page_user.php?viewing=" + user;
                    window.location = url;
                } else {
                    displayError(response.statusMsg);
                }
            });
        }
    }
}

function like_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var tmplike = document.getElementById(user + "_likebtn");
            let data = "liked=" + user + "&status=" + tmplike.innerHTML;
            ajax_post("like_user.php", data, function(httpRequest) {
                let response = JSON.parse(httpRequest.responseText);
                if (response.status == false) {
                    displayError(response.statusMsg);
                } else {
                    if (tmplike.innerHTML == "like") {
                        tmplike.innerHTML = "unlike";
                        var tmpchat = document.getElementById(user + "_chatbtn");
                        tmpchat.disabled = false;
                        var temp = String("you liked: " + user);
                        console.log(temp);
                    } else if (tmplike.innerHTML == "unlike") {
                        tmplike.innerHTML = "like";
                        var tmpchat = document.getElementById(user + "_chatbtn");
                        tmpchat.disabled = true;
                        var temp = String("you unliked: " + user);
                        console.log(temp);
                    }
                }

            });
        }
    }
}

function chat_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            let data = "chat_with=" + user;
            ajax_post("chat.php", data, function(httpRequest) {
                let response = JSON.parse(httpRequest.responseText);
                var temp = String("chat with: " + user);
                console.log(temp);

                if (response.status == true) {
                    window.location("chat_index.php");
                } else {
                    displayError(response.statusMsg);
                }
            });
        }
    }
}

function block_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var tmpblock = document.getElementById(user + "_blockbtn");
            let data = "blocked=" + user + "&status=" + tmpblock.innerHTML;
            ajax_post("blocked.php", data, function(httpRequest) {
                let response = JSON.parse(httpRequest.responseText);
                if (response.status == false)
                    displayError(response.statusMsg);
                else {

                    if (tmpblock.innerHTML == "block") {
                        tmpblock.innerHTML = "unblock";
                        var tmplike = document.getElementById(user + "_likebtn");
                        tmplike.disabled = true;
                        if (response.chat_stat == 1) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = false;
                        } else if (response.chat_stat == 0) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = true;
                        } else if (response.chat_stat == 2) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = true;
                            var tmplike = document.getElementById(user + "_likebtn");
                            tmplike.disabled = true;
                        }
                        var temp = String("you blocked: " + user);
                        console.log(temp);
                    } else if (tmpblock.innerHTML == "unblock") {
                        tmpblock.innerHTML = "block";
                        var tmplike = document.getElementById(user + "_likebtn");
                        tmplike.disabled = false;
                        if (response.chat_stat == 1) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = false;
                        } else if (response.chat_stat == 0) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = true;
                        } else if (response.chat_stat == 2) {
                            var tmpchat = document.getElementById(user + "_chatbtn");
                            tmpchat.disabled = true;
                            var tmplike = document.getElementById(user + "_likebtn");
                            tmplike.disabled = true;
                        }
                        var temp = String("you unblocked: " + user);
                        console.log(temp);
                    }
                }
            });
        }
    }
}

function report_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var temp = String("you reported: " + user);
            console.log(temp);
            let data = "reported=" + user;
            ajax_post("report_email.php", data, function(httpRequest) {
                let response = JSON.parse(httpRequest.responseText);
                displayError(response.statusMsg);
            });
        }
    }
}

// Show users at home
var httpRequest = new XMLHttpRequest();
httpRequest.addEventListener("error", function(event) {
    console.log("An error has occured. ERROR : " + event.message);
});
httpRequest.addEventListener("readystatechange", function() {
    if (httpRequest.readyState == 4 && httpRequest.status == 200) {
        let response = JSON.parse(httpRequest.responseText);
        if (response.status == false) {
            displayError(response.statusMsg);
        } else if (response.users_array.length == 0) {
            displayError("<br /><p class='info'>There are currently no other users signed up, sorry :(</p><br /><br />")
        } else {
            var profile_list = document.getElementById("profile_list");
            for (var key in response.users_array) {
                var mainD = document.createElement("div");
                mainD.id = response.users_array[key]['username'];
                mainD.style.height = "340px";
                mainD.style.width = "320px";
                mainD.style.float = "left";
                mainD.style.border = "5px solid #21181D";
                if (response.users_array[key]['gender'] == 'male') {
                    mainD.style.backgroundColor = "rgba(33,158,242, 0.6)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    mainD.style.backgroundColor = "rgba(187,58,242, 0.6)";
                } else {
                    mainD.style.backgroundColor = "rgba(112,163,1, 0.6)";
                }


                var pro_pic = document.createElement("img");
                pro_pic.src = response.users_array[key]['pic_path_and_name'];
                pro_pic.style.height = "180px";
                pro_pic.style.width = "180px";
                mainD.appendChild(pro_pic);

                var view_btn = document.createElement("button");
                view_btn.innerHTML = "view";
                view_btn.id = response.users_array[key]['username'] + "_viewbtn";
                view_btn.style.height = "70px";
                view_btn.style.width = "130px";
                view_btn.style.float = "right";
                if (response.users_array[key]['gender'] == 'male') {
                    view_btn.style.color = "rgb(33,158,242)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    view_btn.style.color = "rgb(187,58,242)";
                } else {
                    view_btn.style.color = "rgb(112,163,1)";
                }
                view_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                view_btn.style.fontFamily = "Chewy";
                view_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    view_user(this.id);
                });
                if (response.users_array[key]['blocked'].includes(response.logged_on_user)) {
                    view_btn.disabled = true;
                }
                mainD.appendChild(view_btn);

                var like_btn = document.createElement("button");
                if (response.users_array[key]['who_liked'].includes(response.logged_on_user)) {
                    like_btn.innerHTML = "unlike";
                } else {
                    like_btn.innerHTML = "like";
                }
                like_btn.id = response.users_array[key]['username'] + "_likebtn";
                like_btn.style.height = "25px";
                like_btn.style.width = "130px";
                like_btn.style.float = "right";
                if (response.users_array[key]['gender'] == 'male') {
                    like_btn.style.color = "rgb(33,158,242)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    like_btn.style.color = "rgb(187,58,242)";
                } else {
                    like_btn.style.color = "rgb(112,163,1)";
                }
                if (response.own_user_pro_pic == "" || !response.own_user_pro_pic || response.users_array[key]['who_blocked'].includes(response.logged_on_user) || response.users_array[key]['blocked'].includes(response.logged_on_user))
                    like_btn.disabled = true;
                like_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                like_btn.style.fontFamily = "Chewy";
                like_btn.style.borderRadius = "20px";
                like_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    like_user(this.id);
                });
                if (response.users_array[key]['blocked'].includes(response.logged_on_user)) {
                    like_btn.disabled = true;
                }
                mainD.appendChild(like_btn);

                var text = document.createElement("div");
                text.innerHTML = response.users_array[key]['fname'] + " " + response.users_array[key]['lname'] + ", " + response.users_array[key]['age'];
                text.style.color = "black";
                text.style.fontWeight = "bold";
                mainD.appendChild(text);

                var moretext = document.createElement("div");
                var fame = Number(response.users_array[key]['likes']) + Number(response.users_array[key]['views']);
                moretext.innerHTML = "likes: " + response.users_array[key]['likes'] + " | views: " + response.users_array[key]['views'] + "<br>Fame rating: " + fame;
                moretext.style.color = "black";
                mainD.appendChild(moretext);

                var chat_btn = document.createElement("button");
                chat_btn.innerHTML = "chat";
                chat_btn.id = response.users_array[key]['username'] + "_chatbtn";
                chat_btn.style.height = "25px";
                chat_btn.style.width = "130px";
                chat_btn.style.float = "right";
                if (response.users_array[key]['gender'] == 'male') {
                    chat_btn.style.color = "rgb(33,158,242)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    chat_btn.style.color = "rgb(187,58,242)";
                } else {
                    chat_btn.style.color = "rgb(112,163,1)";
                }
                chat_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                chat_btn.style.fontFamily = "Chewy";
                chat_btn.style.borderRadius = "20px";
                chat_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    chat_user(this.id);
                });
                if (!response.users_array[key]['who_liked'].includes(response.logged_on_user) || response.users_array[key]['who_blocked'].includes(response.logged_on_user) || response.users_array[key]['blocked'].includes(response.logged_on_user))
                    chat_btn.disabled = true;
                mainD.appendChild(chat_btn);

                var br1 = document.createElement("br");
                mainD.appendChild(br1);
                var br2 = document.createElement("br");
                mainD.appendChild(br2);

                var block_btn = document.createElement("button");
                block_btn.id = response.users_array[key]['username'] + "_blockbtn";
                if (response.users_array[key]['who_blocked'].includes(response.logged_on_user)) {
                    block_btn.innerHTML = "unblock";
                } else {
                    block_btn.innerHTML = "block";
                }
                block_btn.style.height = "25px";
                block_btn.style.width = "130px";
                block_btn.style.float = "right";
                if (response.users_array[key]['gender'] == 'male') {
                    block_btn.style.color = "rgb(33,158,242)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    block_btn.style.color = "rgb(187,58,242)";
                } else {
                    block_btn.style.color = "rgb(112,163,1)";
                }
                block_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                block_btn.style.fontFamily = "Chewy";
                block_btn.style.borderRadius = "20px";
                block_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    block_user(this.id);
                });
                mainD.appendChild(block_btn);

                var report_btn = document.createElement("button");
                report_btn.innerHTML = "report user as fake";
                report_btn.id = response.users_array[key]['username'] + "_reportbtn";
                report_btn.style.height = "25px";
                report_btn.style.width = "130px";
                report_btn.style.float = "right";
                if (response.users_array[key]['gender'] == 'male') {
                    report_btn.style.color = "rgb(33,158,242)";
                } else if (response.users_array[key]['gender'] == 'female') {
                    report_btn.style.color = "rgb(187,58,242)";
                } else {
                    report_btn.style.color = "rgb(112,163,1)";
                }
                report_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                report_btn.style.fontFamily = "Chewy";
                report_btn.style.borderRadius = "20px";
                report_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    report_user(this.id);
                });
                mainD.appendChild(report_btn);

                profile_list.appendChild(mainD);
            }
        }
    }
});
httpRequest.open("POST", "get_profiles.php", true);
httpRequest.send();
