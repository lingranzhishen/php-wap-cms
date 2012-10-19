<?

require 'Tiger/Tiger.php';
include_once APP_PATH.'/config.php';

//$a = new DB();
//echo $a->execute("select curdate()");

class dbtest extends DB {

	function __construct() {
		parent::__construct();
	}
}

$a = new dbtest();
$a->connect($GLOBALS['database']['host'], $GLOBALS['database']['user'], $GLOBALS['database']['pwd'], $GLOBALS['database']['db'], $GLOBALS['database']['char']);

print_r ($a->getOne("select now()"));

$GLOBALS['_exeTime'] = microtime(true);
echo "<br/>".($GLOBALS['_exeTime']-$GLOBALS['_loadTime']). "<br/>";