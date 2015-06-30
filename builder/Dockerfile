FROM alpine
MAINTAINER kazaoki lab.

# Add PHP & etc
# -------------
RUN apk --update add php-gd php-openssl && \
	rm -rf /var/cache/apk/* && \
	sed -i 's/\;extension\=php_openssl\.dll/extension\=openssl\.so/g' /etc/php/php.ini

# Added shell files
# -----------------
ADD textimage.php /opt/textimage.php
CMD ["/usr/bin/php", "/opt/textimage.php"]
