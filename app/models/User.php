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
        'email',
        'password',
    ];

    /** метод подтверждения */
    public function validate($data)
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

        /** сообщение об ошибке */
        if (empty($data['password'])) {
            $this->errors['password'] = "Password is required";
        }

        /** сообщение об ошибке */
        if (empty($data['terms'])) {
            $this->errors['terms'] = "Please accept the terms and conditions";
        }

        /** если нет ошибок, то true */
        if (empty($this->errors)) {
            return true;
        }
        return false;
    }
}
