<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

use Core\Pager;
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
        /** смотрю функцию URL в app/core/functions.php */
        $id = URL('slug') ?? user('id');

        $ses = new Session;

        /** pagination vars; разбиение на страницы */
        $limit = 3;

        $data['pager'] = new Pager($limit);

        $offset = $data['pager']->offset;

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

            /** пагинация постов */
            // лимит постов
            $post->limit = $limit;
            // смещение
            $post->offset = $offset;

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
