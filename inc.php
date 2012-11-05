<?

require 'Tiger/Tiger.php';
include APP_PATH . '/config.php';

$_tiger = new Tiger();

/* 优先级
 * 1配置，2错误处理，3语言
 */

$_tiger->setConfig($_config);
$_tiger->setHalt("handleErrorFuncForTiger");

$_lang = $_tiger->lang(true);

function handleErrorFuncForTiger($msg, $isI18nMsg = false) {
  global $_tiger, $_lang;
  $c = $_tiger->error();
  if (true === $isI18nMsg) {
    $msg = $_lang->get($msg);
  }
  $c->call($msg);
}