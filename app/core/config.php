<?php

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

if ($_SERVER['SERVER_NAME'] == 'localhost') {
    /** БД конфиг */
    define('DBNAME', 'MVC_QuPrCh_up1');
    define('DBHOST', 'localhost');
    define('DBUSER', 'evg');
    define('DBPASS', 3);
    define('DBDRIVER', ''); /** если надо можно сменить драйвер(вид БД: mySQL, postgres и тд) */
    
    /** Создаю константу - путь для отображения изображений */
    define('ROOT', 'http://localhost/LearnProjects/MVC_QuPrCh_up1/public');
} else {
    /** БД конфиг */
    define('DBNAME', 'MVC_QuPrCh_up1');
    define('DBHOST', 'localhost');
    define('DBUSER', 'evg');
    define('DBPASS', 3);
    define('DBDRIVER', '');

    /** Если разместить на хосте(онлайн), то надо так: */
    define('ROOT', 'https://www.yourwebsite.com');
}

/** просто для примера константы */
define('APP_NAME', 'My website');
define('APP_DESC', 'Best website on the planet');

/** использование отладки; true-покажет ошибки; а false-например на онлайн серве, чтоб не отображались ошибки */
define('DEBUG', true);
