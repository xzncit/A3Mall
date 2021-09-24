<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------
namespace mall\library\wechat\chat\module;

use mall\library\wechat\chat\BasicWeChat;

class Media extends BasicWeChat{

    /**
     * 新增临时素材
     */
    public function add($filename, $type = 'image'){
        if (!in_array($type, ['image', 'voice', 'video', 'thumb'])) {
            throw new \Exception('您所选择的素材类型不允许上传', '0');
        }
        $url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token=ACCESS_TOKEN&type={$type}";
        return $this->httpPost($url, ['media' => $this->createCurlFile($filename)], false);
    }

    /**
     * 获取临时素材
     * @param string $media_id
     * @param string $outType 返回处理函数
     * @return array|string
     */
    public function get($media_id, $outType = null){
        $url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id={$media_id}";
        $result = $this->get($url);
        if (is_array($json = json_decode($result, true))) {
            return $this->json2arr($result);
        }

        return is_null($outType) ? $result : $outType($result);
    }

    /**
     * 新增图文素材
     * @param array $data 文件名称
     * @return array
     */
    public function addNews($data){
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 更新图文素材
     * @param string $media_id 要修改的图文消息的id
     * @param int $index 要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
     * @param array $news 文章内容
     * @return array
     */
    public function updateNews($media_id, $index, $news){
        $data = ['media_id' => $media_id, 'index' => $index, 'articles' => $news];
        $url = "https://api.weixin.qq.com/cgi-bin/material/update_news?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, $data);
    }

    /**
     * 上传图文消息内的图片获取URL
     * @param string $filename
     * @return array
     */
    public function uploadImg($filename){
        $url = "https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, [
            'media' => $this->createCurlFile($filename)
        ], false);
    }

    /**
     * 新增其他类型永久素材
     * @param string $filename 文件名称
     * @param string $type 媒体文件类型(image|voice|video|thumb)
     * @param array $description 包含素材的描述信息
     * @return array
     */
    public function addMaterial($filename, $type = 'image', $description = []){
        if (!in_array($type, ['image', 'voice', 'video', 'thumb'])) {
            throw new \Exception('Invalid Media Type.', '0');
        }
        $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token=ACCESS_TOKEN&type={$type}";
        return $this->httpPost($url, [
            'media' => $this->createCurlFile($filename),
            'description' => $this->arr2json($description)
        ], false);
    }

    /**
     * 获取永久素材
     * @param string $media_id
     * @param null|string $outType 输出类型
     * @return array|string
     */
    public function getMaterial($media_id, $outType = null){
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_material?access_token=ACCESS_TOKEN";
        $result = $this->post($url, ['media_id' => $media_id]);
        if (is_array($json = json_decode($result, true))) {
            return $this->json2arr($result);
        }
        return is_null($outType) ? $result : $outType($result);
    }

    /**
     * 删除永久素材
     * @param string $media_id
     * @return array
     */
    public function delMaterial($media_id){
        $url = "https://api.weixin.qq.com/cgi-bin/material/del_material?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, ['media_id' => $media_id]);
    }

    /**
     * 获取素材总数
     * @return array
     */
    public function getMaterialCount(){
        $url = "https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token=ACCESS_TOKEN";
        return $this->httpGet($url);
    }

    /**
     * 获取素材列表
     * @param string $type
     * @param int $offset
     * @param int $count
     * @return array
     */
    public function batchGetMaterial($type = 'image', $offset = 0, $count = 20){
        if (!in_array($type, ['image', 'voice', 'video', 'news'])) {
            throw new \Exception('Invalid Media Type.', '0');
        }
        $url = "https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token=ACCESS_TOKEN";
        return $this->httpPost($url, [
            'type' => $type,
            'offset' => $offset,
            'count' => $count
        ]);
    }
}