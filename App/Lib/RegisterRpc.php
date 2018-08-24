<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 4:05
 */
namespace App\Lib;
use EasySwoole\Core\Component\Rpc\Common\Parser;
use EasySwoole\Core\Component\Rpc\Common\ServiceResponse;
use EasySwoole\Core\Component\Rpc\Common\Status;
use EasySwoole\Core\Component\Trigger;
use EasySwoole\Core\Socket\Client\Tcp;
use EasySwoole\Core\Socket\Response;


class RegisterRpc{

    private $controllerNameSpace = 'App\\Rpc\\';

    public function setControllerNameSpace(string $nameSpace)
    {
        $this->controllerNameSpace = $nameSpace;
    }

    public function registerReceive(\swoole_server $server, int $fd, int $reactor_id, string $data)
    {
        $response = new ServiceResponse();
        $client = new Tcp($fd,$reactor_id);
        $data = Parser::unPack($data);
//        $openssl = null;
//        if($openssl){
//            $data = $openssl->decrypt($data);
//        }
        if($data !== false){
            $caller = Parser::decode($data,$client);
            if($caller){
                $response->arrayToBean($caller->toArray());
                $response->setArgs(null);
                $group = ucfirst($caller->getServiceGroup());
                $controller = "{$this->controllerNameSpace}{$group}";
                if(!class_exists($controller)){
                    $response->setStatus(Status::SERVICE_GROUP_NOT_FOUND);
                    $controller = "{$this->controllerNameSpace}\\Index";
                    if(!class_exists($controller)){
                        $controller = null;
                    }else{
                        $response->setStatus(Status::OK);
                    }
                }
                if($controller){
                    try{
                        (new $controller($client,$caller,$response));
                    }catch (\Throwable $throwable){
                        Trigger::throwable($throwable);
                        $response->setStatus(Status::SERVICE_ERROR);
                    }
                }else{
                    $response->setStatus(Status::SERVICE_NOT_FOUND);
                }
            }else{
                $response->setStatus(Status::PACKAGE_DECODE_ERROR);
            }
        }else{
            $response->setStatus(Status::PACKAGE_ENCRYPT_DECODED_ERROR);
        }
//        $response = json_encode($response->toArray(),JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
        $response = json_encode(['status'=>$response->getStatus(),'data'=>$response->getResult()],JSON_UNESCAPED_SLASHES|JSON_UNESCAPED_UNICODE);
//        if($openssl){
//            $response =  $openssl->encrypt($response);
//        }
        Response::response($client,Parser::pack($response));
    }




}