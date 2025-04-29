FROM php:8.2-apache

# Instalações essenciais (sem pacotes desnecessários)
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

# Copia os arquivos
COPY ./src/ /var/www/html/

# Configura permissões para upload
RUN mkdir -p /var/www/html/uploads \
 && chown -R www-data:www-data /var/www/html/uploads \
 && chmod -R 775 /var/www/html/uploads