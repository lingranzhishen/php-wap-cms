<?php
//网站入口

include 'inc.php';

header('Content-Type: text/html; charset=utf-8');

echo $_lang->get("ParamMustBeArray") . "<br/>";

$a = $_tiger->db();
// $_lang->set("");


$log = $_tiger->log();
$log->msg('hello');

print_r ($a->getRow("select 8"));

$_exeTime = microtime(true);
echo "<br/>load: ".($_tiger_time_load - $_tiger_time_begin)*(1000). "<br/>exe:".($_exeTime - $_tiger_time_load)*(1000). "<br/>";

$t = $_tiger->template();
include($t->fetchCache("1.html"));


// var_dump($t);
// echo "-----------------n------------------";
// var_dump($_tiger_mami->template());