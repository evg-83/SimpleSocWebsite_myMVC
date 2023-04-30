<?php

namespace Model;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс постов */
class Post
{
    /** использую трейт */
    use MainModel;

    /** а вот здесь уже объявляю свойством таблицу конкретно под эту модель */
    protected $table = 'posts';

    /** обозначу какие столбцы можно редактировать */
    protected $allowedColumns = [
        'image',
        'post',
        'user_id',
        'date',
    ];

    /** внесение данных о пользователе */
    public function add_user_data($rows)
    {
        foreach ($rows as $key => $row) {
            $res = $this->get_row("select * from users where id = :id", ['id' => $row->user_id]);
            $rows[$key]->user = $res;
        }

        return $rows;
    }

    /** метод подтверждения */
    public function validate($data)
    {
        /** массив для ошибок */
        $this->errors = [];

        /** сообщение об ошибке(есть пост или нет) */
        if (empty($data['post'])) {
            $this->errors['post'] = "Please type something to post";
        }

        /** если нет ошибок, то true */
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }

    /** Create table function, if not exist table */
    public function create_table()
    {
        /** создание таблицы */
        $query = "
            create table if not exists posts
            (
				id int unsigned primary key auto_increment,
				user_id int unsigned,
				post text null,
				image varchar(1024) null,
				date datetime null,

				key user_id (user_id),
				key date (date)
            )
        ";

        $this->query($query);
    }
}
