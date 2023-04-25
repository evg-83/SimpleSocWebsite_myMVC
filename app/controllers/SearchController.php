<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс поиска(search) */
class SearchController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('search');
    }
}
