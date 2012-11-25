<?php
/*
 * Tiger 加载文件
 *
 */

if(version_compare(PHP_VERSION,'5.2.0','<') ) {
	include TIGER_PATH.'/common/compat.php';
}

/* 引进系统函数 */
require TIGER_PATH. '/common/include.php';


/* 引进系统类 */

//引进核心类
require TIGER_PATH.'/core/Base.php';	//1
require TIGER_PATH.'/core/Error.php';	//10
require TIGER_PATH.'/core/Lang.php';	//20

require TIGER_PATH.'/core/Template.php';//200


//引进数据库类
require TIGER_PATH.'/db/DB.php';

$_tiger_mami = Tiger_mountain::findTiger();

class Tiger_mountain{

	private static $_instance = null;
	private static $_instances = null;
	private static $_db = null;
	private static $_error = null;
	private static $_lang = null;
	private static $_template = null;

	private static $_config = null;
	private static $_halt = null;

	private function __construct(){
		$this->_instances = array();
	}
	
	public static function findTiger(){
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
		$this->_config = $config;
	}
	
	function getConfig(){
		return $this->_config;
	}
	
	function setHalt($funcName){
		if(function_exists($funcName)){
			$this->_halt = $funcName;
			if(isset($this->_instances)){
				foreach($this->_instances as &$instance){
					$instance->setHalt($funcName);
				}
			}
		}
	}
	
	function db($autoConnect = true){
		if (!isset($this->_db)){
			$this->_db = new Tiger_db();
			if (true === $autoConnect){
				$conf = $this->_config['db'];
				$this->_db->connect($conf['host'], $conf['user'], $conf['pwd'], $conf['db_name'], $conf['char']);
			}
			$this->_instances[] = &$this->_db;
			if (isset($this->_halt)){
				$this->_db->setHalt($this->_halt);
			}
		}
		return $this->_db;
	}
	
	function database(){
		$db = new Tiger_db();
		$this->_instances[] = &$db;
		if (isset($this->_halt)){
			$db->setHalt($this->_halt);
		}
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
				$lang = $this->_config['lang'];
				$this->includeLangFile($lang);
			}
			$this->_instances[] = &$this->_lang;
			if (isset($this->_halt)){
				$this->_lang->setHalt($this->_halt);
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
	
	function template(){
		if (!isset($this->_template)){
			$this->_template = new Tiger_template();
			$this->_instances[] = &$this->_template;
			if (isset($this->_halt)){
				$this->_template->setHalt($this->_halt);
			}
		}
		return $this->_template;
	}

}


