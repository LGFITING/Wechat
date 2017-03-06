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


        $this->ci_wechat->valid(); //明文或兼容模式可以在接口验证通过后注释此句，但加密模式一定不能注释，否则会验证失败
        $this->ci_wechat->getRev()->getRevType();
        
        switch($type) {
	case Wechat::MSGTYPE_TEXT:
			$this->ci_wechat->text("hello, I'm wechat")->reply();
			exit;
			break;
	case Wechat::MSGTYPE_EVENT:
			break;
	case Wechat::MSGTYPE_IMAGE:
			break;
	default:
			$this->ci_wechat->text("help info")->reply();
}
        
//        $userMsg = $this->ci_wechat->getUserInfo();
//        $user = json_decode($userMsg);
//        var_dump($user);
    }

}

?>