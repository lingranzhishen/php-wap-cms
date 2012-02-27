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
abstract class Base
{

    /**
     * 自动变量设置
     * @access public
     * @param $name 属性名称
     * @param $value  属性值
     */
    public function __set($name, $value) {
        if(property_exists($this, $name)){
            $this->$name = $value;
        }
    }

    /**
     * 自动变量获取
     * @access public
     * @param $name 属性名称
     * @return mixed
     */
    public function __get($name) {
        if(isset($this->$name)){
            return $this->$name;
        }else {
            return null;
        }
    }
}