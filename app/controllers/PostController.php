<?php

namespace Controller;

use Core\Pager;
use Core\Session;
use Model\Comment;
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

        $post    = new Post;
        $comment = new Comment;

        // $comment->create_table();

        /** get post row */
        $data['post'] = $post->where(['id' => $id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['post']) {
            $data['post'] = $post->add_user_data($data['post']);
            $data['post'] = $post_row = $data['post'][0];

            /** get comments for this post */
            // $comment->order_type = 'asc';
            $comment->offset     = $offset;

            $data['comments'] = $comment->where(['post_id' => $post_row->id]);

            if ($data['comments']) {
                $data['comments'] = $comment->add_user_data($data['comments']);
            }
        }

        $this->view('post', $data);
    }

    /** метод редактирования поста */
    public function edit($id = null)
    {
        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $post = new Post;

        $user_id = user('id');

        /** get post row */
        $data['post'] = $post->where(['id' => $id, 'user_id' => $user_id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['post']) {
            $data['post'] = $post->add_user_data($data['post']);
            $data['post'] = $data['post'][0];
        }

        $this->view('post-edit', $data);
    }

    /** метод удаления поста */
    public function delete($id = null)
    {
        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $post = new Post;

        $user_id = user('id');

        /** get post row */
        $data['post'] = $post->where(['id' => $id, 'user_id' => $user_id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['post']) {
            $data['post'] = $post->add_user_data($data['post']);
            $data['post'] = $data['post'][0];
        }

        $this->view('post-delete', $data);
    }
}
