#!/usr/bin/env bash

APT_UPDATE_FLAG="/home/vagrant/.apt-get-update-done"
if [ ! -f "$APT_UPDATE_FLAG" ]; then
    sudo apt-get update
    sudo apt-get install -y curl git zip
    touch "$APT_UPDATE_FLAG"
fi

# install apache
APACHE_INSTALLED=$(dpkg -l | grep apache2)
if [ -z "$APACHE_INSTALLED" ]; then
    sudo apt-get install -y apache2
    sudo usermod -a -G www-data vagrant
else
    echo "Apache is already installed"
fi

# install mysql
MYSQL_INSTALLED=$(dpkg -l | grep "mysql-server")
MYSQL_ROOT_PASSWD="root"
if [ -z "$MYSQL_INSTALLED" ]; then
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password password $MYSQL_ROOT_PASSWD"
    sudo debconf-set-selections <<< "mysql-server mysql-server/root_password_again password $MYSQL_ROOT_PASSWD"
    sudo apt-get install -y mysql-server-5.6
else
    echo "MySQL is already installed"
fi

# install php
#PHP_INSTALLED=$(dpkg -l | grep php7)
PHP_INSTALLED=$(dpkg -l | grep php5)
if [ -z "$PHP_INSTALLED" ]; then
    # add ppa repository for PHP5.6
    sudo apt-get install -y software-properties-common python-software-properties
#    sudo add-apt-repository -y ppa:ondrej/php
    sudo add-apt-repository -y ppa:ondrej/php5-5.6
    sudo apt-get update
#    sudo apt-get install -y php7.0 php7.0-mysql php7.0-intl php7.0-curl php7.0-xml php7.0-gd php7.0-mbstring php-xdebug libapache2-mod-php7.0
    sudo apt-get install -y php5 php5-mysql php5-intl php5-curl php5-gd php5-xdebug libapache2-mod-php5
else
    echo "PHP is already installed"
fi

# install composer globally
if [ ! -f "/usr/local/bin/composer" ]; then
    php -r "readfile('https://getcomposer.org/installer');" > composer-setup.php
    php -r "if (hash('SHA384', file_get_contents('composer-setup.php')) === '7228c001f88bee97506740ef0888240bd8a760b046ee16db8f4095c0d8d525f2367663f22a46b48d072c816e7fe19959') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); }"
    php composer-setup.php
    php -r "unlink('composer-setup.php');"

    sudo mv composer.phar /usr/local/bin/composer
else
    echo "Composer is already installed"
fi

# rewrite apache default vhost
APACHE_DEFAULT_HOST="/etc/apache2/sites-available/000-default.conf"
DOCROOT_OLD="/var/www/html"
DOCROOT="/var/www/mutov-slingr/web"
VHOST_MODIFIED=$(grep "$DOCROOT" $APACHE_DEFAULT_HOST)

if [ -z "$VHOST_MODIFIED" ]; then
    sed -i -e "s|DocumentRoot $DOCROOT_OLD|DocumentRoot $DOCROOT|g" $APACHE_DEFAULT_HOST
    sudo service apache2 reload
else
    echo "Apache default vhost is already modified"
fi

# add php settings
#PHP_INIS="/etc/php/7.0/fpm/php.ini /etc/php/7.0/cli/php.ini"
PHP_INIS="/etc/php5/apache2/php.ini /etc/php5/cli/php.ini"
DATETIME_ZONE="date.timezone = \"Europe/Berlin\""

for PHP_INI in $PHP_INIS; do
    if [ -z "$(grep "$DATETIME_ZONE" $PHP_INI)" ]; then
        sudo echo "$DATETIME_ZONE" >> $PHP_INI
        echo "Updated PHP config file $PHP_INI"
    fi
done
sudo service apache2 reload
