<?php
namespace mall\basic;

use mall\utils\Tool;
use think\facade\Db;
use think\facade\Config;
use think\facade\Request;
use think\Image;

class Attachments {

    public static function save($name,$path,$suffix,$size,$module="",$method=""){
        if(empty($module)){
            $module = Request::post("module","","trim,strip_tags");
        }

        if(empty($method)){
            $method = Request::post("method","","trim,strip_tags");
        }

        Db::name("attachments")->insert([
            "module"=>$module,
            "method"=>$method,
            "name"=>$name,
            "suffix"=>$suffix,
            "size"=>$size,
            "path"=>"/" . trim($path,"/"),
            "time"=>time()
        ]);

        return Db::name('attachments')->getLastInsID();
    }

    public static function saveFile($url="",$type=0,$module="",$method=""){
        if(empty($url)){
            throw new \Exception("图片地址不能为空");
        }

        switch ($type){
            case 0:
                $ch=curl_init();
                curl_setopt($ch,CURLOPT_URL,$url);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);//最长执行时间
                curl_setopt($ch,CURLOPT_TIMEOUT,30);//最长等待时间

                $img=curl_exec($ch);
                curl_close($ch);
                break;
            case 1:
                ob_start();
                readfile($url);
                $img = ob_get_contents();
                ob_end_clean();
                break;
            case 2:
                $img = file_get_contents($url);
                break;
        }

        if(empty($img)){
            return "";
        }

        $path = Tool::getRootPath() . 'public';
        $filename = date("YmdHis").mt_rand(1,9999);
        $ext = explode(".",$url);
        $suffix = end($ext);
        if(!in_array($suffix,["jpg","png","gif","jpeg","bmp"])){
            return false;
        }

        $filename .= '.' . $suffix;
        $dir =  '/uploads/images/' . date("Ymd") . "/";
        $file = $path . $dir . $filename;
        if(!is_dir($path . $dir)){
            mkdir($path . $dir,0777,true);
        }

        if(!function_exists("file_put_contents")){
            $fp2=@fopen($file,'a');
            fwrite($fp2,$img);
            fclose($fp2);
        }else{
            file_put_contents($file,$img);
        }

        $name = basename($filename);
        self::save(
            $name,
            $dir . $filename,
            $suffix,
            filesize($file),
            $module,$method
        );

        if(Config::get("base.is_thumb_image")){
            $thumb_image_list = Config::get("base.thumb_image_list");
            foreach($thumb_image_list as $key=>$val){
                $image = Image::open($file);
                $image->thumb($val[0], $val[1])->save(str_replace($name, $key . '_' . $name, $file));
            }
        }

        return $dir . $filename;
    }

    public static function handle($attachment_id,$id,$clear=true){
        if(empty($attachment_id)){
            return false;
        }

        $res = Db::name("attachments")->where('id','in',$attachment_id)->select()->toArray();
        foreach($res as $value){
            Db::name("attachments")->where('id',$value['id'])->update([
                "pid"=>$id
            ]);
        }

        $clear && self::clear();
        return true;
    }

    public static function clear($data=[]){
        if(empty($data)){
            $map = ["pid"=>0];
        }else{
            $map = $data;
        }

        $rs = Db::name("attachments")->where($map)->select()->toArray();
        foreach($rs as $item){
            if(Db::name("attachments")->where(["id"=>$item['id']])->delete()){
                self::deleteImage($item['path']);
            }
        }

        return true;
    }

    public static function deleteImage($image){
        $path = str_replace("\\",'/',trim($image,"/"));
        $config = Config::get("base.thumb_image_list");

        $arr = explode("/",$path);
        $lastImage = end($arr);
        file_exists($path) && unlink($path);
        foreach($config as $key=>$val){
            $thumb = str_replace($lastImage, $key . '_' . $lastImage, $path);
            file_exists($thumb) && unlink($thumb);
        }

        return true;
    }

    public static function deleteById($id,$module="",$method=""){
        $condition = [];
        $condition[] = ["pid","=",$id];
        if(!empty($module)){
            $condition[] = ["module","=",$module];
        }

        if(!empty($method)){
            $condition[] = ["method","=",$method];
        }

        $attachments = Db::name("attachments")->where($condition)->select()->toArray();
        foreach($attachments as $val){
            if(Db::name("attachments")->where("id",$val["id"])->delete()){
                self::deleteImage($val["path"]);
            }
        }

        return true;
    }

}