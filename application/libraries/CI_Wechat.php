<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 *  微信公众平台PHP-SDK, Codeigniter实例
 *  @author nigelvon@gmail.com
 *  @link https://github.com/dodgepudding/wechat-php-sdk
 *  usage:
 *  $this->load->library('CI_Wechat');
 *  $this->ci_wechat->valid();
 *  ...
 *
 */
require 'wechat.class.php';
class CI_Wechat extends Wechat {

    protected $_CI;

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_CI->config->load('wechat');
        $options = $this->_CI->config->item('wechat');

        $this->_CI->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        parent::__construct($options);
    }

    /**
     * 重载设置缓存
     * @param string $cachename
     * @param mixed $value
     * @param int $expired
     * @return boolean
     */
    protected function setCache($cachename, $value, $expired)
    {
        return $this->_CI->cache->save($cachename, $value, $expired);
    }

    /**
     * 重载获取缓存
     * @param string $cachename
     * @return mixed
     */
    protected function getCache($cachename)
    {
        return $this->_CI->cache->get($cachename);
    }

    /**
     * 重载清除缓存
     * @param string $cachename
     * @return boolean
     */
    protected function removeCache($cachename)
    {
        return $this->_CI->cache->delete($cachename);
    }

    /**
     * 判断是当前环境是否为微信内置浏览器
     * @return boolean
     */
    public function isWeixinBrowser()
    {
        return strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false;
    }

    /**
     * 获取当前访问的完整url
     * 如 http://benefit.lovchun.com/benefit/index
     * @return string
     */
    public function getCurUrl()
    {
        $url = 'http://';
        if (isset($_SERVER ['HTTPS']) && $_SERVER ['HTTPS'] == 'on') {
            $url = 'https://';
        }
        if ($_SERVER ['SERVER_PORT'] != '80') {
            $url .= $_SERVER ['HTTP_HOST'] . ':' . $_SERVER ['SERVER_PORT'] . $_SERVER ['REQUEST_URI'];
        } else {
            $url .= $_SERVER ['HTTP_HOST'] . $_SERVER ['REQUEST_URI'];
        }
        // 防止出现http://h5.tom.com/game/index&openid=xxxxx的情况出现
        if (stripos($url, '?') === false) {
            $url .= '?t=' . 1008611;
        }
        return $url;
    }

    
    
       /**
     * 获取当前粉丝的openid
     * OAuth2.0授权并缓存
     * @param type $openid
     * @return type
     */
    public function getOpenId($openid = NULL) {
        if ($openid !== NULL) {
            $this->_CI->session->set_userdata('openid', $openid);
        } elseif (!empty($_GET['openid'])) {
            $this->_CI->session->set_userdata('openid', $_GET['openid']);
        }
        $openid = $this->_CI->session->userdata('openid');
        if (empty($openid)) {
            $callback = $this->getCurUrl();
            $this->OAuthWeixin($callback);
        }

        if (empty($openid)) {
            return -1;
        } else {
            echo $openid:exit();
            // 将粉丝的follow id 存入session中
//            $userinfo = $this->_CI->FollowModel->getUserInfoByOpenId($openid);
//
//            $follow_id = $this->_CI->session->userdata('follow_id');
//            if (!$follow_id) {
//                $this->_CI->session->set_userdata('follow_id', $userinfo['id']);
//            }
        }
        return $openid;
    }

    /**
     * 微信授权登录
     * @param type $callback
     */
    public function OAuthWeixin($callback) {

        $refresh_token = $this->_CI->session->tempdata('refresh_token');
        $oauth_access_token = $this->_CI->session->tempdata('oauth_access_token');
        $oauth_openid = $this->_CI->session->userdata('oauth_openid');

        if ($refresh_token && !$oauth_access_token) {
            // 刷新access token并续期
            $refresh = $this->getOauthRefreshToken($refresh_token);
            $oauth_access_token = $refresh['access_token'];
        }

        if (!empty($oauth_access_token) && !empty($oauth_openid)) {
            $data['access_token'] = $oauth_access_token;
            $data['openid'] = $oauth_openid;
        } else {
            $data = $this->getOauthAccessToken();
        }

        if (!$data) {
//            redirect($this->getOauthRedirect($callback));
            $url = $this->getOauthRedirect($callback);
            header("Location:".$url);
        } else {
            // 通过access_token 换取用户详情
            $info = $this->getOauthUserinfo($data['access_token'], $data['openid']);
            var_dump($info);exit();
            // 录入数据库
//            $this->_CI->FollowModel->autoWechatReg($info);

            header('Location:' . $callback . '&openid=' . $data['openid']);
        }
    }

    /**
     * 记录日志
     * ./application/log/logs.txt
     * @param type $text
     */
    public function log($text) {
        $text = is_array($text) ? serialize($text) : $text;
        $time = date('Y-m-d H:i:s', time());
        $dividingLine = '=========================================';
        file_put_contents(APPPATH . '/logs/log.txt', $text . "\n" . $time . "\n" . $dividingLine . "\n", FILE_APPEND);
    }

}


/* End of file CI_Wechat.php */
/* Location: ./application/libraries/CI_Wechat.php */
