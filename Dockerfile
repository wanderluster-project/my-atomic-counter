FROM alpine:3.9
LABEL Maintainer="Kevin Simpson <simpkevin@gmail.com>" \
      Description="Backend API for Wanderluster.io"

# Install packages
RUN apk --no-cache add php7 php7-fpm php7-json php7-openssl php7-curl php7-ctype php7-phar php7-xmlwriter \
    php7-intl php7-tokenizer php7-iconv php7-xml php7-dom php7-mbstring php7-bcmath php7-sodium nginx \
    git php7-dev gcc g++ make supervisor curl && \

    # Install Composer
    cd /tmp && \
    wget https://raw.githubusercontent.com/composer/getcomposer.org/76a7060ccb93902cd7576b67264ad91c8a2700e2/web/installer -O - -q | php -- --quiet && \
    mv /tmp/composer.phar /usr/local/bin/composer && \
    chmod +x /usr/local/bin/composer && \

    # Install JumpHash
    cd /tmp && \
    git clone https://github.com/c9s/jchash.git && \
    cd jchash && \
    sh rebuild-all

# Configure nginx
COPY build/config/nginx.conf /etc/nginx/nginx.conf

# Configure PHP-FPM
COPY build/config/fpm-pool.conf /etc/php7/php-fpm.d/www.conf
COPY build/config/php.ini /etc/php7/conf.d/zzz_custom.ini

# Configure supervisord
COPY build/config/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Make sure files/folders needed by the processes are accessable when they run under the nobody user
RUN chown -R nobody.nobody /run && \
  chown -R nobody.nobody /var/lib/nginx && \
  chown -R nobody.nobody /var/tmp/nginx && \
  chown -R nobody.nobody /var/log/nginx

# Setup document root
RUN mkdir -p /var/www/html

# Switch to use a non-root user from here on
#USER nobody

# Add application
WORKDIR /var/www/html
COPY --chown=nobody . /var/www/html/

# Expose the port nginx is reachable on
EXPOSE 8080

# Let supervisord start nginx & php-fpm
CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]

# Configure a healthcheck to validate that everything is up&running
HEALTHCHECK --timeout=10s CMD curl --silent --fail http://127.0.0.1:8080/fpm-ping