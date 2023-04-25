<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс настройки пользователя(settings) */
class SettingsController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $this->view('settings');
    }
}
