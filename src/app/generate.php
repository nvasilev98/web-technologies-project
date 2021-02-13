<?php

const phpDockerfile =
    "FROM php:{{php-version}}-fpm
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install pdo pdo_mysql";

function generatePhpDockerfile($version)
{
    return str_replace("{{php-version}}", $version, phpDockerfile);
}

const apacheConf =
    "LoadModule deflate_module /usr/local/apache2/modules/mod_deflate.so
LoadModule proxy_module /usr/local/apache2/modules/mod_proxy.so
LoadModule proxy_fcgi_module /usr/local/apache2/modules/mod_proxy_fcgi.so

LISTEN {{port}}

ServerName {{server-name}}

<VirtualHost *:{{port}}>
    ServerName {{server-name}}
    ProxyPassMatch ^/(.*\.php(/.*)?)$ fcgi://php1:9000/var/www/html/$1
    DocumentRoot /var/www/html/
    <Directory /var/www/html/>
        DirectoryIndex index.php
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    CustomLog {{custom-log}} common
    ErrorLog {{error-log}}
</VirtualHost>";

function generateApacheConfFile($host, $port, $errorLog, $customLog) {
    if (isBlank($customLog)) {
        // stdout
        $customLog = "/proc/self/fd/1";
    }
    if (isBlank($errorLog)) {
        // stderr
        $errorLog = "/proc/self/fd/2";
    }

    $content = str_replace("{{server-name}}", $host, apacheConf);
    $content = str_replace("{{port}}", $port, $content);
    $content = str_replace("{{custom-log}}", $customLog, $content);
    $content = str_replace("{{error-log}}", $errorLog, $content);

    return $content;
}

const apacheDockerfile =
    "FROM httpd:{{apache-version}}-alpine
RUN apk update; \
    apk upgrade;

RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{error-log-dir}}' ] && mkdir -p {{error-log-dir}}\"]
RUN [\"/bin/bash\",\"-c\", \"[ ! -d '{{custom-log-dir}}' ] && mkdir -p {{custom-log-dir}}\"]

COPY demo.apache.conf /usr/local/apache2/conf/demo.apache.conf
RUN echo \"Include /usr/local/apache2/conf/demo.apache.conf\" \
    >> /usr/local/apache2/conf/httpd.conf";

function generateApacheDockerfile($version, $errorLog, $customLog) {
    $errorLogDir = getDirectory($errorLog);
    $customLogDir = getDirectory($customLog);

    $content = str_replace("{{apache-version}}", $version, apacheDockerfile);
    $content = str_replace("{{error-log-dir}}", $errorLogDir, $content);
    $content = str_replace("{{custom-log-dir}}", $customLogDir, $content);

    return $content;
}

const nginxDockerFile =
"FROM nginx:{{version}}

RUN [\"/bin/bash\", \"-c\", \"[ ! -d '{{error-log}}' ] && mkdir -p /{{error-log}}\"]
RUN [\"/bin/bash\", \"-c\", \"[ ! -d '{{custom-log}}' ] && mkdir -p /{{custom-log}}\"]";

function generateNginxDockerfile($nginxVersion, $errorLog, $customLog) {
    $errorLog = getDirectory($errorLog);

    $content = str_replace('{{version}}', $nginxVersion, nginxDockerFile);
    $content = str_replace('{{error-log}}', $errorLog, $content);
    $content = str_replace('{{custom-log}}', $customLog, $content);

    return $content;
}

const nginxConf =
"
{{load-balancer}}
server {
    index index.php index.html;
    server_name {{hostname}};
    error_log  {{error-log}};
    access_log {{access-log}};
    root /var/www/html/;
    
    {{lb-pass-pass}}

    location ~ \.php$ {
        try_files \$uri =404;
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass php:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
}";

const nginxLb =
"upstream php {
    {{servers}}
}";

const proxyPassLocation =
"   location / {
        proxy_pass http://php;
    }";

function generateNginxConf($hostname, $errorLog, $accessLog, $useLb, $serverCount) {
    if (isBlank($errorLog)) {
        $errorLog = "/var/log/nginx/error.log";
    }
    if (isBlank($accessLog)) {
        $accessLog = "/var/log/nginx/access.log";
    }

    $content = str_replace('{{hostname}}', $hostname, nginxConf);
    $content = str_replace("{{error-log}}", $errorLog, $content);
    $content = str_replace("{{access-log}}", $accessLog, $content);

    if ($useLb === TRUE) {
        $servers = "";
        for ($i = 1; $i <= $serverCount; $i++) {
            $servers .= "server webtechnologiesproject_php" . $i . "_1:9000;";
        }

        $upstream = str_replace('{{servers}}', $servers, nginxLb);
        $content = str_replace('{{load-balancer}}', $upstream, $content);
        $content = str_replace('{{lb-pass-pass}}', proxyPassLocation, $content);
    } else {
        $content = str_replace('{{load-balancer}}', '', $content);
        $content = str_replace('{{lb-pass-pass}}', '', $content);
    }

    return $content;
}

function isBlank($str) {
    return !isset($str) || trim($str) === '';
}

function isDir($dir) {
    return $dir === "/" || preg_match('/^\/[\w\-\/]+$/', $dir) == 1;
}

function getDirectory($str) {
    $split = explode("/", $str);
    array_pop($split);
    return implode("/", $split);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $hostname = $_POST["host"];
    $errorLog = $_POST["error-log-dir"];
    $port = $_POST["port"];
    $customLog = $_POST["custom-log-dir"];

    $nginxConf = generateApacheConfFile($hostname, $port, $errorLog, $customLog);

    header("Content-Type: application/text");
    header("Content-Disposition: attachment; filename=nginx.conf");

    echo $nginxConf;
}
?>