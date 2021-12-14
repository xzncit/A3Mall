<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\wechat\Intelligent;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Intelligent extends App {

    /**
     * 发送语义理解请求
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function search(array $data){
        $data["appid"] = $this->app->config["appid"];
        return HttpClient::create()->postJson("semantic/semproxy/search?access_token=ACCESS_TOKEN",$data)->toArray();
    }

    /**
     * 提交语音
     * @param string|resource $file             文件的绝对路径或者使用 fopen 返回的资源
     * @param string $voice_id                  语音唯一标识
     * @param string $lang                      语言，zh_CN 或 en_US，默认中文
     * @return array
     * @throws \Exception
     */
    public function addvoicetorecofortext($file,$voice_id,$lang="zh_CN"){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->uploadBody("cgi-bin/media/voice/addvoicetorecofortext?access_token=ACCESS_TOKEN&format=mp3&voice_id={$voice_id}&lang={$lang}",$file)->toArray();
    }

    /**
     * 获取语音识别结果
     * @param string $voice_id         语音唯一标识
     * @param string $lang             语言，zh_CN 或 en_US，默认中文
     * @return array
     * @throws \Exception
     */
    public function queryrecoresultfortext($voice_id,$lang="zh_CN"){
        return HttpClient::create()->postJson("cgi-bin/media/voice/queryrecoresultfortext?access_token=ACCESS_TOKEN&voice_id={$voice_id}&lang={$lang}")->toArray();
    }

    /**
     * 微信翻译
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @param string $lfrom                 源语言，zh_CN 或 en_US
     * @param string $lto                   目标语言，zh_CN 或 en_US
     * @return array
     * @throws \Exception
     */
    public function translatecontent($file,$lfrom,$lto){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->uploadBody("cgi-bin/media/voice/translatecontent?access_token=ACCESS_TOKEN&lfrom={$lfrom}&lto={$lto}",$file)->toArray();
    }

    /**
     * 身份证OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function idcard($file){
        return $this->upload($file,"cv/ocr/idcard?access_token=ACCESS_TOCKEN");
    }

    /**
     * 银行卡OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function bankcard($file){
        return $this->upload($file,"cv/ocr/bankcard?access_token=ACCESS_TOCKEN");
    }

    /**
     * 行驶证OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function driving($file){
        return $this->upload($file,"cv/ocr/driving?access_token=ACCESS_TOCKEN");
    }

    /**
     * 驾驶证OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function drivinglicense($file){
        return $this->upload($file,"cv/ocr/drivinglicense?access_token=ACCESS_TOCKEN");
    }

    /**
     * 营业执照OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function bizlicense($file){
        return $this->upload($file,"cv/ocr/bizlicense?access_token=ACCESS_TOCKEN");
    }

    /**
     * 通用印刷体OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function comm($file){
        return $this->upload($file,"cv/ocr/comm?access_token=ACCESS_TOCKEN");
    }

    /**
     * 车牌OCR识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function platenum($file){
        return $this->upload($file,"cv/ocr/platenum?access_token=ACCESS_TOCKEN");
    }

    /**
     * 二维码/条码识别接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function qrcode($file){
        return $this->upload($file,"cv/img/qrcode?access_token=ACCESS_TOCKEN");
    }

    /**
     * 图片高清化接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function superresolution($file){
        return $this->upload($file,"cv/img/superresolution?access_token=ACCESS_TOCKEN");
    }

    /**
     * 图片智能裁剪接口
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @param string $ratios                ratios参数为可选，如果为空，则算法自动裁剪最佳宽高比；如果提供多个宽高比，请以英文逗号“,”分隔，最多支持5个宽高比
     * @return array
     * @throws \Exception
     */
    public function aicrop($file,$ratios=null){
        return $this->upload($file,"cv/img/aicrop?access_token=ACCESS_TOCKEN",$ratios);
    }

    /**
     * @param string|resource $file         文件的绝对路径或者使用 fopen 返回的资源
     * @param string $uri
     * @param string $ratios                ratios参数为可选，如果为空，则算法自动裁剪最佳宽高比
     * @return array
     * @throws \Exception
     */
    private function upload($file,$uri,$ratios=null){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data = [ ['name' => 'img', 'contents' => $file] ];
        is_null($ratios) || $data[1] = ['name' => 'ratios', 'contents' => $ratios];
        return HttpClient::create()->upload($uri,$data)->toArray();
    }

}