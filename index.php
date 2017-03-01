<?php
header('Content-Type:text/html;charset=utf-8');
include './wechat-php-sdk/wechat.class.php';
//php获取当前访问的完整url地址 
function get_current_url(){ 
    $current_url='http://'; 
    if(isset($_SERVER['HTTPS'])&&$_SERVER['HTTPS']=='on'){ 
        $current_url='https://'; 
    } 
    if($_SERVER['SERVER_PORT']!='80'){ 
        $current_url.=$_SERVER['SERVER_NAME'].':'.$_SERVER['SERVER_PORT'].$_SERVER['REQUEST_URI']; 
    }else{ 
        $current_url.=$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']; 
    } 
    return $current_url; 
}
$current_url = get_current_url();

//scope=snsapi_base 实例
$appid='wxd8e911e6cf0b7ed0';
$redirect_uri = urlencode ( $current_url );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_userinfo&state=1#wechat_redirect";



$appid = "wxd8e911e6cf0b7ed0";  
$secret = "87dc05c99d168869fd9ecd6f213196ef";  
$code = $_GET["code"];

//第一步:取得openid
$oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
$oauth2 = getJson($oauth2Url);
//第二步:根据全局access_token和openid查询用户信息  
$access_token = $oauth2["access_token"];  
$openid = $oauth2['openid']; 

$get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=$access_token&openid=$openid&lang=zh_CN";
$userinfo = getJson($get_user_info_url);
print_r($userinfo);
function getJson($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    return json_decode($output, true);
}
$options = array(
    'token' => 'LGwechat', //填写你设定的key
    'encodingaeskey' => '', //填写加密用的EncodingAESKey
    'appid' => 'wxd8e911e6cf0b7ed0', //填写高级调用功能的app id
    'appsecret' => '87dc05c99d168869fd9ecd6f213196ef' //填写高级调用功能的密钥
);
$weObj = new Wechat($options);
$weObj->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
$type = $weObj->getRev()->getRevType();
switch ($type) {
    case Wechat::MSGTYPE_TEXT:
//        $weObj->text("hello, I'm wechat")->reply();
        $weObj->image('RdvnlzWOKaX72QWk-88TuFyUNBb8F0SkBHUwIb3miJL0SfCR6fLFVcHEN9Vt_P9s')->reply();
        exit;
        break;
    case Wechat::MSGTYPE_EVENT:
        break;
    case Wechat::MSGTYPE_IMAGE:
        break;
    default:
        $weObj->text("help info")->reply();
}
//获取菜单操作:
   $menu = $weObj->getMenu();
//设置菜单
   $newmenu =  array(
   		"button"=>
   			array(
   				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
  				array('type'=>'view','name'=>'我要搜索','url'=>$url),
   				)
 		);
   $result = $weObj->createMenu($newmenu);
   
