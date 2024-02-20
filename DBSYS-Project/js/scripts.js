//const urlBase = "138.197.67.189";
// const urlBase = "chompersphonebook.xyz";
const urlBase = "64.23.175.26";
const extension = "php";

var userId = 0;
let userFirstName = "";
let userLastName = "";

let contactSearchList = [];
let contactList = [];

function doLogin() {
  userId = 0;
  firstName = "";
  lastName = "";

  let login = document.getElementById("loginName").value;
  let password = document.getElementById("loginPassword").value;
  console.log("user/pass= " + login);
  console.log(password);
  //	var hash = md5( password );

  document.getElementById("loginResult").innerHTML = "";

  let tmp = { login: login, password: password };
  //	var tmp = {login:login,password:hash};
  let jsonPayload = JSON.stringify(tmp);
  console.log("jsonPayload= " + jsonPayload);

  let url = "http://" + urlBase + "/LAMPAPI/Login." + extension;
  //let url = '/var/www/html/LAMPAPI/Login.' + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = async function () {
      if (this.readyState == 4 && this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        userId = jsonObject.id;
        userFirstName = jsonObject.firstName;
        userLastName = jsonObject.lastName;
        sessionStorage.setItem("userId", userId);
        sessionStorage.setItem("userFirstName", userFirstName);
        sessionStorage.setItem("userLastName", userLastName);

        if (userId < 1) {
          document.getElementById("loginResult").innerHTML =
            "User/Password combination incorrect";
          return;
        }

        saveCookie();
        const getContactsResult = await getContacts("");
        console.log(getContactsResult);
        //let contacts = JSON.stringify(contactList);
        //sessionStorage.setItem('contactList', contacts);
        window.location.href = "landing.html";
        console.log("cookie on landing= " + document.cookie);
      }
    };
    xhr.send(jsonPayload);
  } catch (err) {
    document.getElementById("loginResult").innerHTML = err.message;
  }
}

function saveCookie() {
  let minutes = 500;
  let date = new Date();
  date.setTime(date.getTime() + minutes * 60 * 1000);
  document.cookie =
    "firstName=" +
    firstName +
    ",lastName=" +
    lastName +
    ",userId=" +
    userId +
    ";expires=" +
    date.toGMTString();
  console.log("cookie= " + document.cookie);
}

function readCookie() {
  userId = -1;
  let data = document.cookie;
  let splits = data.split(",");
  for (var i = 0; i < splits.length; i++) {
    let thisOne = splits[i].trim();
    let tokens = thisOne.split("=");
    if (tokens[0] == "firstName") {
      firstName = tokens[1];
    } else if (tokens[0] == "lastName") {
      lastName = tokens[1];
    } else if (tokens[0] == "userId") {
      userId = parseInt(tokens[1].trim());
    }
  }

  if (userId < 0) {
    window.location.href = "index.html";
  } else {
    //document.getElementById("userName").innerHTML = "Logged in as " + firstName + " " + lastName;
  }
}

function doRegister() {
  // reset required red borders
  document.getElementById("registerFirstName").className = "ele";
  document.getElementById("registerLastName").className = "ele";
  document.getElementById("registerLogin").className = "ele";
  document.getElementById("registerPassword").className = "ele";

  // collect values from form
  let firstName = document.getElementById("registerFirstName").value;
  let lastName = document.getElementById("registerLastName").value;
  let login = document.getElementById("registerLogin").value;
  let password = document.getElementById("registerPassword").value;

  // create json payload
  let tmp = {
    login: login,
    password: password,
    firstName: firstName,
    lastName: lastName,
  };

  // resets fields
  userId = 0;
  firstName = "";
  lastName = "";
  document.getElementById("registerResult").innerHTML = "";

  let jsonPayload = JSON.stringify(tmp);
  console.log("jsonPayload= " + jsonPayload);

  let url = "http://" + urlBase + "/LAMPAPI/Register." + extension;

  let xhr = new XMLHttpRequest();
  xhr.open("POST", url, true);
  xhr.setRequestHeader("Content-type", "application/json; charset=UTF-8");
  try {
    xhr.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        let jsonObject = JSON.parse(xhr.responseText);
        userId = jsonObject.id;

        if (userId < 1) {
          let err = jsonObject.error;
          document.getElementById("registerResult").innerHTML = err;
          return;
        }

        window.location.reload();
      }
    };
    xhr.send(jsonPayload);
  } catch (err) {
    document.getElementById("loginResult").innerHTML = err.message;
  }
}

function openPopup(popupId) {
  document.getElementById(popupId).style.display = "flex";
}

function closePopup(popupId) {
  document.getElementById(popupId).style.display = "none";
}
