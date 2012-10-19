<?php
/*
 * Tiger 数据库操作
 *
 */

require_once TIGER_PATH.'/db/driver/db.php';

/**
 * Tiger 数据库操作类
 * @category   Tiger
 * @package  Tiger
 * @subpackage  DB
 * @author    Vicky
 * @version   $Id$
 */
class DB {

	//数据库操作实例
	private static $db = null;

	function __construct($dbType = 'mysql'){
		$this->init($dbType);
	}

	function __destruct() {
		$this->db = null;
	}

	private function init($dbType = 'mysql') {
        $cls = 'tiger_'.$dbType;
        require_once TIGER_PATH.'/db/driver/db.'.$dbType.'.php';
        if (class_exists($cls))
            $this->db = new $cls();
        else {
            //TODO Log 数据库操作类加载失败
            echo '数据库操作类加载失败';
            exit;
        }
        if (!$this->db) {
            //TODO Log 数据库连接出错
            echo '数据库连接出错';
            exit;
        }
	}

	function connect($host, $user, $pwd, $db, $char) {
		$this->db->connect($host, $user, $pwd, $db);
		if ($char){
            $this->db->setCharset($char);
        }
	}

	function query($sql) {
		$rs = $this->db->execute($sql);
		//TODO Log
		return $rs;
	}

	function getArray($sql) {
		$arr = $this->db->getArray($sql);
		return $arr;
	}

	//用于发送数据，减少网络传输的数据量，客户端须做数据转换
	function getArrayX($sql) {
		$arr = $this->db->getArrayX($sql);
		return $arr;
	}

	function pageArray($sql, $size, $page, &$count = 0) {
		$arr = $this->db->pageArray($sql, $size, $page);
		$count = $this->db->getCount();
		return $arr;
	}

	//用于发送数据，减少网络传输的数据量，客户端须做数据转换
	function pageArrayX($sql, $size, $page, &$count = 0) {
		$arr = $this->db->pageArrayX($sql, $size, $page);
		$count = $this->db->getCount();
		return $arr;
	}

	function getOne($sql) {
		$one = $this->db->getOne($sql);
		return $one;
	}
}