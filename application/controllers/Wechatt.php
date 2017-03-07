<?php

/**
 *
 */
class Wechatt extends CI_Controller {

    function get_current_url()
    {
        $current_url = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on') {
            $current_url = 'https://';
        }
        if ($_SERVER['SERVER_PORT'] != '80') {
            $current_url.=$_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . $_SERVER['REQUEST_URI'];
        } else {
            $current_url.=$_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        }
        return $current_url;
    }

    public function index()
    {
        $this->load->library('CI_Wechat');   
        $callback = $this->get_current_url();
        $Auth_openid = $this->getOpenId($openid = NULL);
        if(isset($Auth_openid)){
            echo $Auth_openid;
        }
//        header('Location:'.$url);
//        $access_token = $this->ci_wechat->getOauthAccessToken();

//        创建菜单
        $menu = $this->ci_wechat->getMenu();
        $newmenu = array(
            "button" =>
            array(
                array('type' => 'click', 'name' => '最新消息', 'key' => 'MENU_KEY_NEWS'),
                array('type' => 'view', 'name' => '首页', 'url' => $url),
            )
        );
        $result = $this->ci_wechat->createMenu($newmenu);
        $options = array(
            'token' => 'LGwechat', //填写你设定的key
            'encodingaeskey' => '' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        );
        $weObj = new Wechat($options);
        $weObj->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $weObj->getRev()->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $weObj->text($access_token)->reply();
                exit;
                break;
            case Wechat::MSGTYPE_EVENT:
                break;
            case Wechat::MSGTYPE_IMAGE:
                break;
            default:
                $weObj->text("help info")->reply();
        }
    }

}

?>