<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/25
 * Time: 8:48
 */
namespace App\Rpc;
use App\Model\Kv\ProPlayer as ProPlayerModelKv;
use App\Model\Db\ProPlayer as ProPlayerModelDb;
use EasySwoole\Core\Component\Rpc\Common\Status;

class ProPlayer extends Base{

    /**
     * 获取指定职业选手信息
     */
    public function getProPlayerInfo()
    {
        $playerAccountID = $this->getArg('player_account_id');

        $proPlayerModelKv = new ProPlayerModelKv();

        //缓存中获取
        if(!$proPlayerInfo = $proPlayerModelKv->getProPlayerInfo($playerAccountID))
        {
            $proPlayerModelDb = new ProPlayerModelDb();
            //缓存MISS->DB获取
            if($proPlayerInfo = $proPlayerModelDb->getProPlayerInfo($playerAccountID))
            {
                //保存到缓存
                $proPlayerModelKv->saveProPlayerInfoToCache($playerAccountID,$proPlayerInfo);
            }
        }

        $this->successResponse($proPlayerInfo);
    }

    /**
     * 更新指定职业选手缓存信息
     */
    public function updateProPlayerInfoToCache()
    {
        $playerAccountID = $this->getArg('player_account_id');

        //DB中获取选手信息
        $proPlayerModelDb = new ProPlayerModelDb();
        $proPlayerInfo = $proPlayerModelDb->getProPlayerInfo($playerAccountID);
        if($proPlayerInfo)
        {
            //更新缓存
            $proPlayerModelKv = new ProPlayerModelKv();
            if(!$proPlayerModelKv->saveProPlayerInfoToCache($playerAccountID,$proPlayerInfo))
            {
                $this->errorResponse(Status::SERVICE_ERROR,'更新缓存数据失败');
            }
            else
            {
                $this->successResponse();
            }
        }

        //选手信息不存在
        $this->errorResponse(Status::SERVICE_REJECT_REQUEST,'选手不存在');
    }




}