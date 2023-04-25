<?php

session_start();

/** Определение минимальной версии PHP */
$minPHPVersion = '8.0';
if (phpversion() < $minPHPVersion) {
    die("Your PHP version must be {$minPHPVersion} or higher to run this app. Your current version is " . phpversion());
}

/** Путь к файлу */
define('ROOTPATH', __DIR__ . DIRECTORY_SEPARATOR);

require '../app/core/init.php';

/** использование отладки; true(1)-покажет ошибки; а false(0)-например на онлайн серве, чтоб не отображались ошибки */
DEBUG ? ini_set('display_errors', 1) : ini_set('display_errors', 0);

$app = new App;
$app->loadController();
