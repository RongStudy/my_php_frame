<?php

namespace Frame\Database;


use Frame\{Factory, Register};
use Frame\Interfaces\Database;

/**
 * Class DbMysqli
 * @package Frame\Database
 * @method where($string)
 */
class DbMysqli implements Database
{
    public $conn;
    private $key;
    private $query_id = false;

    public function __construct($db_conf = array(), $key)
    {
        $this->connect($db_conf);
        $this->key = $key;
    }

    public function connect($db_conf)
    {
        $conn = @mysqli_connect($db_conf['host'], $db_conf['user'], $db_conf['password'], $db_conf['dbname']);
        if ($conn) {
            $this->conn = $conn;
        } else {
            $this->conn = $this->query_id = false;
            echo iconv('gbk', 'utf-8', mysqli_connect_error());
            echo ' Error_NO. ' . mysqli_connect_errno();
        }
    }

    public function query($sql)
    {
        $this->query_id = mysqli_query($this->conn, $sql);
        return $this->query_id;
    }

    public function fetch_assoc($resource = null)
    {
        $query_id = $resource ?? $this->query_id;
        if (!$query_id) {
            return null;
        }
        return mysqli_fetch_assoc($query_id);
    }

    /**
     * 获取单条记录
     * @param $sql
     * @return mixed|object|null
     */
    public function result($sql)
    {
        $result = $this->query($sql . ' LIMIT 1');
        if (mysqli_num_rows($result) == 0) {
            return null;
        }

        if (mysqli_num_fields($result) == 1) {
            $arr = mysqli_fetch_row($result);
            $this->free_result($result);
            return $arr[0];
        } else {
            $obj = mysqli_fetch_object($result);
            $this->free_result($result);
            return $obj;
        }
    }

    public function free_result($resource = null)
    {
        $query_id = $resource ?? $this->query_id;
        if (!$query_id) {
            return null;
        }
        return mysqli_free_result($query_id);
    }

    public function close()
    {
        mysqli_close($this->conn);
        $this->conn = false;
        Register::_unset($this->key);
    }
}