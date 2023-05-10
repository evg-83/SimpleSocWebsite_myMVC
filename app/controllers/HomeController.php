<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

use Core\Pager;
use Core\Session;
use Model\Post;
use Model\User;

/** класс домашний(home) */
class HomeController
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

        $id = user('id');

        /** pagination vars; разбиение на страницы */
        $limit = 5;

        $data['pager'] = new Pager($limit);

        $offset = $data['pager']->offset;

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
            $data['posts'] = $post->findAll();

            //надо получить id юзера этого смс, прокрутив смс
            if ($data['posts']) {
                $data['posts'] = $post->add_user_data($data['posts']);
            }
        }

        $this->view('home', $data);
    }
}
