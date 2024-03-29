<?php

/*
 * Tiger 公共文件
 *
 */

$_tiger_time_begin = microtime(true);

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
  die('require PHP 5.0+ ');
}

if (!defined('TIGER_PATH')) {
  define('TIGER_PATH', dirname(__FILE__));
}

if (!defined('APP_PATH')) {
  define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']));
}


//DIRECTORY_SEPARATOR in Windows would be "\" while in Unix it would be "/".
define('DIR_SEP', DIRECTORY_SEPARATOR);


//引进文件
include 'include.php';

//TODO

class Tiger extends Tiger_mountain {

  function __construct() {
    return Tiger_mountain::findTiger();
  }

}

// 记录加载文件时间
$_tiger_time_load = microtime(true);


