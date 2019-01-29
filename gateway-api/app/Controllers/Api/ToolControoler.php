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
use ServiceComponents\Common\Common;
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
        $no = Common::makeSerialNo();
        $dir = '@runtime/uploadfiles';
        $path = $dir."/".$type."/".date("Ymd");

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
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
    /**
     * @RequestMapping(route="/api/im/image")
     */
    public function uploadImage()
    {
        //获取文件
        $file = request()->file('file');
        if(empty($file))
            return Message::error([],'缺少文件');
        if (!($file instanceof UploadedFileInterface))
            throw new FileException(['文件异常']);
        $type = explode('.',$file->getClientFilename());
        $type = array_pop($type);
        $clientName = $file->getClientFilename();
        $src = $this->getFullPath($file,$type , $clientName);
        $file->moveTo($src);
        return Message::success(compact('src'));
    }

}