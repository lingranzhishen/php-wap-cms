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
	//数据库配置，子类实现
	protected static $config = null;

	function __construct(){
		$this->init();
	}
	
	function __destruct() {
		$this->db = null;
		$this->config = null;
	}
	
	private function init() {
		$_conf = $this->config;
		if ($_conf){
			$cls = 'tiger_'.($_conf['sql']);
			require_once TIGER_PATH.'/db/driver/db.'.$_conf['sql'].'.php';
			if (class_exists($cls))
				$this->db = new $cls();
			else {
				//TODO Log 数据库操作类加载失败
				echo '数据库操作类加载失败';
				exit;
			}
			if ($this->db) {
				$this->connect(	$_conf['host'],	$_conf['user'],	$_conf['pwd'],
								$_conf['db'],	$_conf['char']
							);
			} else {
				//TODO Log 数据库连接出错
				echo '数据库连接出错';
				exit;
			}
		} else {
			//TODO Log 配置出错
			echo '配置出错';
			exit;
		}
	}
	
	private function connect($host, $user, $pwd, $db, $char) {
		$this->db->connect($host, $user, $pwd, $db);
		if ($char) $this->db->setCharset($char);
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