<?
//网站入口

include 'inc.php';

header('Content-Type: text/html; charset=utf-8');

// echo $_lang->get("ParamMustBeArray") . "<br/>";

$a = $_tiger->db();
// $_lang->set("");

print_r ($a->getRow("select * from tt.aa"));


$_exeTime = microtime(true);
// echo "<br/>".($_tiger_time_load - $_tiger_time_begin). "<br/>".($_exeTime - $_tiger_time_load). "<br/>";

$t = $_tiger->template();
include($t->fetchCache("1.html"));

// var_dump($t);
				// echo "-----------------n------------------";
// var_dump($_tiger_mami->template());