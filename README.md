
# MutovSlingr - a data test generator

## Requirements

* [VirtualBox](https://www.virtualbox.org/wiki/Downloads) (>=4.3)
* [Vagrant](https://www.vagrantup.com/downloads.html) (>=1.7.4 for VirtualBox 5.x, [v1.7.2](https://www.vagrantup.com/download-archive/v1.7.2.html) for VirtualBox 4.3.x)
* Vagrant plugins:
  * `vagrant plugin install vagrant-hostmanager`

## Setup

For setting up VM and application, clone project (recursively) and run Vagrant provisioning
```bash
git clone --recursive git@git.votum-media.net:vtm-code-week/mutov-slingr.git
cd mutov-slingr
vagrant up
```

:exclamation: Make sure you clone the repository recursively because the generatedata tool (see below) is included as a submodule.
Just in case you did only a regular clone, run the following command to checkout the submodule as well.
```bash
cd mutov-slingr
git submodule update --init --recursive
```
After the submodule has been cloned, it is located in `src/web`. 

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

## The tool generatedata
 
The base for this application is the tool [generatedata](https://github.com/votum/generatedata), a VOTUM fork of the [original project](https://github.com/benkeen/generatedata).
It comes with an [API](http://api.mutov-slingr.votum-local.de) and a [GUI](http://gui.mutov-slingr.votum-local.de).

Our application uses the API to generate simple, one-dimensional objects, e.g. customers, products, categories.
This data is then post-processed to create more complex, nested objects by adding relations to them. 
