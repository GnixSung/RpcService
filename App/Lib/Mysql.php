<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/18
 * Time: 2:09
 */
namespace App\Lib;
use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\Singleton;

Class Mysql{

    use Singleton;

    private $dbConnects;

    public function getConnect($db)
    {
        try
        {
            $this->dbConnects[$db]->ping();
        }
        catch (\Throwable $throwable)
        {
            $this->connect($db);
        }
        return $this->dbConnects[$db];
    }

    private function connect($db)
    {
        $conf = Config::getInstance()->getConf('database.'.$db);
        $this->dbConnects[$db] = new \MysqliDb($conf['host'],$conf['user'],$conf['password'],$conf['db']);
    }

}