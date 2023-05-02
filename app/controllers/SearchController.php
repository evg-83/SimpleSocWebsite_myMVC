<?php

namespace Controller;

use Core\Pager;
use Core\Session;
use Model\User;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс поиска(search) */
class SearchController
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

        $data = [];

        /** pagination vars; разбиение на страницы */
        $limit = 10;

        $data['pager'] = new Pager($limit);

        $offset = $data['pager']->offset;

        $user = new User;

        $arr          = [];
        $arr['find']  = $_GET['find'] ?? null;

        if ($arr['find']) {
            // %-поиск
            $arr['find']  = "%" . $arr['find'] . "%";
            $data['rows'] = $user->query("select * from users where username like :find || email like :find limit $limit offset $offset", $arr);
        }

        $this->view('search', $data);
    }
}
