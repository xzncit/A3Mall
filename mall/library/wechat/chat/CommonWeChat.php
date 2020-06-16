<?php
namespace mall\library\wechat\chat;

use mall\library\wechat\chat\lib\File;
use think\facade\Cache;

class CommonWeChat {

    protected $config = [];
    protected $request = [];
    protected $access_token = '';

    public function get($url, $query = [], $options = []){
        $options['query'] = $query;
        return $this->httpRequest('get', $url, $options);
    }

    public function httpGet($url){
        try {
            return $this->json2arr($this->get($url));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function post($url, $data = [], $options = []){
        $options['data'] = $data;
        return $this->httpRequest('post', $url, $options);
    }

    public function httpPost($url, array $data,$buildToJson = true){
        try {
            return $this->json2arr($this->post($url, $buildToJson ? $this->arr2json($data) : $data));
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function httpRequest($method, $url, $options = []){
        $curl = curl_init();
        // GET参数设置
        if (!empty($options['query'])) {
            $url .= (stripos($url, '?') !== false ? '&' : '?') . http_build_query($options['query']);
        }

        // CURL头信息设置
        if (!empty($options['headers'])) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $options['headers']);
        }

        // POST数据设置
        if (strtolower($method) === 'post') {
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS,is_array($options['data']) ? http_build_query($options['data']) : $options['data']);
        }

        // 证书文件设置
        if (!empty($options['ssl_cer'])) {
            if (file_exists($options['ssl_cer'])) {
                curl_setopt($curl, CURLOPT_SSLCERTTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLCERT, $options['ssl_cer']);
            } else {
                throw new \Exception("Certificate files that do not exist. --- [ssl_cer]");
            }
        }

        // 证书文件设置
        if (!empty($options['ssl_key'])) {
            if (file_exists($options['ssl_key'])) {
                curl_setopt($curl, CURLOPT_SSLKEYTYPE, 'PEM');
                curl_setopt($curl, CURLOPT_SSLKEY, $options['ssl_key']);
            } else {
                throw new \Exception("Certificate files that do not exist. --- [ssl_key]");
            }
        }

        if(strpos($url,'ACCESS_TOKEN') !== false){
            $url = $this->replaceAccessToken($url);
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_TIMEOUT, 60);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        list($content) = [curl_exec($curl), curl_close($curl)];

        return $content;
    }

    protected function replaceAccessToken($url){
        if (empty($this->access_token)) {
            $this->access_token = $this->getAccessToken();
        }

        return str_replace('ACCESS_TOKEN', $this->access_token, $url);
    }

    /**
     * 获取访问accessToken
     * @return string
     */
    public function getAccessToken(){
        if (!empty($this->access_token)) {
            return $this->access_token;
        }

        $cache = $this->config["appid"] . '_access_token';
        $this->access_token = Cache::get($cache);
        if (!empty($this->access_token)) {
            return $this->access_token;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid={$this->config["appid"]}&secret={$this->config["appsecret"]}";
        $result = $this->json2arr($this->get($url));
        if (!empty($result['access_token'])) {
            Cache::set($cache, $result['access_token'], 7000);
        }

        $this->access_token = $result['access_token'];
        return $result['access_token'];
    }

    protected function deleteAccessToken(){
        $this->access_token = "";
        return Cache::delete($this->config["appid"] . '_access_token');
    }

    /**
     * 数组转XML内容
     */
    protected function arr2xml($data){
        return "<xml>" . $this->_arr2xml($data) . "</xml>";
    }

    /**
     * XML内容生成
     */
    private function _arr2xml($data, $content = ''){
        foreach ($data as $key => $val) {
            is_numeric($key) && $key = 'item';
            $content .= "<{$key}>";
            if (is_array($val) || is_object($val)) {
                $content .= $this->_arr2xml($val);
            } elseif (is_string($val)) {
                $content .= '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
            } else {
                $content .= $val;
            }
            $content .= "</{$key}>";
        }
        return $content;
    }

    /**
     * 解析XML内容到数组
     */
    protected function xml2arr($xml){
        $entity = libxml_disable_entity_loader(true);
        $data = (array)simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        libxml_disable_entity_loader($entity);
        return json_decode(json_encode($data), true);
    }

    /**
     * 数组转xml内容
     * @param array $data
     * @return null|string|string
     */
    public function arr2json($data){
        $json = json_encode($data, JSON_UNESCAPED_UNICODE);
        return $json === '[]' ? '{}' : $json;
    }

    /**
     * 解析JSON内容到数组
     */
    protected function json2arr($json){
        $result = json_decode($json, true);
        if (empty($result)) {
            throw new \Exception('invalid response.', '0');
        }

        if (!empty($result['errcode'])) {
            throw new \Exception($result['errmsg'], $result['errcode']);
        }

        return $result;
    }

    /**
     * 数组KEY全部转小写
     */
    public function strToLower(array $data){
        $data = array_change_key_case($data, CASE_LOWER);
        foreach ($data as $key => $vo) if (is_array($vo)) {
            $data[$key] = $this->strToLower($vo);
        }
        return $data;
    }

    /**
     * 创建CURL文件对象
     */
    public function createCurlFile($filename, $mimetype = null, $postname = null){
        if (is_string($filename) && file_exists($filename)) {
            if (is_null($postname)) {
                $postname = basename($filename);
            }

            if (is_null($mimetype)) {
                $mimetype = File::getExtMine(pathinfo($filename, 4));
            }

            if (function_exists('curl_file_create')) {
                return curl_file_create($filename, $mimetype, $postname);
            }

            return "@{$filename};filename={$postname};type={$mimetype}";
        }

        return $filename;
    }

}