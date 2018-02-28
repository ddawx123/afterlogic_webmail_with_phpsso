<?php
/**
 * 数据库驱动类精简版
 * 本类库不支持自适应机制，仅适用于mysql服务器
 * @package DingStudio/DBDriver_Minimal
 * @author David Ding
 */

class DB {

    static private $_instance = null; //定义实例化空白对象
    static private $_sqlcon;

    private $_hostname = 'localhost:3306'; //定义数据库服务器地址+端口
    private $_username = 'root'; //定义数据库帐号
    private $_password = 'root'; //定义数据库密码
    private $_database = 'test'; //定义数据库名称

    /**
     * 构造函数（实现批量设置连库信息）
     * @param string $h 主机地址
     * @param string $u 用户名
     * @param string $p 密码
     * @param string $d 库名称
     */
    private function __construct($h, $u ,$p ,$d) {
        $this->_hostname = $h;
        $this->_username = $u;
        $this->_password = $p;
        $this->_database = $d;
    }

    /**
     * 统一实例入口（入口同时提供连库信息）
     * @param string $h 主机地址
     * @param string $u 用户名
     * @param string $p 密码
     * @param string $d 库名称
     * @return mixed 实例对象
     */
    static public function getInstance($h, $u, $p, $d) {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self($h, $u, $p, $d);
        }
        return self::$_instance;
    }

    /**
     * 数据库连接建立过程
     * @return mixed {成功返回连接句柄/失败返回布尔值FALSE}
     */
    public function connect() {
        if (!self::$_sqlcon) {
            self::$_sqlcon = mysqli_connect($this->_hostname, $this->_username, $this->_password);
            if (!self::$_sqlcon) {
                //throw new Exception('MySQL Server connect failed, error detail: '.mysqli_error());
                return false;
            }
            mysqli_select_db(self::$_sqlcon, $this->_database);
            self::$_sqlcon->query('set names UTF8');
        }
        return self::$_sqlcon;
    }
    /**
     * 数据库连接释放过程
     * @return boolean 释放结果
     */
    public function close() {
        if (!self::$_sqlcon) { // 判断是否已有线上的连接句柄
            return false;
        }
        $result = self::$_sqlcon->close(); // 尝试关闭已有连接句柄并返回操作结果
        if ($result) { // 关闭成功
            return true;
        }
        else { // 关闭失败
            return false;
        }
    }
}