<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\OCR;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class OCR extends App {

    /**
     * ocr.bankcard
     * 本接口提供基于小程序的银行卡 OCR 识别
     * @param string|resource $file      可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @param string $type               图片识别模式，photo（拍照模式）或 scan（扫描模式）
     * @return array
     * @throws \Exception
     */
    public function bankcard($file,$type="photo"){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/bankcard?type={$type}&img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/bankcard?type={$type}&access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * ocr.businessLicense
     * 本接口提供基于小程序的营业执照 OCR 识别
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function businessLicense($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/bizlicense?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/bizlicense?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * ocr.driverLicense
     * 本接口提供基于小程序的驾驶证 OCR 识别
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function driverLicense($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/drivinglicense?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/drivinglicense?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * ocr.driverLicense
     * 本接口提供基于小程序的身份证 OCR 识别
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function idcard($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/idcard?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/idcard?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * ocr.printedText
     * 本接口提供基于小程序的通用印刷体 OCR 识别
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @return array
     * @throws \Exception
     */
    public function printedText($file){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/comm?img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/comm?access_token=ACCESS_TOCKEN",$data)->toArray();
    }

    /**
     * ocr.vehicleLicense
     * 本接口提供基于小程序的行驶证 OCR 识别
     * @param  string|resource $file     可以传网络图片或者文件的绝对路径也可以使用 fopen 返回的资源
     * @param string $type               图片识别模式，photo（拍照模式）或 scan（扫描模式）
     * @return array
     * @throws \Exception
     */
    public function vehicleLicense($file,$type="photo"){
        $data = [];
        if(false !== filter_var($file,FILTER_VALIDATE_URL)) {
            return HttpClient::create()->postJson("cv/ocr/driving?type={$type}&img_url={$file}&access_token=ACCESS_TOCKEN",$data)->toArray();
        }

        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        $data[] = ['name' => 'img', 'contents' => $file];
        return HttpClient::create()->upload("cv/ocr/driving?type={$type}&access_token=ACCESS_TOCKEN",$data)->toArray();
    }

}