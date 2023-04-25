<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс входа(login) */
class LoginController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('login');
    }
}
