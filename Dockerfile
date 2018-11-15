FROM alpine:edge

WORKDIR /app

RUN echo ">>> Install tools" && \
    apk add --no-cache bash ca-certificates

RUN echo ">>> Install php packages" && \
    apk add --no-cache php7 php7-json php7-phar php7-iconv php7-openssl php7-zlib \
    php7-mbstring php7-json php7-ctype php7-xml php7-xmlwriter php7-simplexml php7-dom php7-pecl-xdebug php7-tokenizer

RUN echo "zend_extension=xdebug.so" > /etc/php7/conf.d/xdebug.ini

ADD https://github.com/composer/composer/releases/download/1.7.3/composer.phar ./composer

COPY . /app

RUN echo ">>> Test" && \
    php ./composer check && \
    ./vendor/bin/infection --test-framework=phpunit --min-msi=50 --min-covered-msi=70 --ignore-msi-with-no-mutations --ansi -s