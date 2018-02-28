<?php

class SSO {

    static private $_instance = null; //定义实例化空白对象

    /**
     * 构造函数
     */
    private function __construct() {}
    
    /**
     * 统一实例入口
     * @return mixed 实例对象
     */
    static public function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * 从已有SSO会话获取用户资料
     * @param string $token 会话密钥
     * @return mixed 如未登录则返回false，已登录则返回用户资料数据集合
     */
    public function getUserInfo($token) {
        //TODO
    }

    public function bindUserInfo() {
        //TODO
    }

    public function delUserInfo() {
        //TODO
    }
}