
# MutovSlingr - a data test generator

## Requirements

* [VirtualBox](https://www.virtualbox.org/wiki/Downloads) (>=4.3)
* [Vagrant](https://www.vagrantup.com/downloads.html) (>=1.7.4 for VirtualBox 5.x, [v1.7.2](https://www.vagrantup.com/download-archive/v1.7.2.html) for VirtualBox 4.3.x)
* Vagrant plugins:
  * `vagrant plugin install vagrant-hostmanager`

## Setup

- Clone project, run Vagrant provisioning and ssh into VM
```bash
git clone --recursive git@git.votum-media.net:vtm-code-week/mutov-slingr.git
cd mutov-slingr
vagrant up
vagrant ssh
```
- Run composer inside VM
```bash
cd /var/www/mutov-slingr
composer install
```
- Setup generatedata application by calling http://mutov-slingr.votum-local.de and follow the instructions
  - for database setup just `slingr` for database name, username and password.

## Installed tools/packages
- Apache 2.4
- MySQL 5.6
- PHP 5.6 with modules: 
  - php5-mysql 
  - php5-intl 
  - php5-curl 
  - php5-gd 
  - php5-xdebug 
- Composer
