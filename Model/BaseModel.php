<?php

namespace Model;

use Common\DB;

class BaseModel
{
    protected $db;
    protected $table;
    public function __construct()
    {
        $this->db = new DB(); // создание переменной в которой будут храниться все необходимые данные
    }

    public function getAll()
    {
        $result = $this->db->query('select * from ' . $this->table);
        return $result;
    }

    public function get($id)
    {
        $id = intval($id);
        $result = $this->db->query('select * from ' . $this->table . ' where id=' . $id);
        if (!$result) {
            return array();
        }
        return $result[0];
    }
    public function post($key, $default = NULL)
    {
        return isset($_POST[$key]) ? $_POST[$key] : $default;
    }
    public function getUrl($key, $default = NULL)
    {
        return isset($_GET[$key]) ? $_GET[$key] : $default;
    }
}