<?php

namespace Controller;

use Core\Session;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс выхода(logout) */
class LogoutController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $ses = new Session;
        $ses->logout();

        redirect( 'login' );
    }
}
