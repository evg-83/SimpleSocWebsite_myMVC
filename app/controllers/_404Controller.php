<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

class _404Controller
{
    use MainController;
    
    public function index()
    {
        $this->view('404');
    }
}
