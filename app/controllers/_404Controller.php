<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

class _404Controller
{
    use MainController;
    
    public function index()
    {
        echo '404 Page not found controller';
    }
}
