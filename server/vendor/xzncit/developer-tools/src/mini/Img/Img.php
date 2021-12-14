<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Img;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Img extends App {

    /**
     * img.aiCrop
     * 本接口提供基于小程序的图片智能裁剪能力。
     * @param string|resource $data     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @param string $ratios                ratios参数为可选，如果为空，则算法自动裁剪最佳宽高比
     * @return array
     * @throws \Exception
     */
    public function aiCrop($file,$ratios=null){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            is_null($ratios) || $data = ['ratios' => $ratios];
            return HttpClient::create()->postJson("cv/img/aicrop?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        is_null($ratios) || $data[] = ['name' => 'ratios', 'contents' => $ratios];
        return HttpClient::create()->upload("cv/img/aicrop?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * img.scanQRCode
     * 本接口提供基于小程序的条码/二维码识别的API
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function scanQRCode($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/img/qrcode?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/img/qrcode?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * img.scanQRCode
     * 本接口提供基于小程序的条码/二维码识别的API
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function superresolution($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/img/superresolution?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/img/superresolution?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

}