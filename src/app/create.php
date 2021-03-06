<?php
include 'header.php';
session_start();
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("location: login.php");
    exit;
}
?>
<h2 id="created" class="createdText" display="none">Your files were successfully created.</h2>
<form id="form" method="post" action="generate.php">
    <div class="w3-content w3-padding" style="max-width:1564px">
        <div class="tab w3-container w3-padding-32">
            <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> General</h3>

            <div>
                <label for="name"> Name of the project:
                    <input class="w3-input w3-section w3-border" required type="text" name="name" id="name"
                           onchange="validateRequired(this)">
                    <span id="name-required-error" class="error-msg"
                          style="display: none;">Project name is required!</span>
                </label>
            </div>

            <div>
                <label for="php-version">
                    PHP Version:
                    <select class="w3-input w3-section w3-border" name="php-version" id="php-version">
                        <option value="8">8</option>
                        <option value="7.4">7.4</option>
                        <option value="7.3">7.3</option>
                        <option value="7">7</option>
                        <option value="5.6">5.6</option>
                    </select>
                </label>
            </div>

            <div>
                <label for="server">
                    Server
                    <select class="w3-input w3-section w3-border" name="server" id="server" onchange="changeServer()">
                        <option value="apache">Apache</option>
                        <option value="nginx">Nginx</option>
                    </select>
            </div>

            <div id="apache-version-div">
                <label for="apache-version">
                    Apache Version:
                    <select class="w3-input w3-section w3-border" name="apache-version" id="apache-version">
                        <option value="2.4">2.4</option>
                        <option value="2.2">2.2</option>
                        <option value="2">2</option>
                    </select>
                </label>
            </div>

            <div id="nginx-version-div">
                <label for="nginx-version">
                    Nginx Version:
                    <select class="w3-input w3-section w3-border" name="nginx-version" id="nginx-version">
                        <option value="1.19">1.19</option>
                        <option value="1.18">1.18</option>
                        <option value="1.17">1.17</option>
                    </select>
                </label>
            </div>

            <div>
                <label for="mysql-version">
                    MySQL Version:
                    <select class="w3-input w3-section w3-border" name="mysql-version" id="mysql-version">
                        <option value="8">8</option>
                        <option value="5.7">5.7</option>
                        <option value="5.6">5.6</option>
                        <option value="5">5</option>
                    </select>
                </label>
            </div>

            <div>
                <label>NOTE: Your application code should be in APP_DIR/src/</label>
            </div>

        </div>

        <div class="tab w3-container w3-padding-32">

            <div id="apacheDiv">
                <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> Apache</h3>
                <div>
                    <label for="apache-host"> Hostname:
                        <input class="w3-input w3-section w3-border" required type="text" name="apache-host"
                               id="apache-host" onchange="validateRequired(this)">
                        <span id="apache-host-required-error" class="error-msg"
                              style="display: none;">Host is required!</span>
                    </label>
                </div>

                <div>
                    <label for="apache-port"> Port:
                        <input class="w3-input w3-section w3-border" type="number" min="0" max="65535"
                               name="apache-port" id="apache-port" required
                               onchange="validateRange(this); validateRequired(this)">
                        <span id="apache-port-required-error" class="error-msg"
                              style="display: none;">Port is required!</span>
                        <span id="apache-port-error" class="error-msg" style="display: none;">Port must be between 0 and 65635!</span>
                    </label>
                </div>

                <div>
                    <label for="apache-error-log-dir"> Error log file:
                        <input class="w3-input w3-section w3-border" type="text" name="apache-error-log-dir"
                               id="apache-error-log-dir" placeholder="If not provided, defaults to stderr"
                               onchange="validateDirectoryPattern(this)">
                        <span id="apache-error-log-dir-pattern-error" class="error-msg"
                              style="display: none;">The file should be part of a valid path and end as .log</span>
                    </label>
                </div>

                <div>
                    <label for="apache-custom-log-dir"> Custom log file:
                        <input class="w3-input w3-section w3-border" type="text" name="apache-custom-log-dir"
                               id="apache-custom-log-dir" placeholder="If not provided, defaults to stdout"
                               onchange="validateDirectoryPattern(this)">
                        <span id="apache-custom-log-dir-pattern-error" class="error-msg"
                              style="display: none;">The file should be part of a valid path and end as .log</span>
                    </label>
                </div>

            </div>

            <div id="nginxDiv">

                <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> Nginx</h3>
                <div>
                    <label for="nginx-host"> Hostname:
                        <input class="w3-input w3-section w3-border" required type="text" name="nginx-host"
                               id="nginx-host" onchange="validateRequired(this)">
                        <span id="nginx-host-required-error" class="error-msg"
                              style="display: none;">Host is required!</span>
                    </label>
                </div>

                <div>
                    <label for="nginx-port"> Port:
                        <input class="w3-input w3-section w3-border" type="number" min="0" max="65535" name="nginx-port"
                               id="nginx-port" required onchange="validateRange(this); validateRequired(this)">
                        <span id="nginx-port-error" class="error-msg" style="display: none;">Port must be between 0 and 65635!</span>
                        <span id="nginx-port-required-error" class="error-msg"
                              style="display: none;">Port is required!</span>
                    </label>
                </div>

                <div>
                    <label for="nginx-error-log-dir"> Error log file:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-error-log-dir"
                               id="nginx-error-log-dir"
                               placeholder="If not provided, defaults to /var/log/nginx/error.log"
                               onchange="validateDirectoryPattern(this)">
                        <span id="nginx-error-log-dir-pattern-error" class="error-msg"
                              style="display: none;">The file should be part of a valid path and end as .log</span>
                    </label>
                </div>

                <div>
                    <label for="nginx-custom-log-dir"> Access log file:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-custom-log-dir"
                               id="nginx-custom-log-dir"
                               placeholder="If not provided, defaults to /var/log/nginx/access.log"
                               onchange="validateDirectoryPattern(this)">
                        <span id="nginx-custom-log-dir-pattern-error" class="error-msg"
                              style="display: none;">The file should be part of a valid path and end as .log</span>
                    </label>
                </div>

                <div>
                    <label for="use-load-balancer"> Do you want to use Load Balancer?
                        <select class="w3-input w3-section w3-border" name="use-load-balancer" id="use-load-balancer"
                                onchange="useLB()">
                            <option value="TRUE">Yes</option>
                            <option value="FALSE">No</option>
                        </select>
                    </label>
                </div>
                <div id="instances">
                    <label for="server-count"> Number of instances:
                        <input class="w3-input w3-section w3-border" type="number" min="1" name="server-count"
                               id="server-count" placeholder="If not provided, defaults to 1"
                               onchange="validateRange(this)">
                        <span id="server-count-error" class="error-msg" style="display: none;">There should be at least 1 instance</span>
                    </label>
                </div>

            </div>
        </div>

        <div class="tab w3-container w3-padding-32">
            <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> MySql</h3>
            <div id="fieldsDiv">
                <div>
                    <label for="mysql-user" id="mysql-user-label"> MySQL User:
                        <input class="w3-input w3-section w3-border" required type="text" name="mysql-user"
                               id="mysql-user"
                               onchange="validateRequired(this)">
                        <span id="mysql-user-required-error" class="error-msg"
                              style="display: none;">User is required!</span>
                    </label>
                </div>
                <div>
                    <label for="mysql-password" id="mysql-password-label"> MySQL Password:
                        <input class="w3-input w3-section w3-border" required type="text" name="mysql-password"
                               id="mysql-password" onchange="validateRequired(this)">
                        <span id="mysql-password-required-error" class="error-msg"
                              style="display: none;">Password is required!</span>
                    </label>
                </div>
                <div>
                    <label for="mysql-root" id="mysql-root-label"> MySQL Root Password:
                        <input class="w3-input w3-section w3-border" required type="text" name="mysql-root"
                               id="mysql-root" onchange="validateRequired(this)">
                    </label>
                    <span id="mysql-root-required-error" class="error-msg"
                          style="display: none;">Root Password is required!</span>
                </div>
                <div>
                    <label>NOTE: If you want SQL script file to be executed on container startup please include it in
                        APP_DIR/scripts/</label>
                </div>
            </div>
        </div>
</form>

<div style="overflow:auto;">
    <div style="float:right;">
        <button class="w3-button w3-black w3-section" type="button" id="prevBtn" onclick="nextPrev(-1)">
            <i class="fa fa-paper-plane"></i> Previous
        </button>
        <button class="w3-button w3-black w3-section" type="button" id="nextBtn" onclick="nextPrev(1)">
            <i class="fa fa-paper-plane"></i> Next
        </button>
    </div>
</div>

<div style="text-align:center;margin-top:40px;">
    <span class="step"></span>
    <span class="step"></span>
    <span class="step"></span>
</div>
<script src="../js/main.js"></script>
<script></script>