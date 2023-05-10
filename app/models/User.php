<?php

namespace Model;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс юзера */
class User
{
    /** использую трейт */
    use MainModel;

    /** а вот здесь уже объявляю свойством таблицу конкретно под эту модель */
    protected $table = 'users';

    /** обозначу какие столбцы можно редактировать */
    protected $allowedColumns = [
        'image',
        'username',
        'email',
        'password',
        'date',
    ];

    /** метод подтверждения */
    public function validate($data, $id = null)
    {
        /** массив для ошибок */
        $this->errors = [];

        /** сообщение об ошибке */
        if (empty($data['email'])) {
            $this->errors['email'] = "Email is required";
        } else {
            /** если email не прошел фильтр, то смс об ошибке */
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Email is not valid";
            }
        }

        /** проверка с уже существующей почтой */
        if ($id) {
            if ($this->query("select id from $this->table where id != $id && email = '$data[email]' limit 1")) {
                $this->errors['email'] = "Email is already in use";
            }
        }

        /** сообщение об ошибке */
        if (empty($data['username'])) {
            $this->errors['username'] = "A username is required";
        } else {
            /** если username не соответствует регулярному выражению, то смс об ошибке */
            if (!preg_match('/^[a-zA-Z]+$/', $data['username'])) {
                $this->errors['username'] = "Username can only have letter with no spaces";
            }
        }

        if (!$id) {
            /** сообщение об ошибке */
            if (empty($data['password'])) {
                $this->errors['password'] = "Password is required";
            }

            /** сообщение об ошибке */
            if (empty($data['terms'])) {
                $this->errors['terms'] = "Please accept the terms and conditions";
            }
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
            create table if not exists users
            (
				id int unsigned primary key auto_increment,
				username varchar(50) not null,
				image varchar(1024) null,
				email varchar(100) not null,
				password varchar(255) not null,
				date datetime null,

				key username (username),
				key email (email)
            )
        ";

        $this->query($query);
    }
}
