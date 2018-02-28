<?php
require_once(dirname(__FILE__).'/../libraries/afterlogic/api.php');
require_once(dirname(__FILE__).'/libs/common.class.php');
require_once(dirname(__FILE__).'/config.inc.php');
$common = Common::getInstance();
if (class_exists('CApi') && CApi::IsValid() && isset($_REQUEST['token'])) {
	$userinfo = $common->getUserInfo($_REQUEST['token']);
	if (!$userinfo) {
		exit($common->generateMsg(403, '当前会话密钥无效，请尝试重新登录平台。', null));
	}
	$conn = DB::getInstance(constant('DBHOST').':'.constant('DBPORT'), constant('DBUSER'), constant('DBPSWD'), constant('DBNAME'))->connect();
	$result = $conn->query('select * from mailusers where username="'.$userinfo->username.'"');
	$data = $result->fetch_all(MYSQLI_ASSOC);
	if (!isset($data[0]['usermail'])) {
		exit($common->generateMsg(401, '首次登录webmail，请先绑定或完善用户信息。', $userinfo));
	}
	if ($data[0]['usermail'] == $userinfo->usermail) {
		$login_array = array(
			'mailaddr'	=>	$data[0]['usermail'],
			'mailtoken'	=>	CApi::GenerateSsoToken($data[0]['usermail'], Encrypt::getInstance(constant('SECKEY'))->decrypt($data[0]['password']))
		);
		exit($common->generateMsg(200, '用户互联认证成功，正等待客户端使用token信息发起登录确认。', $login_array));
	}
	else {
		exit($common->generateMsg(500, '您修改了统一身份认证平台绑定邮箱，请重新完善用户信息。', $userinfo));
	}
	//header('Location: ../?sso&hash='.CApi::GenerateSsoToken('someone@example.org', 'example_password_plaintext'));
}
else {
	exit('Single Sign On API isn\'t available');
}