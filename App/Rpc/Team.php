<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 2:45
 */
namespace App\Rpc;

use App\Model\Db\TeamInfo as TeamInfoDb;
use App\Model\Kv\TeamInfo as TeamInfoKv;
use EasySwoole\Core\Component\Rpc\Common\Status;

class Team extends Base{

    /**
     * 获取战队信息
     */
    public function getTeamInfo()
    {
        $teamID = $this->getArg('team_id');

        $teamInfoModelKv = new TeamInfoKv();

        //缓存中读取 miss->db
        if(!$teamInfo = $teamInfoModelKv->getTeamInfo($teamID))
        {
            //Db中获取战队信息
            $teamInfoModelDb = new TeamInfoDb();
            if($teamInfo = $teamInfoModelDb->getTeamInfo($teamID))
            {
                //将战队信息写入缓存
                $teamInfoModelKv->saveTeamInfoToCache($teamID,$teamInfo);
            }
        }

        if (!empty($teamInfo))
        {
            if (empty($teamInfo['custom_logo']))
            {
                if(!empty($teamInfo['logo']))
                {
                    $teamInfo['custom_logo'] =  $teamInfo['logo'];
                }
            }
        }

        $this->successResponse($teamInfo);
    }

    /**
     * 更新战队信息缓存
     */
    public function updateTeamInfoInCacheForTeamID()
    {
        $teamID = $this->getArg('team_id');

        $teamInfoModelDb = new TeamInfoDb();

        $teamInfo = $teamInfoModelDb->getTeamInfo($teamID);

        if($teamInfo)
        {
            $teamInfoModelKv = new TeamInfoKv();

            if(!$teamInfoModelKv->saveTeamInfoToCache($teamID,$teamInfo))
            {
                $this->errorResponse(Status::SERVICE_ERROR,'更新队伍缓存数据失败');
            }
            else
            {
                $this->successResponse();
            }

        }

        $this->errorResponse(Status::SERVICE_REJECT_REQUEST,'战队不存在');
    }

    /**
     * 添加一条战队ID记录
     */
    public function addTeamID()
    {

        $teamID = $this->getArg('team_id');

        $teamInfoModelDb = new TeamInfoDb();

        $teamIsExist = $teamInfoModelDb->getTeamInfo($teamID);

        if(!$teamIsExist)
        {
            if(!$teamInfoModelDb->insertTeamID($teamID))
            {
                $this->errorResponse(Status::SERVICE_ERROR,'添加失败');
            }
            else
            {
                //加入更新队列
                $teamInfoModelKv = new TeamInfoKv();
                $teamInfoModelKv->delTeamIDInUpdateQueue($teamID);
                $teamInfoModelKv->insertTeamIDInQueue($teamID);
                $this->successResponse();
            }
        }
        //战队ID已存在
        $this->errorResponse(Status::SERVICE_REJECT_REQUEST,'战队ID已存在');
    }

}