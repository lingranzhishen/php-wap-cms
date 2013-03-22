<?php

/*
 * Tiger 加载文件
 *
 */

if (version_compare(PHP_VERSION, '5.2.0', '<')) {
  include TIGER_PATH . '/common/compat.php';
}

/* 引进系统函数 */
require TIGER_PATH . '/common/include.php';


/* 引进系统类 */

//引进核心类
require TIGER_PATH . '/core/Base.php'; //1
require TIGER_PATH . '/core/Error.php'; //10
require TIGER_PATH . '/core/Lang.php'; //20

require TIGER_PATH . '/core/Template.php'; //200
//引进数据库类
require TIGER_PATH . '/db/DB.php';

$_tiger_mami = Tiger_mountain::findTiger();

class Tiger_mountain {

  private static $_instance = null;
  private static $_instances = null;
  private static $_db = null;
  private static $_error = null;
  private static $_lang = null;
  private static $_template = null;
  private static $_config = null;
  private static $_halt = null;

  private function __construct() {
    self::$_instances = array();
  }

  public static function findTiger() {
    if (!isset(self::$_instance)) {
      $cls = __CLASS__;
      self::$_instance = new $cls;
    }
    return self::$_instance;
  }

  public function __clone() {
    die("Mountain Cannot Accommodate Two Tigers");
  }

  function setConfig($config) {
    self::$_config = $config;
  }

  function getConfig() {
    return self::$_config;
  }

  function setHalt($funcName) {
    if (function_exists($funcName)) {
      self::$_halt = $funcName;
      if (isset(self::$_instances)) {
        foreach (self::$_instances as &$instance) {
          $instance->setHalt($funcName);
        }
      }
    }
  }

  function db($argu = true) {
    if (!isset(self::$_db)) {
      self::$_db = new Tiger_db();
      if (true === $argu) {
        $conf = self::$_config['db'];
        self::$_db->connect($conf['host'], $conf['user'], $conf['pwd'], $conf['db_name'], $conf['char'], $conf['pc']);
      }
      self::$_instances[] = &self::$_db;
      if (isset(self::$_halt)) {
        self::$_db->setHalt(self::$_halt);
      }
    }
    return self::$_db;
  }

  function database() {
    $db = new Tiger_db();
    self::$_instances[] = &$db;
    if (isset(self::$_halt)) {
      $db->setHalt(self::$_halt);
    }
    return $db;
  }

  function error() {
    if (!isset(self::$_error)) {
      self::$_error = new Tiger_error();
    }
    return self::$_error;
  }

  function lang($argu = true) {
    $conf = self::$_config['lang'];
    if (!isset(self::$_lang)) {
      self::$_lang = new Tiger_lang();
      if (true === $argu) {
        self::_loadLang($conf['local'], $conf['path']);
      }
      self::$_instances[] = &self::$_lang;
      if (isset(self::$_halt)) {
        self::$_lang->setHalt(self::$_halt);
      }
    }
    if (is_string($lang = $argu)) {
      self::_loadLang($lang, $conf['path']);
    }
    return self::$_lang;
  }

  private function _loadLang($local, $path) {
    $php = APP_PATH . "/$path/" . $local . ".php";
    if (file_exists($php)) {
      $lang = include_once $php;
      if ($lang) {
        self::$_lang->locale($local);
        self::$_lang->set($lang, $local);
      }
    }
  }

  function template($autoSet = true) {
    if (!isset(self::$_template)) {
      self::$_template = new Tiger_template();
      if (true === $autoSet) {
        self::$_template->setOptions(self::$_config['template']);
      }
      self::$_instances[] = &self::$_template;
      if (isset(self::$_halt)) {
        self::$_template->setHalt(self::$_halt);
      }
    }
    return self::$_template;
  }

}

