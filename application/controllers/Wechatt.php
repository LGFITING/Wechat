<?php

/**
 *
 */
class Wechatt extends CI_Controller {

    public function index()
    {
        $this->load->library('CI_Wechat');
        $menu = $this->ci_wechat->getMenu();
          $newmenu =  array(
   		"button"=>
  			array(
  				array('type'=>'click','name'=>'最新消息','key'=>'MENU_KEY_NEWS'),
  				array('type'=>'view','name'=>'我要搜索','url'=>'http://www.baidu.com'),
   				)
		);
  $result = $this->ci_wechat->createMenu($newmenu);
    }
}

?>