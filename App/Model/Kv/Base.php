<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 6:46
 */
namespace App\Model\Kv;

use App\Lib\Redis;

class Base{

    protected $_connect;
    protected $_kv;

    public function __construct()
    {
        $this->_kv = Redis::getInstance()->getConnect($this->_connect);
    }



}