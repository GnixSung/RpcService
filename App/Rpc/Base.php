<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 5:35
 */
namespace App\Rpc;

use EasySwoole\Core\Component\Rpc\AbstractInterface\AbstractRpcService;

class Base extends AbstractRpcService{

    public function index()
    {
        $this->response()->setResult([]);
    }

    protected function getArg($arg)
    {
        $args = $this->serviceCaller()->getArgs();
        if($args&&isset($args[$arg]))
        {
            return $args[$arg];
        }
        return null;
    }

}