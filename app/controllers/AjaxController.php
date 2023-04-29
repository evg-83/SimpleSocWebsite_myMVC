<?php

namespace Controller;

defined('ROOTPATH') or exit('Доступ запрещен!');

use Model\Image;
use Core\Request;
use Core\Session;
use Model\User;

/** Прямой путь к файлу будет заблокирован */

/** класс ajax */
class AjaxController
{
    use MainController;

    /** общий метод */
    public function index()
    {
        $ses = new Session;

        /** убивание всего, что делаем, если не залогинился */
        if (!$ses->is_logged_in()) {
            die;
        }

        $req = new Request;
        $user = new User;
        $info['success'] = false;
        $info['message'] = "";

        if ($req->posted()) {
            $data_type = $req->input('data_type');
            $info['data_type'] = $data_type;

            if ($data_type = 'profile-image') {
                /** тк obj.image = file */
                $image_row = $req->files('image');

                if ($image_row['error'] == 0) {
                    /** папка загрузки изображений */
                    $folder = "uploads/";

                    if (!file_exists($folder)) {
                        mkdir($folder, 0777, true);
                    }
                    /** место назначения */
                    $destination = $folder . time() . $image_row['name'];
                    /** перемещение файла(как до этого в show() видел) */
                    move_uploaded_file($image_row['tmp_name'], $destination);
                    /** изменение размера */
                    $image_class = new Image;
                    $image_class->resize($destination, 1000);

                    $id = user('id');
                    /** строка юзера по id */
                    $row = $user->first(['id' => $id]);
                    /** сохранение этих данных юзеру */
                    $user->update($id, ['image' => $destination]);

                    /** удаление старых изображений */
                    if (file_exists($row->image)) {
                        unlink($row->image);
                    }

                    $info['message'] = "Profile image change successfully";
                    $info['success'] = true;
                }
            } else {
                if ($data_type = 'create-post') {
                    $id = user('id');

                    $post = new Post;

                    $arr = [];
                    /** все что нужно для поста */
                    $arr['post']    = $req->input('post');
                    $arr['user_id'] = $id;
                    $arr['date']    = date("Y-m-d H:i:s");
                    /** сохранение этих данных юзеру в посты */
                    $post->insert($arr);

                    $info['message'] = "Post created successfully";
                    $info['success'] = true;
                }
            }

            echo json_encode($info);
        }
    }
}
