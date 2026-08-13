#!/bin/bash
set -e

PORT_TO_USE="${PORT:-80}"

echo "Configuring Apache to listen on port ${PORT_TO_USE}..."

# Completely overwrite ports.conf cleanly
cat <<EOF > /etc/apache2/ports.conf
Listen ${PORT_TO_USE}

<IfModule ssl_module>
	Listen 443
</IfModule>

<IfModule mod_gnutls.c>
	Listen 443
</IfModule>
EOF

# Completely overwrite 000-default.conf cleanly with AllowOverride All
cat <<EOF > /etc/apache2/sites-available/000-default.conf
<VirtualHost *:${PORT_TO_USE}>
	ServerAdmin webmaster@localhost
	DocumentRoot /var/www/html

	<Directory /var/www/html>
		Options Indexes FollowSymLinks
		AllowOverride All
		Require all granted
	</Directory>

	ErrorLog \${APACHE_LOG_DIR}/error.log
	CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

exec apache2-foreground
