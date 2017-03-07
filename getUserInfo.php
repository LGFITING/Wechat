<?php
header("Content-Type: text/html;charset=utf-8"); 
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
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
//打印用户信息
print_r($userinfo);
header("Location:http://lg.im-rice.com");
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