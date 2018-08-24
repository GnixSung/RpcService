<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 6:52
 */
namespace App\Model\Db;

use App\Lib\Mysql;

class Base{

    protected $_dbName;
    protected $_db;

    public function __construct()
    {
        $db = Mysql::getInstance()->getConnect($this->_dbName);
        if($db instanceof \MysqliDb)
        {
            $this->_db = $db;
        }
    }



}