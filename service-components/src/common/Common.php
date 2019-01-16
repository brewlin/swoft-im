<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/13
 * Time: 8:57
 */

namespace ServiceComponents\Common;


class Common
{
    public static function getRandChar($length)
    {
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol) - 1;

        for ($i=0;$i<$length;$i++)
            $str .= $strPol[rand(0,$max)];
        return $str;
    }

    // 生成 n 位随机数
    public static function generate_code($length = 6)
    {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }

    public static function security($str)
    {
        if(!is_string($str))
            return false;
        return htmlspecialchars(addslashes($str));
    }
    //生成随机串号
    public static function makeSerialNo()
    {
        $code = ['A','B','C','D','E','F','G','H','I','J'];
        $serialNo = $code[intval(date('Y'))-2017].strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $serialNo;
    }
}