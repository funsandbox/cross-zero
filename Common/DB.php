<?php

namespace Common;

class DB
{
    protected $connection;

    public function __construct()
    {
        $this->connection  = new \mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    }

    public function query($sql) //подключение к БД
    {
        $result = $this->connection->query($sql);
        $data = array();
        if($result)
        {
            while($row = mysqli_fetch_assoc($result))
            {
                $data[] = $row;
            }
        }
        return $data;
    }

    public function execute($sql)
    {
        $result = $this->connection->query($sql);
        return $result;
    }

    public function escape($value)
    {
        return mysqli_escape_string($this->connection, $value);
    }
}