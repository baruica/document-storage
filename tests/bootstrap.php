<?php

error_reporting(-1);
date_default_timezone_set('UTC');

$processUser = posix_getpwuid(posix_geteuid());
$credFile    = sprintf('/home/%s/.aws/credentials', $processUser['name']);

if (!file_exists($credFile)) {
    die(sprintf('credentials file [%s] does not exist', $credFile)."\n");
}

// Ensure that composer has installed all dependencies
if (!file_exists(dirname(__DIR__) . '/composer.lock')) {
    die("Dependencies must be installed using composer:\n\nphp composer.phar install\n\n"
        . "See http://getcomposer.org for help with installing composer\n");
}

// Include the composer autoloader
require dirname(__DIR__).'/vendor/autoload.php';
