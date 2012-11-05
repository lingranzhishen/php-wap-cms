<?php

/*
 * Tiger 公共文件
 *
 */

$tiger_time_begin = microtime(true);

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
  die('require PHP 5.0+ ');
}

$tiger_tmp = __FILE__;

if (!$tiger_tmp)
  die('__FILE__ is undefined ');
if (!defined('TIGER_PATH')) {
  define('TIGER_PATH', dirname($tiger_tmp));
}

$tiger_tmp = $_SERVER['SCRIPT_FILENAME'];
if (!$tiger_tmp)
  die('SCRIPT_FILENAME is undefined ');
if (!defined('APP_PATH')) {
  define('APP_PATH', dirname($tiger_tmp));
}

unset($tiger_tmp);

//引进文件
include 'include.php';

//TODO 

class Tiger extends Tiger_mountain {

  function __construct() {
    return Tiger_mountain::init();
  }

}

// 记录加载文件时间
$tiger_time_load = microtime(true);


