<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс регистрации(signup) */
class SignupController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('signup');
    }
}
