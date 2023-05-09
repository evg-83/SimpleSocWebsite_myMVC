<?php

namespace Model;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс комментарий */
class Comment
{
    /** использую трейт */
    use MainModel;

    /** а вот здесь уже объявляю свойством таблицу конкретно под эту модель */
    protected $table = 'comments';

    /** обозначу какие столбцы можно редактировать */
    protected $allowedColumns = [
        'image',
        'comment',
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

    /** метод подтверждения|проверки на ошибки  */
    public function validate($post_data, $files_data, $id = null)
    {
        /** массив для ошибок */
        $this->errors = [];

        /** сообщение об ошибке(есть пост или нет) */
        if (empty($post_data['comment']) && empty($files_data['image']['name'])) {
            $this->errors['comment'] = "Please type something to comment";
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
            create table if not exists comments
            (
				id int unsigned primary key auto_increment,
				user_id int unsigned,
				post_id int unsigned,
				comment text null,
				image varchar(1024) null,
				date datetime null,

				key user_id (user_id),
				key post_id (post_id),
				key date (date)
            )
        ";

        $this->query($query);
    }
}
