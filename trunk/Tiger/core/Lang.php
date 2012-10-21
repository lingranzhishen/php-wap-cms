<?php
/*
 * Tiger 语言类
 */

class Tiger_lang{

	private static $lang = null;
	private static $local = null;
	private static $funcErr = null;

	function __construct($local = 'en') {
		$this->lang = array();
		$this->local = $local;
		$this->lang[$local] = array();
		$this->funcErr = "";
	}
	
	function locale($local){
		if(!array_key_exists($local, $this->lang)){
			$this->lang[$local] = array();
		}
		$this->local = $local;
	}
	
	function set($langArray, $local = ''){
		if(!is_array($langArray)){
			//TODO
			$this->callError("ParamMustBeArray", true);
		}
		
		if(!$local){
			$local = $this->local;
		}else{
			$this->locale($local);
		}
		
		$this->lang[$local] = array_merge($this->lang[$local] , $langArray);
	}
	
	function get($key, $local = ''){
		if (!is_string($key)){
			//TODO
			return null;
		}
		
		if(!$local){
			$local = $this->local;
		}else{
			if(!array_key_exists($local, $this->lang)){
				return null;
			}
		}
		if(!array_key_exists($key, $this->lang[$local])){
			return null;
		}
		return $this->lang[$local][$key];
	}
	
	function setFuncErr($funcName){
		if(function_exists($funcName)){
			$this->funcErr = $funcName;
		}
	}
	
	private function callError($msg, $isI18nMsg = false){
		if (!$this->funcErr){
			die($msg);
		}
		call_user_func($this->funcErr, $msg, $isI18nMsg);
	}
	
}

