<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\mini\Search;

use xzncit\core\App;
use xzncit\core\http\HttpClient;

class Search extends App {

    /**
     * search.imageSearch
     * 本接口提供基于小程序的站内搜商品图片搜索能力
     * @param $file
     * @return array
     * @throws \Exception
     */
    public function imageSearch($file){
        if(gettype($file) != "resource"){
            if(!file_exists($file)){
                throw new \Exception("file does not exist",0);
            }

            $file = fopen($file,"r+");
        }

        return HttpClient::create()->upload("wxa/imagesearch?access_token=ACCESS_TOKEN",[
            [
                'name'     => 'img',
                'contents' => $file
            ]
        ])->toArray();
    }

    /**
     * search.siteSearch
     * 小程序内部搜索API提供针对页面的查询能力，小程序开发者输入搜索词后，
     * 将返回自身小程序和搜索词相关的页面。因此，利用该接口，
     * 开发者可以查看指定内容的页面被微信平台的收录情况；同时，该接口也可供开发者在小程序内应用，
     * 给小程序用户提供搜索能力。
     * @param $keyword              关键词
     * @param $next_page_info       请求下一页的参数，开发者无需理解。为空时查询的是第一页内容，如需查询下一页，把返回参数的next_page_info填充到这里即可
     * @return array
     * @throws \Exception
     */
    public function siteSearch($keyword,$next_page_info){
        return HttpClient::create()->postJson("wxa/sitesearch?access_token=ACCESS_TOKEN",[
            "next_page_info"=>$next_page_info,"keyword"=>$keyword
        ])->toArray();
    }

    /**
     * search.submitPages
     * 小程序开发者可以通过本接口提交小程序页面url及参数信息(不要推送webview页面)，
     * 让微信可以更及时的收录到小程序的页面信息，开发者提交的页面信息将可能被用于小程序搜索结果展示。
     * @param $pages        小程序页面信息列表
     * @return array
     * @throws \Exception
     */
    public function submitPages($pages){
        return HttpClient::create()->postJson("wxa/search/wxaapi_submitpages?access_token=ACCESS_TOKEN",[
            "pages"=>$pages
        ])->toArray();
    }

}