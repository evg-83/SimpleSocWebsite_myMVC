<?php

namespace Controller;

use Core\Request;
use Model\User;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** класс регистрации(signup) */
class SignupController
{
    use MainController;

    /** общий метод */
    public function index()
    {
        $data = [];

        $req = new Request;

        if ($req->posted()) {
            $user = new User;

            if ($user->validate($req->post())) {
                /** save to DB */
                //шифрование пароля
                $password = password_hash( $req->post( 'password'), PASSWORD_DEFAULT );
                
				$req->set('password',$password);
				$req->set('date',date("Y-m-d H:i:s"));

                $user->insert($req->post());
				message("Profile created successfully");
                /** перенаправление на стр логина */
                redirect('login');
            }
            /** если не пройдет валидацию */
            $data['errors'] = $user->errors;
        }

        $this->view('signup', $data);
    }
}
