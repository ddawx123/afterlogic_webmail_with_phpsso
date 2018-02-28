<?php
header('Content-Type: text/plain; charset=UTF-8');
exit('注释此行启动脚本');

require_once(dirname(__FILE__).'/libs/encrypt.class.php');

echo '加密测试：'.Encrypt::getInstance('dcloud20180228132049')->encrypt('P@ssw0rd');
echo ' ';
echo '解密测试：'.Encrypt::getInstance('dcloud20180228132049')->decrypt('s6Sqp9qVq50=');