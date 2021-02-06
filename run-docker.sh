#!/bin/bash

containerId=`docker ps | awk 'NR==2{print $1}'`
docker stop $containerId
docker rmi -f php-test
docker build . --tag php-test
docker run -d php-test