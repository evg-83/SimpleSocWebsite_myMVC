<?php

namespace Controller;

use Core\Pager;
use Core\Session;
use Model\Comment;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс комментариев(comment) */
class CommentController
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

        $comment = new Comment;

        /** get comment row */
        $data['comment'] = $comment->where(['id' => $id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['comment']) {
            $data['comment'] = $comment->add_user_data($data['comment']);
            $data['comment'] = $comment_row = $data['comment'][0];
        }

        $this->view('comment', $data);
    }

    /** метод редактирования поста */
    public function edit($id = null)
    {
        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $comment = new Comment;

        $user_id = user('id');

        /** get comment row */
        $data['comment'] = $comment->where(['id' => $id, 'user_id' => $user_id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['comment']) {
            $data['comment'] = $comment->add_user_data($data['comment']);
            $data['comment'] = $data['comment'][0];
        }

        $this->view('comment-edit', $data);
    }

    /** метод удаления поста */
    public function delete($id = null)
    {
        $ses = new Session;

        /** перенаправление если не залогинился */
        if (!$ses->is_logged_in()) {
            redirect('login');
        }

        $comment = new Comment;

        $user_id = user('id');

        /** get comment row */
        $data['comment'] = $comment->where(['id' => $id, 'user_id' => $user_id]);

        //надо получить id юзера этого смс, прокрутив смс
        if ($data['comment']) {
            $data['comment'] = $comment->add_user_data($data['comment']);
            $data['comment'] = $data['comment'][0];
        }

        $this->view('comment-delete', $data);
    }
}
