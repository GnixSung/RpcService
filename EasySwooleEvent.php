<?php
/**
 * Created by PhpStorm.
 * User: yf
 * Date: 2018/1/9
 * Time: 下午1:04
 */

namespace EasySwoole;

use App\Lib\RegisterRpc;
use App\Process\Inotify;
use EasySwoole\Core\AbstractInterface\EventInterface;
use EasySwoole\Core\Http\Request;
use EasySwoole\Core\Http\Response;
use EasySwoole\Core\Swoole\EventRegister;
use EasySwoole\Core\Swoole\Process\ProcessManager;
use EasySwoole\Core\Swoole\ServerManager;
use EasySwoole\Core\Utility\File;

Class EasySwooleEvent implements EventInterface {

    public static function frameInitialize(): void
    {
        // TODO: Implement frameInitialize() method.
        date_default_timezone_set('Asia/Shanghai');
        //加载evn文件
        (new \Dotenv\Dotenv(EASYSWOOLE_ROOT,'.env'))->load();
        //加载项目配置文件
        self::loadConf(EASYSWOOLE_ROOT . '/Config');
    }

    public static function mainServerCreate(ServerManager $server,EventRegister $register): void
    {
        // TODO: Implement mainServerCreate() method.
        $register->set($register::onReceive,function(\swoole_server $server, int $fd, int $reactor_id, string $data){
            (new RegisterRpc())->registerReceive($server,$fd,$reactor_id,$data);
        });
        ProcessManager::getInstance()->addProcess('autoReload',Inotify::class);
    }

    public static function onRequest(Request $request,Response $response): void
    {
        // TODO: Implement onRequest() method.
    }

    public static function afterAction(Request $request,Response $response): void
    {
        // TODO: Implement afterAction() method.
    }

    public static function loadConf($ConfPath)
    {
        $Conf  = Config::getInstance();
        $files = File::scanDir($ConfPath);
        foreach ($files as $file) {
            $data = require_once $file;
            $Conf->setConf(strtolower(basename($file, '.php')), (array)$data);
        }
    }
}