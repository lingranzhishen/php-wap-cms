<?

require ('Tiger/Tiger.php');
include_once APP_PATH.'/config.php';

//$a = new DB();
//echo $a->execute("select curdate()");

class dbtest extends DB {

	function __construct() {
		$this->config = $GLOBALS['database'];
		parent::__construct();
	}
}

$a = new dbtest();

print_r ($a->getOne("select * from tbl_match"));
print_r ($a->getOne("select * from tbl_match"));

$GLOBALS['_exeTime'] = microtime(true);
echo "<br/>".($GLOBALS['_exeTime']-$GLOBALS['_loadTime']). "<br/>";