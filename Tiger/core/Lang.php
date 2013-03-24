<?php

/*
 * Tiger 语言类
 */

class Tiger_lang extends Tiger_base {

  private $lang = null;
  private $local = null;

  function __construct($local = 'en') {
    $this->lang = array();
    $this->local = $local;
    $this->lang[$local] = array();
  }

  function locale($local) {
    if (!isset($this->lang[$local])) {
      $this->lang[$local] = array();
    }
    $this->local = $local;
  }

  function set($langArray, $local = '') {
    if (!is_array($langArray)) {
      //TODO
      $this->Halt("ParamMustBeArray", true);
    }

    if (!$local) {
      $local = $this->local;
    } else {
      $this->locale($local);
    }

    $this->lang[$local] = array_merge($this->lang[$local], $langArray);
  }

  function get($key, $local = '') {
    if (!is_string($key)) {
      //TODO
      return null;
    }

    if (!$local) {
      $local = $this->local;
    } else {
      if (!isset($this->lang[$local])) {
        return null;
      }
    }
    if (!isset($this->lang[$local])) {
      return null;
    }
    return $this->lang[$local][$key];
  }

}

