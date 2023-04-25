<?php

namespace Controller;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

trait MainController
{
    /** метод вид, отображение */
    public function view($name, $data=[])
    {
        /** если есть данные, то извлекаю */
        if (!empty($data)) {
            extract($data);
        }
        
        /** указание какой именно view  */
        $filename = '../app/views/' . $name . '.view.php';

        /** проверка на наличие файла */
        if (file_exists($filename)) {
            /** если есть, то загружу */
            require $filename;
        } else {
            /** иначе загрузи этот файл */
            $filename = '../app/views/404.view.php';
            require $filename;
        }
    }
}
