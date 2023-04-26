<?php

namespace Controller;

use Core\Request;
use Core\Session;
use Model\User;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс входа(login) */
class LoginController
{
    use MainController;

    /** общий метод */
    public function index()
    {
        $data = [];

        $req = new Request;
        if ($req->posted()) {

            $user = new User();
            $email = $req->post('email');
            $password = $req->post('password');

            if ($row = $user->first(['email' => $email])) {
                //check if password is correct
                if (password_verify($password, $row->password)) {
                    //authenticate
                    $ses = new Session;
                    $ses->auth($row);

                    redirect('home');
                }
            }

            $user->errors['email'] = "Wrong email or password";
            $data['errors'] = $user->errors;
        }

        $this->view('login', $data);
    }
}
