<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/18
 * Time: 3:23
 */
namespace App\Lib;
use EasySwoole\Config;
use EasySwoole\Core\AbstractInterface\Singleton;

class Redis{

    use Singleton;

    private $kvConnects;


    public function getConnect($kv)
    {
        try
        {
            $pingRet = $this->kvConnects[$kv]->ping();

            if($pingRet != '+pong' && $pingRet != 'pong')
            {
                $this->connect($kv);
            }

        }
        catch (\Throwable $throwable)
        {
            $this->connect($kv);
        }
        return $this->kvConnects[$kv];
    }

    private function connect($kv)
    {
        $conf = Config::getInstance()->getConf('kv.'.$kv);
        $this->kvConnects[$kv] = new \Redis();
        $this->kvConnects[$kv]->connect($conf['host'],$conf['port']?:6379);
        if($conf['auth']){
            $this->kvConnects[$kv]->auth($conf['auth']);
        }
    }




}