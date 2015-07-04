FROM alpine
MAINTAINER kazaoki lab.

# Add PHP & etc
# -------------
RUN apk --update add php-gd php-openssl && \
	rm -rf /var/cache/apk/*

# Environment variables
# ---------------------
ENV TERM=xterm

# Added shell files
# -----------------
ADD clipic.php /opt/clipic.php
CMD ["/usr/bin/php", "/opt/clipic.php"]
