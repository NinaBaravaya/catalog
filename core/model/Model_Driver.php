<?php

defined('PROM') or exit('Access denied');

class Model_Driver
{

    static $instance;

    public $ins_db;

    static function get_instance()
    {
        if (self::$instance instanceof self) {
            return self::$instance;
        }
        return self::$instance = new self;
    }

    public function __construct()
    {
        $this->ins_db = new mysqli(HOST, USER, PASSWORD, DB_NAME);

        if ($this->ins_db->connect_error) {
            throw new DbException("Ошибка соединения : " . $this->ins_db->connect_errno . "|" . iconv("CP1251", "UTF-8", $this->ins_db->connect_error));
        }

        $this->ins_db->query("SET NAMES 'UTF8'");
    }

    public function select(
        $param,
        $table,
        $where = array(),
        $order = FALSE,
        $napr = "ASC",
        $limit = FALSE,
        $operand = arraY('='),
        $match = array()


    )
    {

        $sql = "SELECT";

        foreach ($param as $item) {
            $sql .= ' ' . $item . ',';
        }

        $sql = rtrim($sql, ',');

        $sql .= ' ' . 'FROM' . ' ' . $table;

        if (count($where) > 0) {
            $ii = 0;
            foreach ($where as $key => $val) {
                if ($ii == 0) {
                    if ($operand[$ii] == "IN") {
                        $sql .= " WHERE " . strtolower($key) . " " . $operand[$ii] . "(" . $val . ")";
                    } else {
                        $sql .= ' ' . ' WHERE ' . strtolower($key) . ' ' . $operand[$ii] . ' ' . "'" . $this->ins_db->real_escape_string($val) . "'";
                    }
                }
                if ($ii > 0) {
                    if ($operand[$ii] == "IN") {
                        $sql .= " AND " . strtolower($key) . " " . $operand[$ii] . "(" . $val . ")";
                    } else {
                        $sql .= ' ' . ' AND ' . strtolower($key) . ' ' . $operand[$ii] . ' ' . "'" . $this->ins_db->real_escape_string($val) . "'";
                    }

                }
                $ii++;
                if ((count($operand) - 1) < $ii) {
                    $operand[$ii] = $operand[$ii - 1];
                }
            }

        }

        if (count($match) > 0) {
            foreach ($match as $k => $v) {
                if (count($where) > 0) {
                    $sql .= " AND MATCH (" . $k . ") AGAINST('" . $this->ins_db->real_escape_string($v) . "')";
                } elseif (count($where) == 0) {
                    $sql .= " WHERE MATCH (" . $k . ") AGAINST('" . $this->ins_db->real_escape_string($v) . "')";
                }
            }
        }

        if ($order) {
            $sql .= ' ORDER BY ' . $order . " " . $napr . ' ';
        }

        if ($limit) {
            $sql .= " LIMIT " . $limit;
        }

        $result = $this->ins_db->query($sql);

        if (!$result) {
            throw new DbException("Ошибка запроса" . $this->ins_db->connect_errno . "|" . $this->ins_db->connect_error);
        }

        if ($result->num_rows == 0) {
            return FALSE;
        }

        for ($i = 0; $i < $result->num_rows; $i++) {
            $row[] = $result->fetch_assoc();
        }

        return $row;

    }
///////Метод формирует sql-запросы по удалению данных из бд///////////
    public function delete($table, $where = array(), $operand = array('='))
    {

        //$sql = "DELETE FROM brands WHERE brand_id=28";
        $sql = "DELETE FROM " . $table;
        if (is_array($where)) {
            $i = 0;//счетчик итерации для операнда
            foreach ($where as $k => $v) {
                $sql .= ' WHERE ' . $k . $operand[$i] . "'" . $v . "'";
                $i++;
                if ((count($operand) - 1) < $i) {
                    $operand[$i] = $operand[$i - 1];
                }

            }
        }

        $result = $this->ins_db->query($sql);
        //обращаемся к об-у $this->ins_db класса mysqli
        if (!$result) {
            throw new DbException("Ошибка бд:" . $this->ins_db->errno . " | " . $this->ins_db->error);
            return FALSE;
        }
        return TRUE;
    }
////////Метод формирует sql-запросы по удалению данных из бд///////////////

////////Метод формирует sql-запросы по вставке данных в бд///////////////
    public function insert($table, $data = array(), $values = array(), $id = FALSE)
    {
        //$sql = "INSERT INTO brands (brand_name,parent_id) VALUES ('TEST','0')";

        $sql = "INSERT INTO " . $table . " (";

        $sql .= implode(",", $data) . ") ";

        $sql .= "VALUES (";

        foreach ($values as $val) {
            $sql .= "'" . $val . "'" . ",";
        }

        $sql = rtrim($sql, ',') . ")";
//echo $sql;
       // exit();
        $result = $this->ins_db->query($sql);

        if (!$result) {
            throw new DbException("Ошибка базы данных: " . $this->ins_db->errno . " | " . $this->ins_db->error);
            return FALSE;
        }

        if ($id) {
            return $this->ins_db->insert_id;//вернем id вставленой записи
        }
        return TRUE;
    }
////////Метод формирует sql-запросы по вставке данных в бд///////////////

////////Метод формирует sql-запросы по обновлению данных в бд///////////////
    public function update($table, $data = array(), $values = array(), $where = array())
    {
        //$sql = "UPDATE brands SET brand_name='TES1',parent_id='1' WHERE brand_id = 29";
        $data_res = array_combine($data, $values);


        $sql = "UPDATE " . $table . " SET ";

        foreach ($data_res as $key => $val) {
            $sql .= $key . "='" . $val . "',";
        }

        $sql = rtrim($sql, ',');

        foreach ($where as $k => $v) {
            $sql .= " WHERE " . $k . "=" . "'" . $v . "'";
        }
        // echo $sql;
        //exit();
        $result = $this->ins_db->query($sql);

        if (!$result) {
            throw new DbException("Ошибка базы данных: " . $this->ins_db->errno . " | " . $this->ins_db->error);
            return FALSE;
        }

        return TRUE;
    }
    ////////Метод формирует sql-запросы по обновлению данных в бд///////////////
}

?>