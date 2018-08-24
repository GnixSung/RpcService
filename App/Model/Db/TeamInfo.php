<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 5:39
 */

namespace App\Model\Db;



use EasySwoole\Core\AbstractInterface\Singleton;

class TeamInfo extends Base{

    protected $_db;
    protected $_dbName    = 'team_dota2';
    protected $_tbName    = 'team_info';


    private $_resultField = ['team_id','name','tag','rank','division','custom_logo','logo','country_code'];

    public function getTeamInfo($teamID)
    {
        $teamInfoArr = $this->_db->where('team_id',$teamID,'=')->get($this->_tbName,1,$this->_resultField);
        return $teamInfoArr;
    }





}