<?php
/**
 * Created by PhpStorm.
 * User: liuxiaodong
 * Date: 2019/1/10
 * Time: 21:52
 */

namespace App\Controllers\Api;
use App\Exception\Http\FileException;
use Psr\Http\Message\UploadedFileInterface;
use ServiceComponents\Common\Message;
use Swoft\Http\Server\Bean\Annotation\Controller;
use Swoft\Http\Server\Bean\Annotation\RequestMapping;

/**
 * Class ToolControoler
 * @package App\Controllers\Api
 * @Controller()
 */
class ToolControoler
{
    /**
     * 生成保存路径
     * @param $obj
     * @param $type
     * @param $clientName
     * @return string
     */
    public function getFullPath($obj , $type , $clientName)
    {
        $no = self::makeSerialNo();
        $dir = Config::getInstance()->getConf('tool.upload_path')?'/upload':Config::getInstance()->getConf('tool.upload_path');
        $path = $dir."/".$type."/".date("Ymd");

        if (!file_exists(Config::getInstance()->getConf('tool.root_dir').$path)) {
            mkdir(Config::getInstance()->getConf('tool.root_dir').$path, 0777, true);
        }
        if(in_array($type , ['jpeg','jpg','png','avi','mp3']))
        {
            $str = $path."/".$no.".".$type;
        }else
        {
            $str = $path."/".$clientName;
        }
        return $str;
    }
    //生成随机串号
    public static function makeSerialNo(){
        $code = ['A','B','C','D','E','F','G','H','I','J'];
        $serialNo = $code[intval(date('Y'))-2017].strtoupper(dechex(date('m'))).date('d').substr(time(), -5).substr(microtime(),2,5).sprintf('%02d',rand(0,99));
        return $serialNo;
    }

    /**
     * @RequestMapping(route="/api/im/image")
     */
    public function uploadImage()
    {
        //获取群信息
        $file = request()->file('file');
        if(empty($file))
            return Message::error([],'缺少文件');
        if (!($file instanceof UploadedFileInterface))
            throw new FileException(['文件异常']);
        $type = explode('.',$file->getClientFilename());
        $type = array_pop($type);
        $clientName = $file->getClientFilename();
        $src = $this->getFullPath($file,$type , $clientName);
        $file->moveTo('@runtime/uploadfiles/'.$src);
        return Message::sucess(compact('src'));
    }

}