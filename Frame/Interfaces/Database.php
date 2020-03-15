<?php


namespace Frame\Interfaces;


interface Database
{
    public function connect($conf);
    public function query($sql);
    public function fetch_assoc($resource = null);
    public function free_result($resource = null);
    public function close();
}