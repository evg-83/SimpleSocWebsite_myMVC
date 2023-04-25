<?php

use Model\Image;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** check which php extensions are required; проверьте, какие расширения php требуются */
check_extensions();
function check_extensions()
{
    $required_extensions = [
        'gd',
        'mysqli',
		'pdo_mysql',
		'pdo_sqlite',
		'curl',
		'fileinfo',
		'intl',
		'exif',
		'mbstring',
    ];

    $not_loaded = [];

    foreach ($required_extensions as $ext) {
        if (!extension_loaded($ext)) {
            $not_loaded[] = $ext;
        }
    }

    if (!empty($not_loaded)) {
        show("Please load the following extensions in your php.ini file: <br>" . implode("<br>", $not_loaded));
        die;
    }
}

/** Вывод ошибок */
ini_set('display_errors', 1);

/** активирую отчет об ошибках */
error_reporting(E_ALL);

/** просто просмотр-проверка */
function show($stuff)
{
    echo '<pre>';
    print_r($stuff);
    echo '</pre>';
}

/** защита от атак(чтобы не работал как JavaScript) */
function esc($str)
{
    return htmlspecialchars($str);
}

/** функция перенаправления */
function redirect($path)
{
    header("Location: " . ROOT . "/" . $path);
    die;
}

/** load image. if not exist, load placeholder; функция добавления images; если не существует, загрузить заполнитель  */
function get_image(mixed $file = '', string $type = 'post'): string
{
    $file = $file ?? '';

    /** если название изображения, то подставится корневой путь */
    if (file_exists($file)) {
        return ROOT . "/" . $file;
    }

    /** проверка типа, условие подстановки */
    if ($type == 'user') {
        /** вернуть заполнитель */
        return ROOT . "/assets/images/user.jpeg";
    } else {
        return ROOT . "/assets/images/no_image.jpeg";
    }
}

/** returns pagination links; Возвращает некоторые ссылки на страницы */
function get_pagination_vars(): array
{
    $vars = [];
    /** кодовая страница элемента равна номеру страницы; если страница существует в GET-переменной, то получим ее как страницу 1 */
    $vars['page'] = $_GET['page'] ?? 1;
    /** преобразовываем в целое число(от случаев если кто-то например ввел 'no', преобразует в число 1) */
    $vars['page'] = (int)$vars['page'];
    /** условие для предыдущей страницы(если ввел кто-то) */
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
    /** следующая стр - добавление к номеру страницы */
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}

/** saves or displays a saved message to the user; Сохраняет или показывает сообщения пользователю. */
function message(string $msg = null, bool $clear = false)
{
    /** объект класса Session */
    $ses = new Core\Session();

    if (!empty($msg)) {
        /** если дам функции сообщение, она его сохранит(в переменной значения сеанса например) */
        $ses->set('message', $msg);
    } else {
        /** если смс нет: проверяем есть ли смс в сеансе, берем его и возвращаем */
        if (!empty($ses->get('message'))) {
            $msg = $ses->get('message');
            /** но если установлено значение очистки true например, то оно удалится прежде чем вернет его функция */
            if ($clear) {
                $ses->pop('message');
            }
            return $msg;
        }
    }
    return false;
}

/** return URL variables; возвращать URL-переменные **/
function URL($key): mixed
{
    $URL = $_GET['url'] ?? 'home';
    $URL = explode("/", trim($URL, "/"));

    switch ($key) {
        case 'page':
        case 0:
            return $URL[0] ?? null;
            break;
        case 'section':
        case 'slug':
        case 1:
            return $URL[1] ?? null;
            break;
        case 'action':
        case 2:
            return $URL[2] ?? null;
            break;
        case 'id':
        case 3:
            return $URL[3] ?? null;
            break;
        default:
            return null;
            break;
    }
}

/** 
 * displays input values after a page refresh; Отображение входных значений после обновления страницы.
 * 
 * вернет выбор флажков например. 
 **/
function old_checked(string $key, string $value, string $default = ""): string
{
    if (isset($_POST[$key])) {
        if ($_POST[$key] == $value) {
            return ' checked ';
        }
    } else {
        if ($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value) {
            return ' checked ';
        }
    }
    return '';
}

/** вернет старое значение */
function old_value(string $key, mixed $default = "", string $mode = 'post'): mixed
{
    $POST = ($mode == 'post') ? $_POST : $_GET;

    if (isset($POST[$key])) {
        return $POST[$key];
    }
    return $default;
}

/** вернет старый выбор */
function old_select(string $key, mixed $value, mixed $default = "", string $mode = 'post'): mixed
{
    $POST = ($mode == 'post') ? $_POST : $_GET;

    if (isset($POST[$key])) {
        if ($POST[$key] == $value) {
            return " selected ";
        }
    } else {
        if ($default == $value) {
            return " selected ";
        }
    }
    return "";
}

/** returns a user readable date format */
function get_date($date)
{
    return date("jS M, Y", strtotime($date));
}

/** converts image paths from relative to absolute; преобразует пути изображения из относительных в абсолютные **/
function add_root_to_images($contents)
{

    preg_match_all('/<img[^>]+>/', $contents, $matches);
    if (is_array($matches) && count($matches) > 0) {

        foreach ($matches[0] as $match) {

            preg_match('/src="[^"]+/', $match, $matches2);
            if (!strstr($matches2[0], 'http')) {

                $contents = str_replace($matches2[0], 'src="' . ROOT . '/' . str_replace('src="', "", $matches2[0]), $contents);
            }
        }
    }

    return $contents;
}

/** converts images from text editor content to actual files; конвертирует изображения из содержимого текстового редактора в реальные файлы */
function remove_images_from_content($content, $folder = "uploads/")
{
    /** проверка наличия папки; создания; размещения файла */
    if (!file_exists($folder)) {
        mkdir($folder, 0777, true);
        /** тк данная папка находится в public, то для этого и создам пустой файл index(чтоб в него попали и отобразилась пустая страница, ну или с текстом "Доступ запрещен" в параметр data) */
        file_put_contents($folder . "index.php", "");
    }

    /** remove images from content; ищет любые теги img */
    preg_match_all('/<img[^>]+>/', $content, $matches);
    $new_content = $content;

    if (is_array($matches) && count($matches) > 0) {
        $images_class = new Image();
        foreach ($matches[0] as $match) {
            if (strstr($match, "http")) {
                /** ignore images with links already; игнорировать изображения со ссылками уже */
                continue;
            }
            /** get the src; получить источник */
            preg_match('/src="[^"]+/', $match, $matches2);

            /** get the filename; получить имя файла */
            preg_match('/data-filename="[^\"]+/', $match, $matches3);

            if (strstr($matches2[0], 'data')) {
                $parts = explode(",", $matches2[0]);
                $basename = $matches3[0] ?? 'basename.jpg';
                $basename = str_replace('data-filename="', "", $basename);

                $filename = $folder . "img_" . sha1(rand(0, 9999999999)) . $basename;

                /** замена по сути изображения */
                $new_content = str_replace($parts[0] . "," . $parts[1], 'src="' . $filename, $new_content);
                file_put_contents($filename, base64_decode($parts[1]));

                /** resize image; изменить размер изображения, если будут какие-то проблемы с этим классом, изменение можно удалить */
                $images_class->resize($filename, 1000);
            }
        }
    }
    return $new_content;
}

/** deletes images from text editor content; Удалит изображение из контента */
function delete_images_from_content(string $content, string $content_new = ''): void
{
    /** delete images from content */
    if (empty($content_new)) {
        preg_match_all('/<img[^>]+>/', $content, $matches);
        if (is_array($matches) && count($matches) > 0) {
            foreach ($$matches[0] as $match) {
                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    unlink($matches2[0]);
                }
            }
        }
    } else {
        /** compare old to new and delete from old what inst in the new; сравнить старое с новым и удалить из старого то, что вставлено в новое */
        preg_match_all('/<img[^>]+>/', $content, $matches);
        preg_match_all('/<img[^>]+>/', $content_new, $matches_new);

        $old_images = [];
        $new_images = [];

        /** collect old images; собирать старые изображения */
        if (is_array($matches) && count($matches) > 0) {
            foreach ($matches[0] as $match) {
                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    $old_images[] = $matches2[0];
                }
            }
        }
        /** collect new images; собирать новые изображения */
        if (is_array($matches_new) && count($matches_new) > 0) {
            foreach ($matches_new[0] as $match) {

                preg_match('/src="[^"]+/', $match, $matches2);
                $matches2[0] = str_replace('src="', "", $matches2[0]);

                if (file_exists($matches2[0])) {
                    $new_images[] = $matches2[0];
                }
            }
        }

        /** compare and delete all that dont appear in the new array; сравнить и удалить все, что не отображается в новом массиве **/
        foreach ($old_images as $img) {

            if (!in_array($img, $new_images)) {

                if (file_exists($img)) {
                    unlink($img);
                }
            }
        }
    }
}
