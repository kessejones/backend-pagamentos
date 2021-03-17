FROM php:7.4-fpm

# Instalando utilitários
RUN apt update
RUN apt install -y sudo vim

# Instalando dependências e extensões do PHP
RUN apt install -y libxml2 libxml2-dev libpng-dev libjpeg-dev unzip git supervisor
RUN docker-php-ext-install mysqli pdo_mysql xml json gd

# Instalando Composer
RUN curl "https://getcomposer.org/installer" > composer-setup.php && php composer-setup.php --install-dir=/usr/local/bin --filename=composer

# Configurando timezone do container
RUN ln -fs /usr/share/zoneinfo/America/Sao_Paulo /etc/timezone

# Configuração do PHP
COPY ./docker/php/php.ini /usr/local/etc/php/php.ini

# Configurando Worker do laravel para as Jobs
RUN touch /var/log/laravel-worker.log
COPY ./docker/php/laravel-worker.conf /etc/supervisor/conf.d/laravel-worker.conf

# Definindo script de inicialização dos serviços
COPY ./docker/php/start.sh /start.sh
RUN chmod +x /start.sh

# Criando um usuário comum com permissão de root
RUN useradd -ms /bin/bash default
RUN usermod -aG sudo default
RUN echo '%sudo ALL=(ALL) NOPASSWD:ALL' >> /etc/sudoers

WORKDIR /usr/share/nginx/html
USER default

CMD ["/start.sh"]
