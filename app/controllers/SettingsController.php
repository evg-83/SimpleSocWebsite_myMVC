<?php

namespace Controller;

use Core\Pager;
use Core\Session;
use Model\User;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс настройки пользователя(settings) */
class SettingsController
{
    use MainController;

    /** общий метод */
    public function index()
    {
        /** смотрю функцию URL в app/core/functions.php */
        $id = URL('slug') ?? user('id');

        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $user = new User;
        /** get user row */
        $data['row'] = $row = $user->first(['id' => $id]);

        $this->view('settings', $data);
    }
}
