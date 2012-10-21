<?php
/*
 * Tiger 加载文件
 *
 */

if(version_compare(PHP_VERSION,'5.2.0','<') ) {
	include TIGER_PATH.'/common/compat.php';
}

/*
 * 引进系统类
 */

//引进核心类
include TIGER_PATH.'/core/Error.php';	//10
include TIGER_PATH.'/core/Lang.php';	//20

//引进数据库类
include TIGER_PATH.'/db/DB.php';



class Tiger_mountain{

	private static $_instance = null;
	private static $_db = null;
	private static $_error = null;
	private static $_lang = null;

	private static $config = null;

	private function __construct(){
	}
	
	public static function init(){
        if(!isset(self::$_instance)){
            $cls = __CLASS__;
            self::$_instance = new $cls;
        }
        return self::$_instance;
    }    
    
    public function __clone(){
		die("Mountain Cannot Accommodate Two Tigers");
    }
	
	function setConfig($config){
		$this->config = $config;
	}
	
	function getConfig(){
		return $this->config;
	}
	
	function db($autoConnect = true){
		if (!isset($this->_db)){
			$this->_db = new Tiger_db();
			if (true === $autoConnect){
				$config = $this->config['db'];
				$this->_db->connect($config['host'], $config['user'], $config['pwd'], $config['db_name'], $config['char']);
			}
		}
		return $this->_db;
	}
	
	function database(){
		$db = new Tiger_db();
		return $db;
	}
	
	function error(){
		if (!isset($this->_error)){
			$this->_error = new Tiger_error();
		}
		return $this->_error;
	}

	function lang($autoSet = true){
		if (!isset($this->_lang)){
			$this->_lang = new Tiger_lang();
			if (true === $autoSet){
				$lang = $this->config['lang'];
				$this->includeLangFile($lang);
			}
		}
		if (is_string($lang = $autoSet)){
			$this->includeLangFile($lang);
		}
		return $this->_lang;
	}
	
	private function includeLangFile($lang){
		$php = TIGER_PATH."/lang/$lang.php";
		if(file_exists($php)){
			include_once $php;
			$this->_lang->locale($lang);
			$this->_lang->set($_lang, $lang);
		}
	}

}


