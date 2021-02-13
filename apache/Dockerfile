FROM httpd:2.4.33-alpine
RUN apk update; \
    apk upgrade;

RUN ["/bin/bash", "-c", "[ ! -d 'enis' ] && mkdir -p /enis"]

# Copy apache vhost file to proxy php requests to php-fpm container
COPY demo.apache.conf /usr/local/apache2/conf/demo.apache.conf
RUN echo "Include /usr/local/apache2/conf/demo.apache.conf" \
    >> /usr/local/apache2/conf/httpd.conf