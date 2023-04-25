<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс профиль(profile) */
class ProfileController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('profile');
    }
}
