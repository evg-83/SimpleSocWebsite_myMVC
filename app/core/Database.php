<?php

namespace Model;

use PDO;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** будет не класс, а трейт */
trait Database
{
    /** метод соединения */
    private function connect()
    {
        /** само соединение */
        $string = "mysql::hostname=" . DBHOST . "; dbname=" . DBNAME; //dbname= можно потом указать
        /** экземпляр PDO создающий соединение с БД */
        $con    = new PDO($string, DBUSER, DBPASS);

        return $con;
    }

    /** метод запрос к БД */
    public function query($query, $data = [])
    /** когда нужен запрос в БД, сделается в совокупности с трейтом */
    {
        /** коннект с БД */
        $con = $this->connect();
        /** подготовка самого запроса; stm=statement(заявление) */
        $stm = $con->prepare($query);
        /** проверка массива */
        $check = $stm->execute($data);

        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result)) {
                return $result;
            }
        }
        /** срабатывает если нет результатов, например запросы на обновление или удаление */
        else return false;
    }

    /** метод получение строки к БД; используется когда надо ввести сложный запрос и нужен только один результат */
    public function get_row($query, $data = [])
    /** когда нужна строка запрос в БД, сделается в совокупности с трейтом */
    {
        /** коннект с БД */
        $con = $this->connect();
        /** подготовка самого запроса; stm=statement(заявление) */
        $stm = $con->prepare($query);
        /** проверка массива */
        $check = $stm->execute($data);

        if ($check) {
            $result = $stm->fetchAll(PDO::FETCH_OBJ);
            if (is_array($result) && count($result)) {
                /** только строку */
                return $result[0];
            }
        }
        /** срабатывает если нет результатов, например запросы на обновление или удаление */
        return false;
    }
}
