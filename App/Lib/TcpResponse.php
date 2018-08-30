<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/25
 * Time: 7:01
 */
namespace App\Lib;
use EasySwoole\Core\Component\Rpc\Common\Status;
use EasySwoole\Core\Socket\Response;
use EasySwoole\Core\Component\Rpc\Common\Parser;
use EasySwoole\Core\Swoole\ServerManager;

class TcpResponse{

    public static function errorResponse($client,int $status,$data = '')
    {
        $response = json_encode(['status'=>$status,'data'=>$data],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        Response::response($client,Parser::pack($response));
        ServerManager::getInstance()->getServer()->close($client->getFd());
    }

    public static function successResponse($client,$data = '')
    {
        $response = json_encode(['status'=>Status::OK,'data'=>$data],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        Response::response($client,Parser::pack($response));
        ServerManager::getInstance()->getServer()->close($client->getFd());
    }



}