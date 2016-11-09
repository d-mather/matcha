"use strict";

// Timeout variables for error messages
var addClass_timeout, removeError_timeout;

window.onload = function() {
    /* --- Events Handlers --- */

    // Submit event for image upload form
    let imageUploadForm = document.querySelector("#imageUploadForm");
    if (imageUploadForm) {
        imageUploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            ajax_upload_image(imageUploadForm);
        });
    }

    //For other general forms
    let modifyForm = document.querySelector("#modifyForm");
    if (modifyForm) {
        modifyForm.addEventListener('submit', function(e) {
            e.preventDefault();
            modify_account(modifyForm);
        });
    }

    let delAccForm = document.querySelector("#delAccForm");
    if (delAccForm) {
        delAccForm.addEventListener('submit', function(e) {
            e.preventDefault();
            del_account(delAccForm);
        });
    }

    let signin = document.querySelector("#signin");
    if (signin) {
        signin.addEventListener('submit', function(e) {
            e.preventDefault();
            signin_account(signin);
        });
    }

    let pwdreset = document.querySelector("#pwdreset");
    if (pwdreset) {
        pwdreset.addEventListener('submit', function(e) {
            e.preventDefault();
            pwdreset_account(pwdreset);
        });
    }

    let signup = document.querySelector("#signup");
    if (signup) {
        signup.addEventListener('submit', function(e) {
            e.preventDefault();
            create_account(signup);
        });
    }

    let forgot_psswd_form = document.querySelector("#forgot_psswd_form");
    if (forgot_psswd_form) {
        forgot_psswd_form.addEventListener('submit', function(e) {
            e.preventDefault();
            forgot_psswd_form_account(forgot_psswd_form);
        });
    }

    let profile = document.querySelector("#profile");
    if (profile) {
        profile.addEventListener('submit', function(e) {
            e.preventDefault();
            profiles_form(profile);
        });
    }

}

/* --- FUNCTION DEFINITIONS --- */
function del_account(form) {
    let delAccPwd = encodeURIComponent(document.getElementById("delAccPwd").value);

    let data = "delAccPwd=" + delAccPwd;

    ajax_post("deleteacc.php", data, function(httpRequest) {
        let response = JSON.parse(httpRequest.responseText);
        if (response.status === true) {
            displayError(response.statusMsg);
            setTimeout(function() {
                window.location = "index.php";
            }, 1000);
        } else {
            displayError(response.statusMsg);
        }
    });
}

function forgot_psswd_form_account(form) {
    displayError('<p class="info">An email has been sent</p>');
}

function modify_account(form) {
    let oldpw = encodeURIComponent(document.getElementById("oldpw").value);
    let newpw = encodeURIComponent(document.getElementById("newpw").value);

    let data = "oldpw=" + oldpw + "&newpw=" + newpw;

    ajax_post("modif.php", data, function(httpRequest) {
        let response = JSON.parse(httpRequest.responseText);
        displayError(response.statusMsg);
    });
}

function pwdreset_account(form) {
    let fuser = encodeURIComponent(document.getElementById("fuser").value);
    let femail = encodeURIComponent(document.getElementById("femail").value);
    let fpwd1 = encodeURIComponent(document.getElementById("fpwd1").value);
    let fpwd2 = encodeURIComponent(document.getElementById("fpwd2").value);

    let data = "fuser=" + fuser + "&femail=" + femail + "&fpwd1=" + fpwd1 + "&fpwd2=" + fpwd2;

    ajax_post("fpassword.php", data, function(httpRequest) {
        let response = JSON.parse(httpRequest.responseText);
        if (response.status == true) {
            displayError(response.statusMsg);
            setTimeout(function() {
                window.location = "index.php";
            }, 1000);
        } else {
            displayError(response.statusMsg);
        }
    });
}

function signin_account(form) {
    let userin = encodeURIComponent(document.getElementById("userin").value);
    let pwdin = encodeURIComponent(document.getElementById("pwdin").value);

    let data = "userin=" + userin + "&pwdin=" + pwdin;

    ajax_post("login.php", data, function(httpRequest) {
        let response = JSON.parse(httpRequest.responseText);
        if (response.status === false) {
            displayError(response.statusMsg);
        } else if (response['meta'] == 1) {
            displayError(response.statusMsg);
            setTimeout(function() {
                window.location = "setup_profile.php";
            }, 1000);
        } else {
            displayError(response.statusMsg);
            setTimeout(function() {
                window.location = "home.php";
            }, 1000);
        }
    });
}

function create_account(form) {
    let userup = encodeURIComponent(document.getElementById("userup").value);
    let emailup = encodeURIComponent(document.getElementById("emailup").value);
    let pwd1up = encodeURIComponent(document.getElementById("pwd1up").value);
    let pwd2up = encodeURIComponent(document.getElementById("pwd2up").value);
    let fnameup = encodeURIComponent(document.getElementById("fnameup").value);
    let lnameup = encodeURIComponent(document.getElementById("lnameup").value);

    let data = "userup=" + userup + "&emailup=" + emailup + "&pwd1up=" + pwd1up + "&pwd2up=" + pwd2up + "&fnameup=" + fnameup + "&lnameup=" + lnameup;


    ajax_post("create.php", data, function(httpRequest) {
        let response = JSON.parse(httpRequest.responseText);
        if (response['status'] === false) {
            displayError(response.statusMsg);
        } else {
            displayError(response.statusMsg);
        }
    });
}

function profiles_form(form) {
    var tmp_lat = document.getElementById("latitude").value;
    var tmp_long = document.getElementById("longitude").value;
    if (isNaN(tmp_lat) || isNaN(tmp_long)) {
        displayError("<p class='warning'>latitude and longitude coordinates must be valid!</p>");
        return;
    }

    var fname = encodeURIComponent(document.getElementById("fname").value);
    var lname = encodeURIComponent(document.getElementById("lname").value);
    var email = encodeURIComponent(document.getElementById("email").value);
    var genderm = encodeURIComponent(document.getElementById("genderm").checked);
    var genderf = encodeURIComponent(document.getElementById("genderf").checked);
    var sex_prefm = encodeURIComponent(document.getElementById("sex_prefm").checked);
    var sex_preff = encodeURIComponent(document.getElementById("sex_preff").checked);
    var age = encodeURIComponent(document.getElementById("age").value);
    var biography = encodeURIComponent(document.getElementById("biography").value);
    var interests = encodeURIComponent(document.getElementById("interests").value);
    var lat = encodeURIComponent(document.getElementById("latitude").value);
    var long = encodeURIComponent(document.getElementById("longitude").value);

    if (lat == "" || long == "") {
        var hidden = encodeURIComponent("yes");
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                    var lati = encodeURIComponent(position.coords.latitude);
                    var longi = encodeURIComponent(position.coords.longitude);
                    save_to_profile(lati, longi, hidden);
                },
                function(error) {
                    switch (error.code) {
                        case error.PERMISSION_DENIED:
                            console.log("User denied the request for Geolocation.");
                            break;
                        case error.POSITION_UNAVAILABLE:
                            console.log("Location information is unavailable.");
                            break;
                        case error.TIMEOUT:
                            console.log("The request to get user location timed out.");
                            break;
                        case error.UNKNOWN_ERROR:
                            console.log("An unknown error occurred.");
                            break;
                    }
                    save_to_profile("", "", hidden);
                });
        } else {
            save_to_profile("", "", hidden);
        }
    } else {
        var hidden = encodeURIComponent("no");
        save_to_profile(lat, long, hidden);
    }

    function save_to_profile(lat, long, hidden) {
        var data = "fname=" + fname + "&lname=" + lname + "&email=" + email + "&genderm=" + genderm +
            "&genderf=" + genderf + "&sex_prefm=" + sex_prefm + "&sex_preff=" + sex_preff +
            "&age=" + age + "&biography=" + biography + "&interests=" + interests +
            "&latitude=" + lat + "&longitude=" + long + "&hidden=" + hidden;

        ajax_post("profiles.php", data, function(httpRequest) {
            let response = JSON.parse(httpRequest.responseText);
            if (response.status === false) {
                displayError(response.statusMsg);
            } else {
                displayError(response.statusMsg);
                setTimeout(function() {
                    window.location = "home.php";
                }, 1000);
            }
        });
    }
}

function get_coords() {
    var lat = document.getElementById("latitude");
    var long = document.getElementById("longitude");

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(showPosition, showError);
    } else {
        displayError("<p class='danger'>Geolocation is not supported by this browser.</p>");
    }

    function showPosition(position) {
        lat.value = position.coords.latitude;
        long.value = position.coords.longitude;

        //    var latlon = position.coords.latitude + "," + position.coords.longitude;

        //    var img_url = "https://maps.googleapis.com/maps/api/staticmap?center=" + latlon + "&zoom=14&size=400x300&sensor=false";
        //    document.getElementById("mapholder").innerHTML = "<img src='" + img_url + "'>";
    }

    function showError(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                displayError("<p class='warning'>User denied the request for Geolocation.<?p>");
                break;
            case error.POSITION_UNAVAILABLE:
                displayError("<p class='warning'>Location information is unavailable.<?p>");
                break;
            case error.TIMEOUT:
                displayError("<p class='warning'>The request to get user location timed out.<?p>");
                break;
            case error.UNKNOWN_ERROR:
                displayError("<p class='warning'>An unknown error occurred.<?p>");
                break;
        }
    }
}

// Add class to element
function addClass(el, className) {
    if (el.classList && !el.classList.contains(className)) {
        el.classList.add(className);
    }
}

// Remove class from element
function removeClass(el, className) {
    if (el.classList && el.classList.contains(className)) {
        el.classList.remove(className);
    }
}

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

// Function to display errors
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

    // Remove html. i.e. Get text only
    let tmp = document.createElement("div");
    tmp.innerHTML = errMsg;
    errMsg = tmp.textContent || tmp.innerText || "No error message found.";

    console.log(errMsg);
}

function observeErrors(errorDiv) {

    // Vendor specific aliases for 'MutationObserver'
    var MutationObserver = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver;

    // create an observer instance
    var observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            var newNodes = mutation.addedNodes;
            //        console.log(newNodes);
            addClass_timeout = setTimeout(function() {
                for (let i = 0; i < newNodes.length; ++i) {
                    addClass(newNodes[i], "scale-out");
                    //    newNodes[i].className += " scale-out";
                }
                removeError_timeout = setTimeout(function() {
                    while (errorDiv.children.length) {
                        errorDiv.removeChild(errorDiv.children[0]);
                    }
                }, 2000);
            }, 10000);
        })
    });

    // configuration of the observer:
    var config = {
        attributes: true,
        childList: true,
        characterData: true
    };

    // pass in the target node, as well as the observer options
    observer.observe(errorDiv, config);
}

// Function for uploading users images
function ajax_upload_image(uploadForm) {
    var httpRequest = new XMLHttpRequest(),
        formdata = new FormData(uploadForm);

    httpRequest.upload.addEventListener("progress", uploadProgress);
    httpRequest.upload.addEventListener("loadstart", uploadStarted);
    httpRequest.upload.addEventListener("load", uploadSuccess);
    httpRequest.upload.addEventListener("loadend", uploadFinished);
    httpRequest.upload.addEventListener("abort", uploadAborted);
    httpRequest.upload.addEventListener("error", uploadError);
    document.getElementById("cancelUploadBtn").addEventListener("click", cancelUpload)

    try {
        httpRequest.open("POST", "upload.php", true);
        httpRequest.send(formdata);
    } catch (e) {
        displayError("<p class=\"info\">ajax send error : " + e + "</p>");
    }

    function uploadProgress(event) {
        if (event.lengthComputable) {
            let percent = event.loaded / event.total * 100;
            document.getElementById("progress").setAttribute("value", percent.toFixed(1));
            document.querySelector("progress[value]").setAttribute("data-content", percent.toFixed(1) + "%");

        }
    }

    function uploadStarted(event) {
        document.querySelector("#imageUploadForm .image-upload-fields").className += " hidden absolute";
        let items = document.querySelector("#imageUploadForm").children;
        for (let item of items) {
            if (item.classList.contains("during-upload")) {
                item.setAttribute("style", "display: inline-block;");
            }
        }
    }

    function uploadSuccess(event) {
        httpRequest.onreadystatechange = function() {
            if (httpRequest.readyState == 4 && httpRequest.status == 200) {
                displayError(httpRequest.responseText);

            }
        };
    }

    function uploadFinished(event) {
        document.querySelector("#imageUploadForm .image-upload-fields.hidden").className = "image-upload-fields";
        document.getElementById("progress").value = "0";
        document.querySelector("progress[value]").setAttribute("data-content", "");
        let items = document.querySelector("#imageUploadForm").children;
        for (let item of items) {
            if (item.classList.contains("during-upload")) {
                item.removeAttribute("style");
            }
        }
    }

    function uploadAborted(event) {
        displayError("<p class=\"warning\">User aborted file upload or the connection was lost. ERROR : " + event.message + "</p>");
    }

    function uploadError(event) {
        displayError("<p class=\"danger\">An error has occured. ERROR : " + event.message + "</p>");
    }

    function cancelUpload() {
        httpRequest.abort();
    }

}
