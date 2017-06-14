<?php
namespace wisderapi;
/**
 * Class wisderapi
 * @package wisderapi
 * example
 * $data=array(
 *    'token'     =>'qw1321qs2wqe',
 *    'secret'    =>'dfdsf12312das123',
 *    'url'       =>'http://60.174.195.124:6041/router/rest',
 * );
 * $wisderapi = new wisderapi($data);
 * $params = array(
 *  'method'=>'trade.list',
 *  'start_time'=>'2017-02-11 13:03:41',
 *  'end_time'=>'2017-06-09 17:03:41',
 *  'shop_type_ids'=>'1,2,3',
 *  'page_no'=>'1',
 *  'page_size'=>'50',
 *  );
 * $result = $wisderapi->send($params);
 * var_dump($result);
 * exit;
 */
class wisderapi {
    public $conf=array();
    public function __construct($conf){
        $this->conf=$conf;
    }
    public function send($data){
        $param =array(
            'timestamp' =>  date("Y-m-d H:i:s",time()),
            'token'     =>$this->conf['token'],
        );
        $params=array_merge($data,$param);
        $params['sign']=$this->get_sign($params);
        $uniurl=$this->unicodeurl($params);     
        $createLinkStrings=$this->createLinkStrings($uniurl);
        $result=$this->getHttp($createLinkStrings);
        return $result;
    }    
    public function unicodeurl($data)
    {
        $uniurl=array();
        foreach ($data as $key => $value) {
          $uniurl[urlencode($key)]=urlencode($value);
        }
        return $uniurl;
    }
    private function get_sign($params){
        $sign_str='';
        $secret_str='';
        ksort($params);
        foreach ($params as $key => $value) {
          $sign_str.= $key . $value;
        }  
        $sign_str=$this->decodeUnicode(($sign_str));
        $secret_str=$this->conf['secret'].$sign_str.$this->conf['secret'];
        return strtoupper(md5($secret_str));
    } 
    public function createLinkStrings($para) {
        $linkString = "";
        while ( list ( $key, $value ) = each ( $para ) ) {
            $linkString .= $key . "=" . $value . "&";
        }
        $linkString = substr ( $linkString, 0, count ( $linkString ) - 2 );
        return $this->conf['url'].'?'.$linkString;
    }
    public function decodeUnicode($str)
    {
        return preg_replace_callback('/\\\\u([0-9a-f]{4})/i',
            create_function(
                '$matches',
                'return mb_convert_encoding(pack("H*", $matches[1]), "UTF-8", "UCS-2BE");'
            ),
            $str);
    }
    public function getHttp($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded; charset=utf-8"));
        curl_setopt($curl, CURLOPT_HEADER, 0 );
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($curl);
        curl_close($curl);
        $result = json_decode($data,true);
        return $result;     
    }


}