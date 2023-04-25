<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс домашний(home) */
class HomeController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('home');
    }
}
