<?php

namespace Controller;

use Core\Pager;
use Core\Session;
use Model\Post;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс постов(post) */
class PostController
{
    use MainController;

    /** общий метод */
    public function index($id = null)
    {
        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        /** pagination vars; разбиение на страницы */
        $limit = 3;
        $data['pager'] = new Pager($limit);
        $offset = $data['pager']->offset;

        $post = new Post;

        /** get post row */
        $data['post'] = $post->where(['id' => $id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['post']) {
            $data['post'] = $post->add_user_data($data['post']);
            $data['post'] = $data['post'][0];
        }

        $this->view('post', $data);
    }
}
