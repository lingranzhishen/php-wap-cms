<?php

/*
 * Tiger MySQL数据库操作
 *
 */

class Tiger_mysql extends Tiger_sql {

  function __construct() {
    
  }

  function connect($host, $user, $pwd, $dbName) {
    $this->db = mysql_connect($host, $user, $pwd, true);
    if (!$this->db) {
      //TODO 开发可见且log
      die("<b>Fatal Error:</b> " . mysql_error() . "<br/><b>Error Code:</b> " . mysql_errno() . "<br/><b>Error Tip:</b> \db\Mysql::connect($host, $dbName) Failed");
    }
    $this->selectDB($dbName);
  }

  function selectDB($dbName) {
    if ($dbName) {
      mysql_select_db($dbName);
      $this->db_name = $dbName;
    } else {
      //TODO Log
    }
  }

  function execute($sql) {
    $rs = mysql_query($sql);
    if (!$rs) {
      //TODO 开发可见且log
      die("<b>Fatal Error:</b> " . mysql_error() . "<br/><b>Error Code:</b> " . mysql_errno() . "<br/><b>Error Tip:</b> \db\Mysql::execute(\"$sql\") Failed");
    }
    $this->result = $rs;
    return $rs;
  }

  function close() {
    mysql_close($this->db);
  }

  function insertID() {
    return mysql_insert_id($this->db);
  }

  function lastInsertID() {
    return $this->getOne("SELECT LAST_INSERT_ID()");
  }

  function affectedRows() {
    $this->count = mysql_affected_rows($this->db);
    return $this->count;
  }

  function numRows() {
    $this->count = mysql_num_rows($this->db);
    return $this->count;
  }

  /**
   * 取得结果中指定字段的字段名
   * @param int $offest 开始搜索的位置
   * @return string
   */
  protected function fieldName($offest = 0) {
    return @mysql_field_name($this->result, $offest);
  }

  /**
   *  从结果集中取得一行作为关联数组，或数字数组，或二者兼有
   * @param $array_type 可选。规定返回哪种结果。可能的值：
    MYSQL_ASSOC - 关联数组
    MYSQL_NUM - 数字数组
    MYSQL_BOTH - 默认。同时产生关联和数字数组
   *
   */
  protected function fetchArray($array_type = MYSQL_BOTH) {
    if ($this->result)
      $row = mysql_fetch_array($this->result, $array_type);
    if (!$row)
      $row = array();
    return $row;
  }

  protected function fetchRow() {
    $row = mysql_fetch_row($this->result);
    if (!$row)
      $row = array();
    return $row;
  }

  protected function getRows() {
    $rows = array();
    while ($row = $this->fetchArray(MYSQL_ASSOC)) {
      $rows[] = $row;
    }
    return $rows;
  }

  protected function getRowsX() {
    $rows = array();
    $fields = $this->fieldNameArray();
    while ($row = $this->fetchRow()) {
      $rows[] = $row;
    }
    return array('field' => $fields, 'data' => $rows);
  }

  protected function fieldNameArray() {
    $fields = array();
    $i = 0;
    while ($field = $this->fieldName($i++)) {
      $fields[] = $field;
    }
    return $fields;
  }

  function setCharset($char) {
    $this->execute('set names ' . $char);
  }

  protected function calcAllCount($sql) {
    //本函数不能用于sql中的 union 语法
    $sql = preg_replace('/^\s*SELECT\s.*\s+FROM\s/Uis', 'SELECT COUNT(*) FROM ', $sql);
    if (preg_match('/\sORDER\s+BY\s*\(/i', $sql)) {
      $sql = preg_replace('/(\sORDER\s+BY\s.*)/is', '', $sql);
    } else {
      $sql = preg_replace('/(\sORDER\s+BY\s[^)]*)/is', '', $sql);
    }
    $count = $this->getOne($sql);
    if (!$count)
      $count = 0;
    $this->count = $count;
  }

  function getCount($sql = "", $calcAll = false) {
    if ($sql) {
      if ($calcAll) {
        $this->calcAllCount($sql);
      } else {
        $this->execute($sql);
        $this->affectedRows();
      }
    } else {
      $this->affectedRows();
    }
    return $this->count;
  }

  function getOne($sql) {
    $this->execute($sql . ' limit 1');
    $row = $this->fetchRow();
    if (count($row) > 1) {
      //TODO Log
    }
    return array_shift($row);
  }
  
  function getRow($sql){
	$this->execute($sql);
    $row = $this->fetchArray(MYSQL_ASSOC);
    return $row;
  }

  function getArray($sql) {
    $this->execute($sql);
    return $this->getRows();
  }
  
  //服务端消耗接近getArray()
  function getArrayX($sql) {
    $this->execute($sql);
    return $this->getRowsX();
  }

  function pageArray($sql, $size, $page, &$count) {
    if ($page > 1 && $size > 1) {
      //检查参数个数，判断是否有传 $count
      if (($num = func_num_args()) > 3) {
        $count = $this->getCount($sql, true);
      }

      $offset = ($page - 1) * $size;
      $sql .= ' limit ' . $offset . ',' . $size;
      return $this->getArray($sql);
    } else {
      die("<b>Notice Error:</b> ");
    }
  }

  function pageArrayX($sql, $size, $page, &$count) {
    if ($page > 1 && $size > 1) {
      //检查参数个数，判断是否有传 $count
      if (($num = func_num_args()) > 3) {
        $count = $this->getCount($sql, true);
      }
      $offset = ($page - 1) * $size;
      $sql .= ' limit ' . $offset . ',' . $size;
      return $this->getArrayX($sql);
    } else {
      die("<b>Notice Error:</b> ");
    }
  }

}