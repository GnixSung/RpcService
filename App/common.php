<?php
/**
 * Created by PhpStorm.
 * User: ljx@dotamore.com
 * Date: 2018/9/10
 * Time: 23:22
 */

function divisor($divisor,$be,$reserved = 2)
{
    if($be>0)
    {
        return round($divisor/$be,$reserved);
    }

    return 0;
}