<?php

namespace Controller;

use Core\Session;
use Model\User;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс профиль(profile) */
class ProfileController
{
    use MainController;

    /** общий метод */
    public function index()
    {
        $id = user('id');

        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        /** get user row */
        $user = new User;

        $data['row'] = $user->first(['id' => $id]);

        $this->view('profile', $data);
    }
}
