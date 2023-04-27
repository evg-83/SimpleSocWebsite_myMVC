<?php

/**
 * Session class
 * Save or read data to the current session; Сохранение или чтение данных в текущем сеансе
 */

namespace Core;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Access Denied!');

/** Summary of Session class */
class Session
{

    /** Summary of mainkey = 'APP1' */
    public $mainkey = 'APP1';
    /** Summary of userkey = 'USER1' */
    public $userkey = 'USER1';

    /** activate session if not yet started; активировать сеанс, если он еще не запущен **/
    private function start_session(): int
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        return 1;
    }

    /** put data into the session; поместить данные в сессию **/
    public function set(mixed $keyOrArray, mixed $value = ''): int
    {
        $this->start_session();

        /** если $keyOrArray массив */
        if (is_array($keyOrArray)) {
            foreach ($keyOrArray as $key => $value) {

                $_SESSION[$this->mainkey][$key] = $value;
            }

            return 1;
        }

        /** если $keyOrArray просто значение */
        $_SESSION[$this->mainkey][$keyOrArray] = $value;

        return 1;
    }

    /** get data from the session. default is return if data not found; получить данные из сессии. по умолчанию возвращается, если данные не найдены; по дефолту можно подставлять что-то **/
    public function get(string $key, mixed $default = ''): mixed
    {

        $this->start_session();

        if (isset($_SESSION[$this->mainkey][$key])) {
            return $_SESSION[$this->mainkey][$key];
        }

        return $default;
    }

    /** saves the user row data into the session after a login; сохраняет данные строки пользователя в сеанс после входа в систему **/
    public function auth(mixed $user_row): int
    {
        $this->start_session();

        $_SESSION[$this->userkey] = $user_row;

        return 0;
    }

    /** removes user data from the session; удаляет пользовательские данные из сеанса **/
    public function logout(): int
    {
        $this->start_session();

        if (!empty($_SESSION[$this->userkey])) {

            unset($_SESSION[$this->userkey]);
        }

        return 0;
    }

    /** checks if user is logged in; проверяет, авторизован ли пользователь **/
    public function is_logged_in(): bool
    {
        $this->start_session();

        if (!empty($_SESSION[$this->userkey])) {

            return true;
        }

        return false;
    }

    /** gets data from a column in the session user data; получает данные из столбца в пользовательских данных сеанса */
    public function user(string $key = '', mixed $default = ''): mixed
    {
        $this->start_session();

        if (empty($key) && !empty($_SESSION[$this->userkey])) {

            return $_SESSION[$this->userkey];
        } else

		if (!empty($_SESSION[$this->userkey]->$key)) {

            return $_SESSION[$this->userkey]->$key;
        }

        return $default;
    }

    /** returns data from a key and deletes it; возвращает данные из ключа и удаляет их **/
    public function pop(string $key, mixed $default = ''): mixed
    {
        $this->start_session();

        if (!empty($_SESSION[$this->mainkey][$key])) {

            $value = $_SESSION[$this->mainkey][$key];
            unset($_SESSION[$this->mainkey][$key]);
            return $value;
        }

        return $default;
    }

    /** returns all data from the APP array; возвращает все данные из массива APP **/
    public function all(): mixed
    {
        $this->start_session();

        if (isset($_SESSION[$this->mainkey])) {
            return $_SESSION[$this->mainkey];
        }

        return [];
    }
}
