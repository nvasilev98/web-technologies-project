<?php
    include 'header.php';
?>
<form id="form" method="post" action="generate.php">
    <div class="w3-content w3-padding" style="max-width:1564px">
        <div class="tab w3-container w3-padding-32">
            <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> General</h3>

            <div>
                <label for="name"> Name of the project:
                    <input class="w3-input w3-section w3-border" type="text" name="name" id="name">
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

            <div>
                <label for="apache-version">
                    Apache Version:
                    <select class="w3-input w3-section w3-border" name="apache-version" id="apache-version">
                        <option value="2.4">2.4</option>
                        <option value="2.2">2.2</option>
                        <option value="2">2</option>
                    </select>
                </label>
            </div>

            <div>
                <label for="nginx-version">
                    Nginx Version:
                    <select class="w3-input w3-section w3-border" name="nginx-version" id="nginx-version">
                        <option value="2.4">2.4</option>
                        <option value="2.2">2.2</option>
                        <option value="2">2</option>
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

        </div>

        <div class="tab w3-container w3-padding-32">

            <div id="apacheDiv">
                <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> Apache</h3>
                <div>
                    <label for="apache-host"> Hostname:
                        <input class="w3-input w3-section w3-border" type="text" name="apache-host" id="apache-host">
                    </label>
                </div>

                <div>
                    <label for="apache-port"> Port:
                        <input class="w3-input w3-section w3-border" type="text" name="apache-port" id="apache-port">
                    </label>
                </div>

                <div>
                    <label for="apache-error-log-dir"> Error log directory:
                        <input class="w3-input w3-section w3-border" type="text" name="apache-error-log-dir"
                            id="apache-error-log-dir">
                    </label>
                </div>

                <div>
                    <label for="apache-custom-log-dir"> Error log directory:
                        <input class="w3-input w3-section w3-border" type="apache-text" name="apache-custom-log-dir"
                            id="apache-custom-log-dir">
                    </label>
                </div>

            </div>

            <div id="nginxDiv">

                <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> Nginx</h3>
                <div>
                    <label for="nginx-host"> Hostname:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-host" id="nginx-host">
                    </label>
                </div>

                <div>
                    <label for="nginx-port"> Port:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-port" id="nginx-port">
                    </label>
                </div>

                <div>
                    <label for="nginx-error-log-dir"> Error log directory:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-error-log-dir"
                            id="nginx-error-log-dir">
                    </label>
                </div>

                <div>
                    <label for="nginx-custom-log-dir"> Error log directory:
                        <input class="w3-input w3-section w3-border" type="text" name="nginx-custom-log-dir"
                            id="nginx-custom-log-dir">
                    </label>
                </div>

            </div>
        </div>

        <div class="tab w3-container w3-padding-32">
            <h3 class="w3-border-bottom w3-border-light-grey w3-padding-16"> MySql</h3>
            <label for="configuration-type">
                Configuration type:
                <select class="w3-input w3-section w3-border" name="configuration-type" id="configuration-type"
                        onchange="configurationTypeFunc()">
                    <option value="input-fields">Input fields</option>
                    <option value="configuration-file">Configuration File</option>
                </select>
            </label>
            <div id="fieldsDiv">
                <div>
                    <label for="mysql-user" id="mysql-user-label"> MySQL User:
                        <input class="w3-input w3-section w3-border" type="text" name="mysql-user" id="mysql-user">
                    </label>
                </div>
                <div>
                    <label for="mysql-password" id="mysql-password-label"> MySQL Password:
                        <input class="w3-input w3-section w3-border" type="text" name="mysql-password" id="mysql-password">
                    </label>
                </div>
                <div>
                    <label for="mysql-root" id="mysql-root-label"> MySQL Root Password:
                        <input class="w3-input w3-section w3-border" type="text" name="mysql-root" id="mysql-root">
                    </label>
                </div>
            </div>
            <div id="fileDiv">
                <label for="env-file" id="env-file-label"> Configuration file:
                    <input class="w3-input w3-section w3-border" type="file" name="env-file" id="env-file">
                </label>
            </div>
            <div>
                <label for="sql-script-file"> SQL Script:
                    <input class="w3-input w3-section w3-border" type="file" name="sql-script-file"
                           id="sql-script-file">
                </label>
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
    <span class="step"></span>
</div>
<script src="../js/main.js"></script>
<script></script>