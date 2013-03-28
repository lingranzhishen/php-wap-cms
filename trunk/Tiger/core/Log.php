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

  function setPattern($pattern) {
    $this->pattern = $pattern;
  }

  function setCallback() {

  }

  protected function log($msg, $pattern) {
    $filename = $this->path . '/' . $pattern . '.log';
    $msg = "[" . date("Y-m-d H:i:s") . "] " . $msg . "\r\n";
    tiger_file($filename, $msg, "add");
  }

  function msg($msg) {
    $this->log($msg, $this->pattern);
  }

  function errorlog($msg) {
    $this->log($msg, 'error');
  }

  function accesslog($msg) {
    $this->log($msg, 'access');
  }

  function warnlog($msg) {
    $this->log($msg, 'warning');
  }

}