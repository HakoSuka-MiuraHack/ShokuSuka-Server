FROM ubuntu
RUN apt-get update \
&&  apt-get install -y \
        composer \
        curl \
        php \
        php-intl \
        php-mbstring \
        php-mysql \
        unzip \
        zip
RUN mkdir /code
WORKDIR /code
ADD . /code/