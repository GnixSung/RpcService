<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/8/23
 * Time: 3:02
 */
namespace App\Lib;
use EasySwoole\Core\Socket\AbstractInterface\ParserInterface;
use EasySwoole\Core\Socket\Common\CommandBean;

class Parser implements ParserInterface
{
    public static function decode($raw, $client): ?CommandBean
    {
        // TODO: Implement decode() method.
        $list = explode(":",trim($raw));
        $bean = new CommandBean();
        $controller = array_shift($list);
        if($controller == 'test'){
            $bean->setControllerClass(Test::class);
        }
        $bean->setAction(array_shift($list));
        $bean->setArg('test',array_shift($list));
        return $bean;
    }

    public static function encode(string $raw, $client): ?string
    {
        // TODO: Implement encode() method.
        return $raw."\n";
    }
}
