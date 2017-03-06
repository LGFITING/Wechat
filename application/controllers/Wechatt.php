<?php

/**
 *
 */
class Wechatt extends CI_Controller {

    public function index()
    {
        $this->load->library('CI_Wechat');
        $menu = $this->ci_wechat->getMenu();
        $newmenu = array(
            "button" =>
            array(
                array('type' => 'click', 'name' => '最新消息', 'key' => 'MENU_KEY_NEWS'),
                array('type' => 'view', 'name' => '我要搜索', 'url' => 'http://www.baidu.com'),
            )
        );
        $result = $this->ci_wechat->createMenu($newmenu);
        $openid = 'o6nPS0hZQY1B3Vdadw1jToroZJ08';
        $userMsg = $this->ci_wechat->getUserInfo($openid);
        if(isset($userMsg)){
            $user = $userMsg;
        }else{
            $user = '无法获取';
        }
        
        $options = array(
            'token' => 'LGwechat', //填写你设定的key
            'encodingaeskey' => '' //填写加密用的EncodingAESKey，如接口为明文模式可忽略
        );
        $weObj = new Wechat($options);
        $weObj->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $type = $weObj->getRev()->getRevType();
        switch ($type) {
            case Wechat::MSGTYPE_TEXT:
                $weObj->text($user)->reply();
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