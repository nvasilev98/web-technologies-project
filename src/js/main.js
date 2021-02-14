var currentTab = 0;
showTab(currentTab);
configurationTypeFunc();
changeServer();

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    if (x[n]) {
        x[n].style.display = "block";
    }
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Download";
    } else {
        document.getElementById("nextBtn").innerHTML = "Next";
    }
    fixStepIndicator(n)
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    if (n == 1 && !validateForm()) return false;
    x[currentTab].style.display = "none";
    currentTab = currentTab + n;
    if (currentTab >= x.length) {
        document.getElementById("form").submit();
        return false;
    }
    showTab(currentTab);
}

function validateForm() {
    var x, y, i, valid = true;
    x = document.getElementsByClassName("tab");
    y = x[currentTab].getElementsByTagName("input");
    for (i = 0; i < y.length; i++) {
        if (y[i].value == "" && y[i].style.display != "none") {
            y[i].className += " invalid";
            valid = false;
        }
    }
    if (valid) {
        document.getElementsByClassName("step")[currentTab].className += " finish";
    }
    return valid;
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "");
    }
    x[n].className += " active";
}

function configurationTypeFunc() {
    if (document.getElementById("configuration-type").value == "configuration-file") {
        allDescendantsDeactivate(document.getElementById("fieldsDiv"));
        allDescendantsActivate(document.getElementById("fileDiv"));
    } else {
        allDescendantsDeactivate(document.getElementById("fileDiv"));
        allDescendantsActivate(document.getElementById("fieldsDiv"));
    }
}

function changeServer() {
    console.log("ASDdsasad");
    if (document.getElementById("server").value == "apache") {
        allDescendantsDeactivate(document.getElementById("nginxDiv"));
        allDescendantsDeactivate(document.getElementById("nginx-version-div"));
        allDescendantsActivate(document.getElementById("apacheDiv"));
        allDescendantsActivate(document.getElementById("apache-version-div"));
    } else {
        allDescendantsDeactivate(document.getElementById("apacheDiv"));
        allDescendantsDeactivate(document.getElementById("apache-version-div"));
        allDescendantsActivate(document.getElementById("nginxDiv"));
        allDescendantsActivate(document.getElementById("nginx-version-div"));

    }
}

function allDescendantsActivate (node) {
    for (var i = 0; i < node.childNodes.length; i++) {
      var child = node.childNodes[i];
      allDescendantsActivate(child);
      if(child.style != undefined){
      child.style.display = "block";
      }
    }
}

function allDescendantsDeactivate (node) {
    for (var i = 0; i < node.childNodes.length; i++) {
      var child = node.childNodes[i];
      allDescendantsDeactivate(child);
      if(child.style != undefined){
      child.style.display = "none"
      }
    }
}

function edit(content) {
alert(JSON.stringify(content));
var path = window.location.pathname;
var newPath = path.substring(0, path.lastIndexOf('/') + 1) + "update.php";
window.location.href = newPath;
}