<?php

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

class App
{
    private $controller = 'HomeController';
    private $method     = 'index';

    /** метод разбиения url-а */
    private function splitUrl()
    {
        /** если ни чего не будет, то подставь 'home' */
        $URL = $_GET['url'] ?? 'home';
        /** разбивает строку на массив разделителем, тут '/' */
        $URL = explode('/', trim($URL, '/'));

        return $URL;
    }

    /** метод загрузки контроллера */
    public function loadController()
    {
        $URL = $this->splitUrl();
        /** выбор контроллера; указание какой именно контроллер в соответствие с введенным в адресную строку url-ом */
        $filename = '../app/controllers/' . ucfirst($URL[0]) . 'Controller.php';
        /** проверка на наличие файла */
        if (file_exists($filename)) {
            /** если есть, то загружу */
            require $filename;

            /** те если введут не корректно, но похоже */
            $this->controller = ucfirst($URL[0]) . 'Controller';
            /** чтобы использующий удалял предыдущий */
            unset($URL[0]);
        } else {
            $filename = '../app/controllers/_404Controller.php';

            require $filename;

            /** те если введут не корректно, но похоже */
            $this->controller = '_404Controller';
        }

        /** Теперь новый экземпляр создаваться будет именно тот , соответствующий введенному имени в адресную строку */
		$controller = new ('\Controller\\'.$this->controller);

        /** выбор метода; проверка есть ли после первого элемента url-а второй элемент(напр: /home/edit) */
        if (!empty($URL[1])) {
            if (method_exists($controller, $URL[1])) {
                /** если метод существует, то изменю с index на: */
                $this->method = $URL[1];
                /** чтобы использующий удалял предыдущий */
                unset($URL[1]);
            }
        }

        call_user_func_array([$controller, $this->method], $URL);
        /** соответственно [$controller, $this->method] - подставляются, чтобы было по умолчанию и могло меняться */
    }
}
