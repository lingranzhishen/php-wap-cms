<?php
/*
 * Tiger 公共文件
 *
 */

if(version_compare(PHP_VERSION,'5.0.0','<') ) {
    die('require PHP 5.0+ ');
}

$GLOBALS['_beginTime'] = microtime(true);

if(!defined('TIGER_PATH')) define('TIGER_PATH', dirname(__FILE__));

if(!defined('APP_PATH')) define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']));

//TODO ?
include TIGER_PATH.'/db/DB.php';
if(version_compare(PHP_VERSION,'5.2.0','<') ) {
	include TIGER_PATH.'/common/compat.php';
}
//TODO ?

// 记录加载文件时间
$GLOBALS['_loadTime'] = microtime(true);

echo $GLOBALS['_loadTime'] -$GLOBALS['_beginTime']."<br/>";