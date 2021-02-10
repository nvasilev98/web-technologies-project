<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>
<style>
* {
  box-sizing: border-box;
}

body {
  background-color: #f1f1f1;
}

#createForm {
  background-color: #ffffff;
  margin: 100px auto;
  font-family: Raleway;
  padding: 40px;
  width: 70%;
  min-width: 300px;
}

h1 {
  text-align: center;  
}

input {
  padding: 10px;
  width: 100%;
  font-size: 17px;
  font-family: Raleway;
  border: 1px solid #aaaaaa;
}

input.invalid {
  background-color: #ffdddd;
}

.tab {
  display: none;
}

button {
  background-color: #4CAF50;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  font-size: 17px;
  font-family: Raleway;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#prevBtn {
  background-color: #bbbbbb;
}

.step {
  height: 15px;
  width: 15px;
  margin: 0 2px;
  background-color: #bbbbbb;
  border: none;  
  border-radius: 50%;
  display: inline-block;
  opacity: 0.5;
}

.step.active {
  opacity: 1;
}
.step.finish {
  background-color: #4CAF50;
}
</style>
<body>

<!--   STEP 1 - General   -->

<form id="createForm">
<div class="tab"> General:
    <div>
        <label for="name"> Name of the project:
            <input type="text" name="name" id="name">
        </label>
    </div>
    <div>
        <label for="php-version"> PHP Version
            <select name="php-version" id="php-version">
                <option value="8">8</option>
                <option value="7.4">7.4</option>
                <option value="7.3">7.3</option>
                <option value="7">7</option>
                <option value="5.6">5.6</option>
            </select>
        </label>
    </div>
    <div>
        <label for="apache-version"> Apache Version:
            <select name="apache-version" id="apache-version">
                <option value="2.4">2.4</option>
                <option value="2.2">2.2</option>
                <option value="2">2</option>
            </select>
        </label>
    </div>
    <div>
        <label for="mysql-version"> MySQL Version:
            <select name="mysql-version" id="mysql-version">
                <option value="8">8</option>
                <option value="5.7">5.7</option>
                <option value="5.6">5.6</option>
                <option value="5">5</option>
            </select>
        </label>
    </div>
</div>

<!--   STEP 2 - Apache   -->

<div class="tab"> Apache:
    <div>
        <label for="host"> Hostname:
            <input type="text" name="host" id="host">
        </label>
    </div>

    <div>
        <label for="port"> Port:
            <input type="text" name="port" id="port">
        </label>
    </div>

    <div>
        <label for="error-log-dir"> Error log directory:
            <input type="text" name="error-log-dir" id="error-log-dir">
        </label>
    </div>

    <div>
        <label for="custom-log-dir"> Error log directory:
            <input type="text" name="custom-log-dir" id="custom-log-dir">
        </label>
    </div>
</div>

<!--   STEP 3 - MySQL  -->

<div class="tab"> MySql:
    <div>
        <label for="mysql-user"> MySQL User:
            <input type="text" name="mysql-user" id="mysql-user">
        </label>
    </div>
    <div>
        <label for="mysql-password"> MySQL Password:
            <input type="text" name="mysql-password" id="mysql-password">
        </label>
    </div>
    <div>
        <label for="mysql-root"> MySQL Root Password:
            <input type="text" name="mysql-root" id="mysql-root">
        </label>
    </div>
    <!-- <div>
        <label for="env-file"> Configuration file:
            <input type="file" name="env-file" id="env-file">
        </label>
    </div> -->
    <div>
        <label for="sql-script-file"> SQL Script:
            <input type="file" name="sql-script-file" id="sql-script-file">
        </label>
    </div>
</div>

<div style="overflow:auto;">
    <div style="float:right;">
      <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
      <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
    </div>
  </div>
  <div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
  </div>
</form>
        </body>
</html>
<script src="js/stepper.js"></script>