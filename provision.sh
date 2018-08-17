#!/usr/bin/env bash
DEBIAN_FRONTEND=noninteractive

# Setup PPA's
add-apt-repository ppa:ondrej/php
add-apt-repository ppa:chris-lea/redis-server

debconf-set-selections <<< 'mysql-server mysql-server/root_password password secret_password'
debconf-set-selections <<< 'mysql-server mysql-server/root_password_again password secret_password'

# Install required APT packages
apt-get update
apt-get install -y curl nginx php7.2-cli php7.2-fpm php7.2-mbstring php7.2-xml php7.2-curl php7.2-mysql mysql-server redis-server supervisor

# Install composer
curl -sS https://getcomposer.org/installer -o composer-setup.php
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
chmod a+x /usr/local/bin/composer

# Setup Nginx host

rm /etc/nginx/sites-enabled/default
cat >/etc/nginx/sites-enabled/monzo-to-ynab.conf <<EOL
server {
    listen 80;
    listen [::]:80;

    server_name monzo-to-ynab.ashleyhindle.com;
    root /vagrant/public/;
    index index.php;
    access_log off;
    error_log off;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php7.2-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
    }

    location ~ /\.ht {
        deny all;
    }
}
EOL

service nginx restart

# Setup Supervisor job
cat >/etc/supervisor/conf.d/monzo-to-ynab.conf <<EOL
[program:horizon]
process_name=%(program_name)s
command=php /vagrant/artisan horizon
autostart=true
autorestart=true
user=vagrant
redirect_stderr=true
stdout_logfile=/vagrant/storage/logs/horizon.log
EOL

# Make our new job available, and started
service supervisor stop
service supervisor start

# Fix permissions
chmod -R g+wrx /vagrant/storage/*
