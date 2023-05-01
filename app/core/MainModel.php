<?php

namespace Model;

/** Прямой путь к файлу будет заблокирован */
defined('ROOTPATH') or exit('Доступ запрещен!');

/** главная модель с общими методами */
trait MainModel
{
    use Database;

    /** лимит нумерация страниц */
    public $limit  = 10;
    /** смещение нумерации страниц */
    public $offset = 0;

    /** тип порядка -> в сторону убывания */
    public $order_type = 'desc';
    /** столбец порядка */
    public $order_column = 'id';
    /** ошибки */
    public $errors = [];

    /** метод получения всех результатов из БД */
    public function findAll()
    {
        $query = "select * from $this->table order by $this->order_column $this->order_type limit $this->limit offset $this->offset";

        return $this->query($query);
    }

    /** метод поиска(возвращает несколько строк) */
    public function where($data, $data_not = [])
    /** $data_not=[] - для запросов с отрицанием, пустой массив, для того чтобы был не обязательным параметр */
    {
        $keys     = array_keys($data);
        $keys_not = array_keys($data_not);
        $query    = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        /** обрежу в конце && */
        $query = trim($query, ' && ');

        /** порядок по id на убывание, ну и далее лимит */
        $query .= " order by $this->order_column $this->order_type limit $this->limit offset $this->offset";

        /** объединение(слияние) двух массивов, чтобы отобразить в параметрах запроса */
        $data = array_merge($data, $data_not);

        return $this->query($query, $data);
    }

    /** метод первый(возвращает одну строку и использует массив); а также используя не надо вводить запрос или указывать таблицу */
    public function first($data, $data_not = [])
    {
        $keys     = array_keys($data);
        $keys_not = array_keys($data_not);
        $query    = "select * from $this->table where ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . " && ";
        }
        foreach ($keys_not as $key) {
            $query .= $key . " != :" . $key . " && ";
        }

        $query  = trim($query, ' && ');

        $query .= " limit $this->limit offset $this->offset";

        $data   = array_merge($data, $data_not);

        $result = $this->query($query, $data);

        if ($result) {
            return $result[0];
        }

        return false;
    }

    /** метод добавления в БД */
    public function insert($data)
    {
        /** удаление ненужных данных */
        if (!empty($this->allowedColumns)) {
            /** проход по массиву вводимых данных(по ключам) */
            foreach ($data as $key => $value) {
                /** если в массиве allowedColumns из app/models/User.php не будет соответствия данным(по ключу) из введенных, то удалить эти данные(по ключу) из вводимых, чтобы не прошли в запрос */
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys     = array_keys($data);
        $query    = "insert into $this->table (" . implode(',', $keys) . ") values (:" . implode(',:', $keys) . ")";

        /** просто запрос и подставленные данные из контроллера */
        $this->query($query, $data);

        return false;
    }

    /** метод обновления в БД */
    public function update($id, $data, $id_column = 'id')
    /** $id - потому что надо знать номер строки какую обновляю;
     * $data - просто данные;
     * $id_column='id' - для того, когда не надо использовать 'id', просто ставлю этот */
    {
        /** удаление ненужных данных */
        if (!empty($this->allowedColumns)) {
            /** проход по массиву вводимых данных(по ключам) */
            foreach ($data as $key => $value) {
                /** если в массиве allowedColumns из app/models/User.php не будет соответствия данным(по ключу) из введенных, то удалить эти данные(по ключу) из вводимых, чтобы не прошли в запрос */
                if (!in_array($key, $this->allowedColumns)) {
                    unset($data[$key]);
                }
            }
        }

        $keys             = array_keys($data);
        $query            = "update $this->table set ";

        foreach ($keys as $key) {
            $query .= $key . " = :" . $key . ", ";
        }

        /** обрежу в конце ',' */
        $query            = trim($query, ', ');
        $query           .= " where $id_column = :$id_column";
        $data[$id_column] = $id;

        $this->query($query, $data);

        return false;
    }

    /** метод удаления из БД */
    public function delete($id, $id_column = 'id')
    /** $id - потому что надо знать номер строки какую удаляю;
     * $id_column='id' - для того, когда не надо использовать 'id', просто ставлю этот */
    {
        /** id который запрашиваю на удаление равен id колонки */
        $data[$id_column] = $id;
        $query            = "delete from $this->table where $id_column = :$id_column";

        $this->query($query, $data);

        return false;
    }
}
