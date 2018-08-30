<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 5:35
 */
namespace App\Rpc;

use App\Lib\TcpResponse;
use EasySwoole\Core\Component\Rpc\AbstractInterface\AbstractRpcService;
use EasySwoole\Core\Component\Rpc\Common\ServiceCaller;
use EasySwoole\Core\Component\Rpc\Common\ServiceResponse;
use EasySwoole\Core\Component\Rpc\Common\Status;
use EasySwoole\Core\Socket\Client\Tcp;

class Base extends AbstractRpcService{

    protected $_client;

    public function __construct(Tcp $client, ServiceCaller $serviceCaller, ServiceResponse $response)
    {
        $this->_client = $client;
        parent::__construct($client, $serviceCaller, $response);
    }

    public function index()
    {
        $this->response()->setResult([]);
    }

    public function getArg($arg)
    {
        $arg = $this->serviceCaller()->getArg($arg);
        if(!$arg)
        {
            $this->errorResponse(Status::SERVICE_REJECT_REQUEST,'缺少参数');
        }
        return $arg;
    }

    public function successResponse($data = 'success')
    {
        TcpResponse::successResponse($this->_client,$data);
    }

    public function errorResponse($status,$msg = '')
    {
        TcpResponse::errorResponse($this->_client,$status,$msg);
    }

}