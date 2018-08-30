<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/25
 * Time: 8:51
 */
namespace App\Model\Db;

class ProPlayer extends Base{

    protected $_db;
    protected $_dbName    = 'team_dota2';
    protected $_tbName    = 'team_players';

    private $_resultField = ['player_account_id','team_id','pro_name','rank','pro_avatar','place','steamid','personaname','avatar'];

    public function getProPlayerInfo(int $playerAccountID,array $fields = [])
    {
        $fields = empty($fields)?$this->_resultField:$fields;
        $proPlayerInfoArr = $this->_db->where('player_account_id',$playerAccountID,'=')->getOne($this->_tbName,$fields);
        return $proPlayerInfoArr;
    }



}