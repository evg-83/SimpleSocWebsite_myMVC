<?php

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** автозагрузка моделей; те если PHP не найдет класс, то запустит эту функцию, которая и выдаст нужный класс */
spl_autoload_register(function ($classname) {
    $classname = explode("\\", $classname);
    $classname = end($classname);

    require $filename = "../app/models/" . ucfirst($classname) . ".php";
});

/** сначала, тк в нем конфигурация */
require 'config.php';

require 'functions.php';
require 'Database.php';
require 'MainModel.php';
require 'MainController.php';
require 'App.php';
