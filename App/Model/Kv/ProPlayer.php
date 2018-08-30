<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/25
 * Time: 8:52
 */
namespace App\Model\Kv;

class ProPlayer extends Base{

    protected $_connect        = 'dota2';
    protected $_cacheKeyPre    = 'pro:player:info:paid:';


    public function getProPlayerInfo(int $playerAccountID)
    {
        $key = $this->_getProPlayerInfoCacheKeyForPlayerAccountID($playerAccountID);
        $proPlayerInfoJson = $this->_kv->get($key);
        $proPlayerInfoArr  = json_decode($proPlayerInfoJson);
        return $proPlayerInfoArr;
    }

    public function saveProPlayerInfoToCache(int $playerAccountID,$proPlayerInfo)
    {
        $key   = $this->_getProPlayerInfoCacheKeyForPlayerAccountID($playerAccountID);
        $value = is_array($proPlayerInfo)||is_object($proPlayerInfo)?json_encode($proPlayerInfo):$proPlayerInfo;
        $ret   = $this->_kv->set($key,$value);
        return $ret;
    }


    private function _getProPlayerInfoCacheKeyForPlayerAccountID(int $playerAccountID)
    {
        $key = $this->_cacheKeyPre.$playerAccountID;
        return $key;
    }

}