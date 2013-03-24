<?php

/*
 * Tiger 日志类
 * 用于网站管理
 */

class Tiger_log {

  protected $pattern = null;
  protected $path = null;
  protected $callback = null;

  function __construct() {
    $this->pattern = "error";
  }

  function setPath($path) {
    $this->path = $path;
  }

  function setCallback() {
    
  }

  function msg($msg) {
    $filename = $this->path . '/' . $this->pattern . '.log';
    $msg = "[" . date("Y-m-d H:i:s") . "] " . $msg . "\r\n";
    tiger_file($filename, $msg, "add");
  }

}