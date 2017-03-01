<?php
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
  				array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
   				)
 		);
   $result = $weObj->createMenu($newmenu);