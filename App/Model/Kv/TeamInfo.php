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

    protected $_connect     = 'dota2';
    protected $_cacheKeyPre = 'team:info:tid:';

    public function getTeamInfo($teamID)
    {
        $key = $this->_getTeamInfoCacheKeyForTeamId($teamID);
        $teamInfoJson = $this->_kv->get($key);
        $teamInfoArr  = json_decode($teamInfoJson,true);
        return $teamInfoArr;
    }

    public function saveTeamInfoToCache($teamID,$teamInfo)
    {
        $key   = $this->_getTeamInfoCacheKeyForTeamId($teamID);
        $value = is_array($teamInfo)||is_object($teamInfo)?json_encode($teamInfo):$teamInfo;
        $ret   = $this->_kv->set($key,$value);
        return $ret;
    }

    private function _getTeamInfoCacheKeyForTeamId($teamID)
    {
        $key = $this->_cacheKeyPre.$teamID;
        return $key;
    }

}