<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс постов(post) */
class PostController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('post');
    }
}
