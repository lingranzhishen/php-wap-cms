<?php
/*
 * Tiger MySQL数据库操作
 *
 */


class tiger_mysql extends tiger_sql{


	function __construct(){

	}

	function connect($host, $user, $pwd, $dbName) {
		$this->db = mysql_connect($host, $user, $pwd, true);
		if (!$this->db) {
			//TODO Log
			die("<b>Fatal Error</b>\db\Mysql::connect($host, $dbName) Failed, error NO:". mysql_errno() . ' ' . mysql_error());
		}
		$this->selectDB($dbName);
	}

	function selectDB ($name) {
		if ($name) {
			mysql_select_db($name);
			$this->db_name = $name;
		} else {
			//TODO Log
		}
	}

	function execute($sql) {
		$rs = mysql_query($sql);
		if (!$rs) {
			die("<b>Fatal Error</b>\db\Mysql::query($sql) Failed, error NO:". mysql_errno() . ' ' . mysql_error());
			//TODO Log
		}
		$this->result = $rs;
		return $rs;
	}

	function close() {
		mysql_close($this->db);
	}
	
	function insertID() {
		return mysql_insert_id();
	}
	
	/**
	 *
	 * @param int $offest 开始搜索的位置
	 * @return string 
	 */
	function fieldName($offest = 0) {
		return @mysql_field_name($this->result, $offest);
	}
	
	/**
	 * @param $array_type 可选。规定返回哪种结果。可能的值：
				MYSQL_ASSOC - 关联数组
				MYSQL_NUM - 数字数组
				MYSQL_BOTH - 默认。同时产生关联和数字数组
	 * 
	 */
	function fetchArray($array_type = MYSQL_BOTH) {
		if ($this->result)
			$row = mysql_fetch_array($this->result, $array_type);
		if (!$row) $row = array();
		return $row;
	}
	
	function fetchRow() {
		$row = mysql_fetch_row($this->result);
		if (!$row) $row = array();
		return $row;
	}
	
	function setCharset($char) {
		$this->execute('set names '.$char);
	}
	
	function setCount($sql) {
		//本函数不能用于sql中的 union 语法
		$sql = preg_replace('/^\s*SELECT\s.*\s+FROM\s/Uis', 'SELECT COUNT(*) FROM ', $sql);
		if (preg_match('/\sORDER\s+BY\s*\(/i', $sql))
			$sql = preg_replace('/(\sORDER\s+BY\s.*)/is', '', $sql);
		else
			$sql = preg_replace('/(\sORDER\s+BY\s[^)]*)/is', '', $sql);
		$count = $this->getOne($sql);
		if (!$count) $count = 0;
		$this->count = $count;
	}
	
	function getRows() {
		$rows = array();
		while ($row = $this->fetchArray(MYSQL_ASSOC)) {
			$rows[] = $row;
		}
		return $rows;
	}
	
	function getRowsX() {
		$rows = array();
		$fields = $this->fieldNameArray();
		while ($row = $this->fetchRow()) {
			$rows[] = $row;
		}
		return array('field'=> $fields, 'data'=> $rows);
	}
	
	function getOne($sql) {
		$this->execute( $sql. ' limit 1');
		$row = $this->fetchRow();
		if (count($row) > 1) {
			//TODO Log
		}
		return array_shift($row);
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
	
	function fieldNameArray() {
		$fields = array();
		$i = 0;
		while ($field = $this->fieldName($i++)) {
			$fields[] = $field;
		}
		return $fields;
	}
	
	function pageArray($sql, $size, $page) {
		if ($page > 1 && $size >1) {
			$this->setCount($sql);
			$offset = ($page - 1) * $size;
			$sql .= ' limit '.$offset.','.$size;
			return $this->getArray($sql);
		}
	}
	
	function pageArrayX($sql, $size, $page) {
		if ($page > 1 && $size >1) {
			$this->setCount($sql);
			$offset = ($page - 1) * $size;
			$sql .= ' limit '.$offset.','.$size;
			return $this->getArrayX($sql);
		}
	}
}