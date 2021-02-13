var currentTab = 0;
showTab(currentTab);
configurationTypeFunc();

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";
    if (n == 0) {
        document.getElementById("prevBtn").style.display = "none";
    } else {
        document.getElementById("prevBtn").style.display = "inline";
    }
    if (n == (x.length - 1)) {
        document.getElementById("nextBtn").innerHTML = "Submit";
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
        //document.getElementById("createForm").submit();
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
        document.getElementById("mysql-user").style.display = "none";
        document.getElementById("mysql-password").style.display = "none";
        document.getElementById("mysql-root").style.display = "none";

        document.getElementById("mysql-user-label").style.display = "none";
        document.getElementById("mysql-password-label").style.display = "none";
        document.getElementById("mysql-root-label").style.display = "none";

        document.getElementById("env-file").style.display = "block";
        document.getElementById("env-file-label").style.display = "block";
    } else {
        document.getElementById("mysql-user").style.display = "block";
        document.getElementById("mysql-password").style.display = "block";
        document.getElementById("mysql-root").style.display = "block";

        document.getElementById("mysql-user-label").style.display = "block";
        document.getElementById("mysql-password-label").style.display = "block";
        document.getElementById("mysql-root-label").style.display = "block";

        document.getElementById("env-file").style.display = "none";
        document.getElementById("env-file-label").style.display = "none";
    }
}

function edit() {
var path = window.location.pathname;
var newPath = path.substring(0, path.lastIndexOf('/') + 1) + "update.php";
window.location.href = newPath;
}