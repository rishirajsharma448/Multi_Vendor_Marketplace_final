FROM php:8.2-cli

# Install PDO MySQL extension
RUN docker-php-ext-install pdo pdo_mysql

# Set working directory
WORKDIR /var/www/html

# Copy application files
COPY . /var/www/html/

# Ensure uploads directory exists and set permissions
RUN mkdir -p /var/www/html/uploads && \
    chmod -R 777 /var/www/html/uploads

# Expose default port
EXPOSE 8080

# Run lightweight PHP web server bound to dynamic Railway $PORT
CMD php -S 0.0.0.0:${PORT:-8080}
