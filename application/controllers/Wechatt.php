<?php

/**
 *
 */
class Wechatt extends CI_Controller {

    public function index()
    {
        $this->load->library('CI_Wechat');
        $appid = 'wxd8e911e6cf0b7ed0';
        $appsecret = '87dc05c99d168869fd9ecd6f213196ef';
        $token = 'LGwechat';
        $redirect_uri = urlencode ( 'http://lg.im-rice.com/' );
        $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
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
        
//        获取acess_token
        $access_token = $this->ci_wechat->checkAuth();
        $openid = "o6nPS0hZQY1B3Vdadw1jToroZJ08";
        $userMsg = $this->ci_wechat->getUserInfo($openid);

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