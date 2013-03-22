<?php

/*
 * Tiger 公共文件
 *
 */

$_tiger_time_begin = microtime(true);

if (version_compare(PHP_VERSION, '5.0.0', '<')) {
  die('require PHP 5.0+ ');
}

if (!__FILE__)
  die('__FILE__ is undefined ');
if (!defined('TIGER_PATH')) {
  define('TIGER_PATH', dirname(__FILE__));
}

if (!$_SERVER['SCRIPT_FILENAME'])
  die('SCRIPT_FILENAME is undefined ');
if (!defined('APP_PATH')) {
  define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']));
}


//DIRECTORY_SEPARATOR in Windows would be "\" while in Unix it would be "/".
define('DIR_SEP', DIRECTORY_SEPARATOR);


//引进文件
include 'include.php';

//TODO

class Tiger extends Tiger_mountain {

  private $ver = null;

  function __construct() {
    $this->ver = 1;
    return Tiger_mountain::findTiger();
  }

  function getVersion(){
    return $this->ver;
  }

}

// 记录加载文件时间
$_tiger_time_load = microtime(true);


