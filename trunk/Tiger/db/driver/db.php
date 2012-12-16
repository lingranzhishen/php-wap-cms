<?php

/*
 * Tiger SQL数据库操作
 *
 */

abstract class Tiger_sql {

  //数据库操作实例
  protected static $db = null;
  //数据库名称
  protected static $db_name = null;
  //数据库记录集
  protected static $result = null;
  //数据库记录数
  protected static $count = 0;

  abstract protected function __construct();

  function __destruct() {
    $this->close();
    $this->db = null;
    $this->db_name = null;
    $this->result = null;
    $this->count = null;
  }

  abstract protected function connect($host, $user, $pwd, $dbName);

  abstract protected function selectDB($dbName);

  abstract protected function fetchArray();

  abstract protected function execute($sql);

  abstract protected function close();

  abstract protected function insertID();
  
  abstract protected function lastInsertID();

  abstract protected function affectedRows();

  abstract protected function numRows();

  abstract protected function setCharset($char);

  abstract protected function getRows();

  abstract protected function getRowsX();

  abstract protected function getOne($sql);
  
  abstract protected function getRow($sql);

  abstract protected function getArray($sql);

  abstract protected function getArrayX($sql);

  abstract protected function fieldName();

  abstract protected function pageArray($sql, $size, $page, &$count);

  abstract protected function pageArrayX($sql, $size, $page, &$count);

  abstract protected function getCount();
}