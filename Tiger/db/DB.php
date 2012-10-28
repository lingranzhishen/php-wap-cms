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
class Tiger_db extends Tiger_base{

	//数据库操作实例
	private static $db = null;
	private static $path = null;

	function __construct($dbType = 'mysql'){
		$this->path = dirname(__FILE__);
		$this->_init($dbType);
	}

	function __destruct() {
		$this->db = null;
	}

	private function _init($dbType) {
        $cls = 'Tiger_'.$dbType;
		$php = $this->path.'/driver/db.'.$dbType.'.php';
		if(file_exists($php)){
			include_once $php;
		}else{
			//TODO Log
            // echo "数据库没有提供此接口：$dbType";
            // exit;
			$this->halt("DatabaseHasNoTheInterface");
		}
        if (class_exists($cls))
            $this->db = new $cls();
        else {
            //TODO Log
            // echo '数据库操作类加载失败';
            // exit;
			$this->halt("DatabaseClassFailToLoad");
        }
        if (!$this->db) {
            //TODO Log
            // echo '数据库连接出错';
            // exit;
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
     */
	function connect($host, $user, $pwd, $db, $char) {
		$this->db->connect($host, $user, $pwd, $db);
		if ($char){
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
     * @return Array 结果集数组
     */
	function getArray($sql) {
		$arr = $this->db->getArray($sql);
		return $arr;
	}

    /**
     * 数据库查询
     * 用于发送数据，减少网络传输的数据量，客户端须做数据转换
     * @param String $sql 执行一条SQL语句
     * @return Array 结果集特殊数组
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
     * @return Array 结果集数组
     */
	function pageArray($sql, $size, $page, &$count = 0) {
		$arr = $this->db->pageArray($sql, $size, $page);

        //检查参数个数，判断是否有传 $count
        if (($num = func_num_args())>3){
            $count = $this->db->getCount();
        }
		return $arr;
	}

    /**
     * 数据库分页查询
     * 用于发送数据，减少网络传输的数据量，客户端须做数据转换
     * @param String $sql 执行一条SQL语句
     * @param Int $size 单页最大条数
     * @param Int $page 第几页
     * @param & $count 数据库总记录指针，用于返回总记录数
     * @return Array 结果集特殊数组
     */
	function pageArrayX($sql, $size, $page, &$count = 0) {
		$arr = $this->db->pageArrayX($sql, $size, $page);

        //检查参数个数，判断是否有传 $count
		if (($num = func_num_args())>3){
            $count = $this->db->getCount();
        }
		return $arr;
	}

    /**
     * 查询一个记录值
     * @param String $sql 执行一条SQL语句
     * @return String 一个记录值
     */
	function getOne($sql) {
		$one = $this->db->getOne($sql);
		return $one;
	}
}