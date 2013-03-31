<?php

/*
 * Tiger 数据库操作
 *
 */

require_once TIGER_PATH . '/db/driver/db.php';

/**
 * Tiger 数据库操作类
 * @category   Tiger
 * @package  Tiger
 * @subpackage  DB
 * @author    Vicky
 * @version   $Id$
 */
class Tiger_db extends Tiger_base {

  //数据库操作实例
  private $db = null;
  private $path = null;

  function __construct($dbType = 'mysql') {
    $this->path = dirname(__FILE__);
    $this->_init($dbType);
  }

  function __destruct() {
    $this->db = null;
  }

  private function _init($dbType) {
    $cls = 'Tiger_' . $dbType;
    $php = $this->path . '/driver/db.' . $dbType . '.php';
    if (file_exists($php)) {
      include_once $php;
    } else {
      //TODO Log "数据库没有提供此接口：$dbType";
      $this->halt("DatabaseHasNoTheInterface");
    }
    if (class_exists($cls))
      $this->db = new $cls();
    else {
      //TODO Log '数据库操作类加载失败';
      $this->halt("DatabaseClassFailToLoad");
    }
    if (!$this->db) {
      //TODO Log '数据库连接出错';
      $this->halt("DatabaseFailToConnect");
    }
  }

  /**
   *  数据库连接
   * @param String $host 数据库服务器地址
   * @param String $user 数据库用户名
   * @param String $pwd 数据库用户密码
   * @param String $db  数据库名称
   * @param String $char 数据库编码
   * @param Boolean $pc 是否打开长连接
   */
  function connect($host, $user, $pwd, $db, $char, $pc = false) {
    $this->db->connect($host, $user, $pwd, $db, $pc);
    if ($char) {
      $this->db->setCharset($char);
    }
  }

  /**
   * 数据库查询
   * @param String $sql 执行一条SQL语句
   * @return  数据库资源
   */
  function query($sql) {
    $rs = $this->db->execute($sql);
    //TODO Log
    return $rs;
  }

  /**
   * 数据库查询
   * @param String $sql 执行一条SQL语句
   * @return mixed 结果集数组，没有记录返回false
   */
  function getArray($sql) {
    return $this->db->getArray($sql);
  }

  /**
   * 数据库查询
   * 用于发送数据，减少网络传输的数据量，客户端须做数据转换
   * @param String $sql 执行一条SQL语句
   * @return Array 结果集特殊数组 array('field' => array(), 'data' => array());
   */
  function getArrayX($sql) {
    $arr = $this->db->getArrayX($sql);
    return $arr;
  }

  /**
   * 数据库分页查询
   * @param String $sql 执行一条SQL语句
   * @param Int $size 单页最大条数
   * @param Int $page 第几页
   * @param & $count 数据库总记录指针，用于返回总记录数
   * @return mixed 结果集数组，没有记录返回false
   */
  function pageArray($sql, $size, $page, &$count = null) {
    return $this->db->pageArray($sql, $size, $page, $count);
  }

  /**
   * 数据库分页查询
   * 用于发送数据，减少网络传输的数据量，客户端须做数据转换
   * @param String $sql 执行一条SQL语句
   * @param Int $size 单页最大条数
   * @param Int $page 第几页
   * @param & $count 数据库总记录指针，用于返回总记录数
   * @return Array 结果集特殊数组 array('field' => array(), 'data' => array());
   */
  function pageArrayX($sql, $size, $page, &$count = null) {
    return $this->db->pageArrayX($sql, $size, $page, $count);
  }

  /**
   * 查询一个记录值
   * @param String $sql 执行一条SQL语句
   * @param boolean $limit 是否只查询一条记录
   * @return mixed 一个记录值，没有记录返回false
   */
  function getOne($sql, $limit = false) {
    if ($limit) {
      $sql .= " limit 1";
    }
    $rs = $this->db->getOne($sql);
    return $rs;
  }

  /**
   * 查询一条记录集
   * @param String $sql 执行一条SQL语句
   * @param boolean $limit 是否只查询一条记录
   * @return mixed 一条记录集，没有记录返回false
   */
  function getRow($sql, $limit = false) {
    if ($limit) {
      $sql .= " limit 1";
    }
    $rs = $this->db->getRow($sql);
    return $rs;
  }

  /**
   * 取得上一步 INSERT 操作产生的 ID
   * @return int
   */
  function getInsertID() {
    return $this->db->insertID();
  }

  /**
   * 取得上一步 INSERT 操作产生的 ID，比 getInsertID 更精确，但消耗较大
   * 同时适用于“ON DUPLICATE KEY UPDATE”查询
   * @return int
   */
  function getLastInsertID() {
    return $this->db->lastInsertID();
  }

  /**
   * 取得（本次/上一次）查询的记录数，或影响的记录数
   * @return int
   */
  function getCount($sql = "") {
    return $this->db->getCount($sql);
  }

  /**
   * 关闭数据库
   * @return Boolean 成功true 失败false
   */
  function close() {
    return $this->db->close();
  }

}