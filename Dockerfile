FROM php:7.1-apache

# Enable mod_rewrite
RUN a2enmod rewrite

RUN apt-get update -y && apt-get install -y openssl zip unzip git vim
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN docker-php-ext-install pdo mbstring
#WORKDIR /app
#COPY . /app
#RUN composer install
#RUN composer create-project --prefer-dist laravel/laravel /app5

#WORKDIR /app5

#CMD php artisan serve --host=0.0.0.0 --port=8080
#EXPOSE 80
#CMD ["-D", "FOREGROUND"]
#ENTRYPOINT [ "sh", "-c", "echo $HOME" ]
