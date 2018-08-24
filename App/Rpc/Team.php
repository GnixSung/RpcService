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

class Team extends Base {

    public function getTeamInfo()
    {
        $teamID = $this->getArg('team_id');
        if(!$teamID)
        {
            $this->response()->setStatus(Status::SERVICE_REJECT_REQUEST);
            $this->response()->setResult('缺少参数');
            return false;
        }

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

        $this->response()->setResult($teamInfo);
    }
}