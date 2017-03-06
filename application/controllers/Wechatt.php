<?php

/**
 *
 */
class Wechatt extends CI_Controller {

    public function index()
    {
        $this->load->library('CI_Wechat');
        $this->ci_wechat->test();
    }

    public function testWechat()
    {
        $this->load->library('Myclass');
        $this->myclass->test();
    }
    /**
     * 引入CI
     */
    public function includeWechat(){
        $this->load->library('CI_Wechat');
        $this->ci_wechat->test();
    }
}

?>