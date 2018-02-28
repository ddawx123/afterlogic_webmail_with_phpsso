# afterlogic_webmail_with_phpsso
基于afterlogic-webmail二次开发实现跨域sso的版本

- third-login.php -> SSO前台互联登录入口
- dingstudio[directory] -> SSO实现目录
- dingstudio/config.inc.php -> 应用配置文件，请酌情修改
- dingstudio/ssologin.htm -> 统一身份认证前端入口
- dingstudio/install.php -> 数据库表安装（请确保config.inc.php配置正确后启动该程序）
- dingstudio/sso-login.php -> 互联登录API
- dingstudio/bind-user.php -> 新用户绑定API
- dingstudio/libs[directory] -> 类库目录（包含数据库驱动类，基础功能封装类与数据加密类）
- dingstudio/* -> 其他在此不做介绍，为通用库

### &copy;2012-2018 DingStudio Technology All Rights Reserved. QQ Number: 954759397