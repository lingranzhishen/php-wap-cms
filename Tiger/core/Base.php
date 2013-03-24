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

  protected $_haltFunc = null;
  protected $_debugMode = null;

  /**
   * 中断函数
   * @access protected
   * @param $msg 打印信息
   */
  protected function halt() {
    $params = func_get_args();
    $halt = isset($this->_haltFunc) ? $this->_haltFunc : 'tiger_halt';
    call_user_func_array($halt, $params);
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

}