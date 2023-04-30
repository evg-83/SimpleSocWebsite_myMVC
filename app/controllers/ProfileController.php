<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

use Core\Session;
use Model\Post;
use Model\User;


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

        $user = new User;
        /** get user row */
        $data['row'] = $row = $user->first(['id' => $id]);

        if ($data['row']) {
            $post = new Post;
            // $post->create_table();

            /** get post row */
            $data['posts'] = $post->where(['user_id' => $row->id]);

            //надо получить id юзера этого смс, прокрутив смс
            if ($data['posts']) {
                $data['posts'] = $post->add_user_data($data['posts']);
            }
        }

        $this->view('profile', $data);
    }
}
