<?php
/**
 * 数据加密类
 * @package DingStudio/DataEncrypt
 * @author David Ding
 */

class Encrypt {

    static private $_instance = null; //定义实例化空白对象
    private $_skey = 0; //定义加密密钥

    /**
     * 构造函数
     * @param string $skey_value 加密密钥
     */
    private function __construct($skey_value) {
        $this->_skey = $skey_value;
    }
    
    /**
     * 统一实例入口
     * @param string $skey_value 加密密钥
     * @return mixed 实例对象
     */
    static public function getInstance($skey_value) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($skey_value);
        }
        return self::$_instance;
    }

    /**
     * 数据加密函数
     * @param string $data 所需加密的数据
     * @return string 加密后的密文数据
     */
    public function encrypt($data) {
        $skey = sha1($this->_skey);
        $x = 0;
        $data_length = strlen($data);
        $skey_length = strlen($skey);
        $char = '';
        $str = '';
        for ($i = 0; $i < $data_length; $i++) {
            if ($x == $skey_length) {
                $x = 0;
            }
            $char .= $skey{$x};
            $x++;
        }
        for ($i = 0; $i < $data_length; $i++) {
            $str .= chr(ord($data{$i}) + (ord($char{$i})) % 256);
        }
        return base64_encode($str);
    }

    /**
     * 数据解密函数
     * @param string $data 所需解密的数据
     * @return string 解密后的原文数据
     */
    public function decrypt($data) {
        $skey = sha1($this->_skey);
        $x = 0;
        $data = base64_decode($data);
        $data_length = strlen($data);
        $skey_length = strlen($skey);
        $char = '';
        $str = '';
        for ($i = 0; $i < $data_length; $i++) {
            if ($x == $skey_length) {
                $x = 0;
            }
            $char .= substr($skey, $x, 1);
            $x++;
        }
        for ($i = 0; $i < $data_length; $i++) {
            if (ord(substr($data, $i, 1)) < ord(substr($char, $i, 1))) {
                $str .= chr((ord(substr($data, $i, 1)) + 256) - ord(substr($char, $i, 1)));
            }
            else {
                $str .= chr(ord(substr($data, $i, 1)) - ord(substr($char, $i, 1)));
            }
        }
        return $str;
    }
}
