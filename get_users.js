function view_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var temp = String("you viewed: " + user);
            console.log(temp);
        }
    }
}

function like_user(tmp) {
    var childs = document.querySelector('#profile_list').children;
    for (var i = 0; i < childs.length; ++i) {
        var child = childs[i];
        var user = tmp.split('_')[0];
        if (user == child.id) {
            var temp = String("you liked: " + user);
            console.log(temp);
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
                mainD.appendChild(view_btn);

                var like_btn = document.createElement("button");
                like_btn.innerHTML = "like";
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
                like_btn.style.backgroundColor = "rgba(33, 24, 29, 0.8)";
                like_btn.style.fontFamily = "Chewy";
                like_btn.style.borderRadius = "20px";
                like_btn.addEventListener("click", function(event) {
                    event.preventDefault();
                    like_user(this.id);
                });
                mainD.appendChild(like_btn);


                var text = document.createElement("div");
                text.innerHTML = response.users_array[key]['fname'] + " " + response.users_array[key]['lname'] + ", " + response.users_array[key]['age'];
                text.style.color = "black";
                text.style.fontWeight = "bold";
                mainD.appendChild(text);

                var moretext = document.createElement("div");
                moretext.innerHTML = "likes: " + response.users_array[key]['likes'] + " | views: " + response.users_array[key]['views'];
                moretext.style.color = "black";
                mainD.appendChild(moretext);






                profile_list.appendChild(mainD);
            }
        }
    }
});
httpRequest.open("POST", "get_profiles.php", true);
httpRequest.send();
