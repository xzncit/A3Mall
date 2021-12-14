<?php
// +----------------------------------------------------------------------
// | A3Mall
// +----------------------------------------------------------------------
// | Copyright (c) 2020 http://www.a3-mall.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: xzncit <158373108@qq.com>
// +----------------------------------------------------------------------

namespace xzncit\core\http;

use GuzzleHttp\Client;
use xzncit\core\base\AccessToken;
use xzncit\core\Config;

class HttpClient {

    private static $instance;
    private $client;

    public $options;
    public $response = "";

    private function __construct(){
        $this->client = new Client(Config::get("http"));
    }

    public static function create(){
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * GET提交数据方法
     * @param string $uri
     * @param array $params
     * @param bool $verify
     * @return $this
     */
    public function get($uri,array $params=[]){
        $array = $this->parseUrl($uri);
        $this->response = $this->client->request('GET',$array["path"],[
            'verify'=>false,
            'query'=>$this->params(array_merge($array["query"],$params))
        ]);

        return $this;
    }

    /**
     * POST提交数据方法
     * @param string $uri
     * @param mixed $data
     * @param array $params
     * @return $this
     */
    public function post($uri,$data,array $params=[]){
        $array = $this->parseUrl($uri);
        $options = array_merge([
            'verify'=>false,
            'query'=>$this->params($array["query"]),
            'body'=>$data
        ],$params);

        $this->response = $this->client->request('POST',$array["path"],$options);
        return $this;
    }

    /**
     * POST表单提交数据方法
     * @param string $uri
     * @param array $params
     * @return $this
     */
    public function formPost($uri,array $params=[]){
        $array = $this->parseUrl($uri);
        $this->response = $this->client->request('POST',$array["path"],[
            'verify'=>false,
            'query'=>$this->params($array["query"]),
            'form_params'=>$params
        ]);

        return $this;
    }

    /**
     * POST提交JSON数据方法
     * @param string $uri
     * @param array $params
     * @param array $headers
     * @return $this
     */
    public function postJson($uri,array $params=[],$headers=[]){
        $array = $this->parseUrl($uri);
        $headers["Content-Type"] = "application/json";
        $this->response = $this->client->request('POST',$array["path"],[
            'headers'=>$headers,
            'verify'=>false,
            'query'=>$this->params($array["query"]),
            'body'=>json_encode($params,JSON_UNESCAPED_UNICODE)
        ]);

        return $this;
    }

    /**
     * 表单上传文件
     * 'multipart' => [
     *      [
     *          'name'     => 'field_name',
     *          'contents' => fopen('/path/to/file', 'r')
     *      ],
     *      [
     *          'name'     => 'other_file',
     *          'contents' => 'hello',
     *          'filename' => 'filename.txt',
     *          'headers'  => [
     *              'X-Foo' => 'this is an extra header to include'
     *          ]
     *      ]
     * ]
     * @param string $uri
     * @param array $params
     * @return $this
     */
    public function upload($uri,array $params=[]){
        $array = $this->parseUrl($uri);
        $this->response = $this->client->request('POST', $array["path"], [
            'verify'=>false,
            'query'=>$this->params($array["query"]),
            'multipart'=>$params,
            'connect_timeout' => 35,
            'timeout' => 35,
            'read_timeout' => 35
        ]);

        return $this;
    }

    /**
     * @param $uri
     * @param array $body
     * @return $this
     * @throws \Exception
     */
    public function uploadBody($uri,array $body){
        $array = $this->parseUrl($uri);
        $this->response = $this->client->request('POST', $array["path"], [
            'verify'=>false,
            'query'=>$this->params($array["query"]),
            'body'=>$body,
            'connect_timeout' => 35,
            'timeout' => 35,
            'read_timeout' => 35
        ]);

        return $this;
    }

    /**
     * 处理请求参数
     * @param array $options
     * @return array
     */
    private function params($options=[]){
        foreach($options as $key=>$value){
            $options[$key] = trim($value);
        }

        return $options;
    }

    /**
     * @param string $uri
     * @return array
     * @throws \Exception
     */
    private function parseUrl($url=""){
        $uri = trim($url);
        if(empty($uri)){
            throw new \Exception("error uri not empty",0);
        }

        if(false !== filter_var($uri,FILTER_VALIDATE_URL)){
            return ["path"=>$uri,"query"=>[]];
        }

        if(strpos($uri,'ACCESS_TOKEN') !== false){
            $uri = str_replace('ACCESS_TOKEN', AccessToken::get(), $uri);
        }

        $data = parse_url($uri);
        $array = [];
        if(!empty($data["query"])){
            foreach(explode("&",$data["query"]) as $key=>$value){
                $arr = explode("=",$value);
                $array[$arr[0]] = trim($arr[1]);
            }
        }

        $data["query"] = $array;
        return $data;
    }

    /**
     * 返回未经处理过数据
     * @return string
     */
    public function getResponse(){
        return $this->response->getBody()->getContents();
    }

    /**
     * 返回 text
     * @return string
     * @throws \Exception
     */
    public function getText(){
        return Response::getResult($this->response->getBody()->getContents(),"text");
    }

    /**
     * 将数据转成数组
     * @return array
     * @throws \Exception
     */
    public function toArray(){
        $content = $this->response->getBody()->getContents();
        if(is_null(json_decode($content))){
            return Response::getResult($content,"xml");
        }

        return Response::getResult($content,"array");
    }

    /**
     * 将数据转成对象
     * @return object
     * @throws \Exception
     */
    public function toObject(){
        return Response::getResult($this->response->getBody()->getContents(),"object");
    }
}