<?php

include './wechat-php-sdk/wechat.class.php';

$options = array(
    'token' => 'LGwechat', //填写你设定的key
    'encodingaeskey' => 'hqNymdJwoFK5ehqOwxZlfvmbStAbPd6M7tbWeCE1spC', //填写加密用的EncodingAESKey
    'appid' => 'wx4e766b6a09b62060', //填写高级调用功能的app id
    'appsecret' => '15af110b956a15d76fd0dfc1ed8fa531' //填写高级调用功能的密钥
);
$weObj = new Wechat($options);
$weObj->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败

//二维码
$type = $weObj->getRev()->getRevType();
switch ($type) {
    case Wechat::MSGTYPE_TEXT:
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