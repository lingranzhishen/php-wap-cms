<?php
/*
 * Tiger 日志类
 * 用于前期调试错误和后期网站管理
 */

class Log {
	
	private static $pattern;
	protected static $path;

	function __construct() {}
	
	//前期调试错误
	function debug() {
		$params = func_get_args();
		$msg = $this->toString($params);
		$trace = debug_backtrace();
		$trace = array_pop($trace);
		$file = $trace['file'];
		$func = $trace['function'];
		$file = substr($file, strrchr($file, '\\'));
		$filename = $func.'-'.$file;
		$this->write($msg, $filename);
		//TODO
	}
	
	//后期网站管理
	function admin($msg) {
		
		$this->write($msg, $filename);
	}
	
	
	private function write($msg, $filename) {
		if (!file_exists($this->path)) {
			mkdir($this->path, 0777);
		}
		file_put_contents($filename, $msg);
	}
}