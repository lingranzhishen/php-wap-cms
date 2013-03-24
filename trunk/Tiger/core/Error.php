<?php

/*
 * Tiger 错误类
 */

class Tiger_error {

  function __construct() {
    
  }

  //警告但不阻断程序
  function warn($msg) {
    //TODO log
    echo $msg;
  }

  //警告并阻断程序
  function call($msg) {
    //TODO log
    die($msg);
  }

}

