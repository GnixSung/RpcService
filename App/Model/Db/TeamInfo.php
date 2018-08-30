<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 5:39
 */

namespace App\Model\Db;



class TeamInfo extends Base{

    protected $_db;
    protected $_dbName    = 'team_dota2';
    protected $_tbName    = 'team_info';


    private $_resultField = ['team_id','name','tag','rank','division','custom_logo','logo','country_code'];

    public function getTeamInfo($teamID,array $fields = [])
    {
        $fields = empty($fields)?$this->_resultField:$fields;
        $teamInfoArr = $this->_db->where('team_id',$teamID,'=')->getOne($this->_tbName,$fields);
        return $teamInfoArr;
    }

    public function insertTeamID($teamID)
    {
        $data = ['team_id'=>$teamID,'update_time'=>0];
        $ret  = $this->_db->insert($this->_tbName,$data);
        return $ret;
    }






}