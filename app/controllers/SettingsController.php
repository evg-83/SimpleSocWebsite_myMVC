<?php

namespace Controller;

use Core\Session;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс настройки пользователя(settings) */
class SettingsController
{
    use MainController;
    
    /** общий метод */
    public function index()
    {
        $ses = new Session;
        
        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }
        
        $this->view('settings');
    }
}
