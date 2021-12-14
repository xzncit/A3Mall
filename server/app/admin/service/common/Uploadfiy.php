<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace app\admin\service\common;

use app\common\service\upload\Uploadfiy as UploadfiyService;
use app\common\models\Attachments as AttachmentsModel;
use think\facade\Config;

class Uploadfiy extends UploadfiyService {

    /**
     * 删除附件
     * @param $path
     * @return false
     */
    public static function delete($path){
        if(empty($path) || !AttachmentsModel::where(["path"=>$path])->delete()){
            return false;
        }

        $path = str_replace("\\",'/',trim($path,"/"));
        $config = Config::get("base.thumb_image_list");

        $arr = explode("/",$path);
        $file = end($arr);
        file_exists($path) && unlink($path);

        // 如果是图片检查是否有缩略图，存在缩略图删除。
        $suffix = explode(".",$file);
        $suffix = end($suffix);
        if(in_array($suffix,["jpg","png","gif","jpeg","bmp"])){
            foreach($config as $key=>$val){
                $thumb = str_replace($file, $key . '_' . $file, $path);
                file_exists($thumb) && unlink($thumb);
            }
        }

        return true;
    }

}