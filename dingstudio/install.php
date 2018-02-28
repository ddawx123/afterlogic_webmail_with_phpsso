<?php
header('Content-Type: text/plain; charset=UTF-8');
if (file_exists(dirname(__FILE__).'/install.lock')) {
    exit('Already installed.');
}
require_once(dirname(__FILE__).'/config.inc.php');
if (@constant('DBHOST') == '' || @constant('DBPORT') == '' || @constant('DBUSER') == '' || @constant('DBNAME') == '') {
    exit('Configure file missing');
}
$sqlconn = @mysqli_connect(constant('DBHOST').':'.constant('DBPORT'), constant('DBUSER'), constant('DBPSWD'));
if (!$sqlconn) {
    exit('Could not connect your database server, please check your configure file infomation or your network status and try it again.');
}
$result = $sqlconn->select_db(constant('DBNAME'));
if ($result != 1) {
    exit('Could not open your database, please check your configure file information or your database account \'s permission.');
}
$sqlconn->query('set names utf8');
$code = 'create table mailusers (mid int(15) auto_increment primary key not null, username varchar(255) not null, usermail varchar(255) not null, password varchar(255) not null)';
$result = $sqlconn->query($code);
if (!$result) {
    exit('Already created table: mailusers on your database '.constant('DBNAME').'. If you want to reinstall, please delete it first.');
}
$result = @touch(dirname(__FILE__).'/install.lock');
if (!$result) {
    exit('The installation has been completed, but the installation entrance blocking files can not be created.');
}
exit('Installation has been completed, if you need to reinstall, please delete the installation entrance blocking file.');
