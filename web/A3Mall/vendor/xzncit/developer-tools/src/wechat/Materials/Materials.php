<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Materials;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Materials extends App {

    /**
     * 上传临时素材和永久性素材方法
     * @param string|resource      $file          文件的绝对路径或者使用 fopen 返回的资源
     * @param string               $type          媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @param bool                 $permanently   上传永久性素材
     * @param array                $args          上传需要填写此参数
     * @return array
     * @throws \Exception
     */
    public function add($file,$type="image",$permanently=false,$args=[]){
        if(!in_array($type,["image","voice","video","thumb"])){
            throw new \Exception("Illegal media file type",0);
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data = [[
            'name' => 'media',
            'contents' => $file
        ]];

        if($permanently && $type == "video"){
            $data[] = [
                'name' => 'description',
                'contents' => json_encode([
                    "title"=>isset($args["title"]) ? $args["title"] : "video",
                    "introduction"=>isset($args["intro"]) ? $args["intro"] : "video"
                ],JSON_UNESCAPED_UNICODE)
            ];
        }

        return HttpClient::create()->upload(
            $permanently ? "cgi-bin/material/add_material?access_token=ACCESS_TOKEN&type={$type}" : "cgi-bin/media/upload?access_token=ACCESS_TOKEN&type={$type}",
            $data
        )->toArray();
    }

    /**
     * 获取临时素材和永久性素材方法
     * @param string       $media_id      媒体文件ID
     * @param bool         $permanently   获取永久性素材
     * @return array
     * @throws \Exception
     */
    public function get($media_id,$permanently=false){
        if(!$permanently){
            return HttpClient::create()->get("cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id={$media_id}")->toArray();
        }

        return HttpClient::create()->postJson("cgi-bin/material/get_material?access_token=ACCESS_TOKEN",[
            "media_id"=>$media_id
        ])->toArray();
    }

    /**
     * 新增永久图文素材
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function addNews($data){
        return HttpClient::create()->postJson("cgi-bin/material/add_news?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 修改永久图文素材
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function updateNews($data){
        return HttpClient::create()->postJson("cgi-bin/material/update_news?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 上传图文消息内的图片获取URL
     * @param  string|resource   $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function uploadimg($file){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->upload("cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN",[
            [
                'name' => 'media',
                'contents' => $file
            ]
        ])->toArray();
    }

    /**
     * 删除永久素材,临时素材无法通过本接口删除
     * @param string $media_id
     * @return array
     * @throws \Exception
     */
    public function delete($media_id){
        return HttpClient::create()->postJson("cgi-bin/material/del_material?access_token=ACCESS_TOKEN",[
            "media_id"=>$media_id
        ])->toArray();
    }

    /**
     * 获取永久素材的总数资源列表
     * @return array
     * @throws \Exception
     */
    public function count(){
        return HttpClient::create()->get("cgi-bin/material/get_materialcount?access_token=ACCESS_TOKEN")->toArray();
    }

    /**
     * 获取分类型永久素材的列表
     * @param string $type     素材的类型，图片（image）、视频（video）、语音 （voice）、图文（news）
     * @param string $offset   从全部素材的该偏移位置开始返回，0表示从第一个素材 返回
     * @param string $count    返回素材的数量，取值在1到20之间
     * @return array
     * @throws \Exception
     */
    public function batchgetMaterial($type,$offset,$count){
        return HttpClient::create()->postJson("cgi-bin/material/batchget_material?access_token=ACCESS_TOKEN",[
            "type"=>$type,"offset"=>$offset,"count"=>$count
        ])->toArray();
    }

    /**
     * 打开已群发文章评论
     * @param int $msg_data_id      群发返回的msg_data_id
     * @param int $index            多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentOpen($msg_data_id,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/open?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index
        ])->toArray();
    }

    /**
     * 关闭已群发文章评论
     * @param int $msg_data_id      群发返回的msg_data_id
     * @param int $index            多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentClose($msg_data_id,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/close?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index
        ])->toArray();
    }

    /**
     * @param int $msg_data_id      群发返回的msg_data_id
     * @param int $begin            起始位置
     * @param int $count            获取数目（>=50会被拒绝）
     * @param int $type             type=0 普通评论&精选评论 type=1 普通评论 type=2 精选评论
     * @param int $index            多图文时，用来指定第几篇图文，从0开始，不带默认返回该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function getCommentList($msg_data_id,$begin,$count,$type,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/list?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index,
            "begin"=>$begin,"count"=>$count,"type"=>$type
        ])->toArray();
    }

    /**
     * 将评论标记精选
     * @param int $msg_data_id          群发返回的msg_data_id
     * @param int $user_comment_id      用户评论id
     * @param int $index                多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function setCommentMarkelect($msg_data_id,$user_comment_id,$index){
        return HttpClient::create()->postJson("cgi-bin/comment/markelect?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index,"user_comment_id"=>$user_comment_id
        ])->toArray();
    }

    /**
     * 将评论取消精选
     * @param int $msg_data_id          群发返回的msg_data_id
     * @param int $user_comment_id      用户评论id
     * @param int $index                多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentUnmarkelect($msg_data_id,$user_comment_id,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/unmarkelect?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"user_comment_id"=>$user_comment_id,"index"=>$index
        ])->toArray();
    }

    /**
     * 删除评论
     * @param int $msg_data_id          群发返回的msg_data_id
     * @param int $user_comment_id      评论id
     * @param int $index                多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentDelete($msg_data_id,$user_comment_id,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/delete?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index,"user_comment_id"=>$user_comment_id
        ])->toArray();
    }

    /**
     * 回复评论
     * @param int $msg_data_id          群发返回的msg_data_id
     * @param int $user_comment_id      评论id
     * @param string $content           回复内容
     * @param int $index                多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentReply($msg_data_id,$user_comment_id,$content,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/reply/add?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index,"user_comment_id"=>$user_comment_id,
            "content"=>$content
        ])->toArray();
    }

    /**
     * 删除回复
     * @param int $msg_data_id          群发返回的msg_data_id
     * @param int $user_comment_id      评论id
     * @param int $index                多图文时，用来指定第几篇图文，从0开始，不带默认操作该msg_data_id的第一篇图文
     * @return array
     * @throws \Exception
     */
    public function commentReplyDelete($msg_data_id,$user_comment_id,$index=0){
        return HttpClient::create()->postJson("cgi-bin/comment/reply/delete?access_token=ACCESS_TOKEN",[
            "msg_data_id"=>$msg_data_id,"index"=>$index,"user_comment_id"=>$user_comment_id
        ])->toArray();
    }
}