<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/18
 * Time: 2:27
 */
return [

    'match_data' => [
        'host' => $_ENV['DB_MATCHDATA_HOST'],
        'user' => $_ENV['DB_MATCHDATA_USER'],
        'password' => $_ENV['DB_MATCHDATA_PASSWORD'],
        'db'=> $_ENV['DB_MATCHDATA_DB'],
        'port' => $_ENV['DB_MATCHDATA_PORT'],
        'charset' => $_ENV['DB_MATCHDATA_CHARSET']
    ],

    'team_dota2' => [
        'host' => $_ENV['DB_TEAM_HOST'],
        'user' => $_ENV['DB_TEAM_USER'],
        'password' => $_ENV['DB_TEAM_PASSWORD'],
        'db'=> $_ENV['DB_TEAM_DB'],
        'port' => $_ENV['DB_TEAM_PORT'],
        'charset' => $_ENV['DB_TEAM_CHARSET']
    ],

];