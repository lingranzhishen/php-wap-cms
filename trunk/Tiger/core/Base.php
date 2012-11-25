<?php

/*
 * Tiger 基础抽象类
 *
 */

/**
 * Tiger 基础抽象类
 * @category   Tiger
 * @package  Tiger
 * @subpackage  DB
 * @author    Vicky
 * @version   $Id$
 */
abstract class Tiger_base {

  protected static $_haltFunc = null;
  protected static $_debugMode = null;

  /**
   * 中断函数
   * @access protected
   * @param $msg 打印信息
   */
  protected function halt($msg) {
    if (!isset($this->_haltFunc)) {
      die("[Tiger]Fatal Error:$msg");
    }
    $params = func_get_args();
    call_user_func_array($this->_haltFunc, $params);
    exit;
  }

  /**
   * 修改中断函数
   * @access public
   * @param $msg 打印信息
   * @return boolean 修改是否成功
   */
  public function setHalt($funcName) {
    if (function_exists($funcName)) {
      $this->_haltFunc = $funcName;
      return true;
    }
    return false;
  }
  
  /**
   * 修改开发状态
   * @param boolean $bool 
   */
  public function setDebugMode($bool){
    if($bool){
      $this->_debugMode = true;
    }else{
      $this->_debugMode = false;
    }
  }
  
  /**
   * 获取开发状态
   * @return boolean 
   */
  public function getDebugMode(){
    if(isset($this->_debugMode)){
      return $this->_debugMode;
    }
    return false;
  }

  // /**
  // * 自动变量设置
  // * @access public
  // * @param $name 属性名称
  // * @param $value  属性值
  // */
  // public function __set($name, $value) {
  // if(property_exists($this, $name)){
  // $this->$name = $value;
  // }
  // }
  // /**
  // * 自动变量获取
  // * @access public
  // * @param $name 属性名称
  // * @return mixed
  // */
  // public function __get($name) {
  // if(isset($this->$name)){
  // return $this->$name;
  // }else {
  // return null;
  // }
  // }
}