<?php
require_once(dirname(__FILE__).'/../libraries/afterlogic/api.php');
require_once(dirname(__FILE__).'/libs/common.class.php');
require_once(dirname(__FILE__).'/config.inc.php');

$common = Common::getInstance();
if (class_exists('CApi') && CApi::IsValid() && isset($_REQUEST['usermail']) && isset($_REQUEST['password']) && isset($_REQUEST['token'])) {
    $userinfo = $common->getUserInfo($_REQUEST['token']);
    if (!$userinfo) {
		exit($common->generateMsg(403, '当前会话密钥无效，请尝试重新登录平台。', null));
    }
    $conn = DB::getInstance(constant('DBHOST').':'.constant('DBPORT'), constant('DBUSER'), constant('DBPSWD'), constant('DBNAME'))->connect();
    $result = $common->bindUserInfo($conn, $userinfo->username, $userinfo->usermail, Encrypt::getInstance(constant('SECKEY'))->encrypt($_REQUEST['password']));
    if ($result) {
        $login_array = array(
			'mailaddr'	=>	$userinfo->usermail,
			'mailtoken'	=>	CApi::GenerateSsoToken($userinfo->usermail, $_REQUEST['password'])
		);
        exit($common->generateMsg(200, '用户绑定成功，正等待客户端使用token信息发起登录确认。', $login_array));
    }
    else {
        exit($common->generateMsg(500, '用户绑定失败，请稍候重试。如该现象多次出现，请联系站点管理员！', $userinfo));
    }
}
else {
    exit($common->generateMsg(405, '非法请求，或站点已暂停外部用户绑定请求。请与站点管理员联系获取更多详情！', null));
}
