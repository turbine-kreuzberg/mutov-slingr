<?php

/**
 * This file contains an example of what your settings.php file should contain. The installation script will
 * in most cases automatically create this file, but worst-case scenario (no permissions, for example) it will
 * at least generate the file content so you can create it yourself.
 */
$dbHostname     = 'localhost';
$dbName         = '{{ mysql_databases.0.name | default(slingr) }}';
$dbUsername     = '{{ mysql_users.0.name | default(slingr) }}';
$dbPassword     = '{{ mysql_users.0.password | default(slingr) }}';
$dbTablePrefix  = 'gd_';
$encryptionSalt = 'sDe'; // any 3 A-Z chars
$apiEnabled     = true;
$demoMode       = false;
