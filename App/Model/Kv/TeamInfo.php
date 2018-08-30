<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 6:19
 */
namespace App\Model\Kv;

use EasySwoole\Core\AbstractInterface\Singleton;

class TeamInfo extends Base{

//    use KvTrait;

    protected $_connect        = 'dota2';
    protected $_cacheKeyPre    = 'team:info:tid:';
    protected $_updateQueueKey = 'team:ids:queue';

    public function getTeamInfo(int $teamID)
    {
        $key = $this->_getTeamInfoCacheKeyForTeamId($teamID);
        $teamInfoJson = $this->_kv->get($key);
        $teamInfoArr  = json_decode($teamInfoJson,true);
        return $teamInfoArr;
    }

    public function saveTeamInfoToCache(int $teamID,$teamInfo)
    {
        $key   = $this->_getTeamInfoCacheKeyForTeamId($teamID);
        $value = is_array($teamInfo)||is_object($teamInfo)?json_encode($teamInfo):$teamInfo;
        $ret   = $this->_kv->set($key,$value);
        return $ret;
    }

    private function _getTeamInfoCacheKeyForTeamId(int $teamID)
    {
        $key = $this->_cacheKeyPre.$teamID;
        return $key;
    }

    /**
     * 更新队列中删除指定战队ID
     * @param $teamID
     * @return mixed
     */
    public function delTeamIDInUpdateQueue(int $teamID)
    {
        $ret = $this->_kv->lrem($this->_updateQueueKey,$teamID,0);
        return $ret;
    }

    /**
     * 插入一条战队ID记录到更新队列头
     * @param $teamID
     * @return mixed
     */
    public function insertTeamIDInQueue(int $teamID)
    {
         $ret = $this->_kv->lpush($this->_updateQueueKey,$teamID);
         return $ret;
    }

    /**
     * 插队
     * @param $teamID
     * @return bool|mixed
     */
    public function cutUpdateQueueForTeamID(int $teamID)
    {
        if($this->delTeamIDInUpdateQueue($teamID)!==false)
        {
            $ret = $this->insertTeamIDInQueue($teamID);
            return $ret;
        }
        return false;
    }

}