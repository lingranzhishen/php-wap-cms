<?


include 'inc.php';

header('Content-Type: text/html; charset=utf-8');

echo $_lang->get("ParamMustBeArray") . "<br/>";

$a = $_tiger->db();

// $_lang->set("");

print_r ($a->getOne("select now()"));

$_exeTime = microtime(true);
echo "<br/>".($tiger_time_load - $tiger_time_begin). "<br/>".($_exeTime - $tiger_time_load). "<br/>";
