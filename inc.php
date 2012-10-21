<?

require 'Tiger/Tiger.php';
include APP_PATH.'/config.php';

$_tiger = new Tiger();

/* ���ȼ�
 * 1���ã�2���ԣ�3����
 */

$_tiger->setConfig($_config);

$_lang = $_tiger->lang(true);
$_lang->setFuncErr("handleErrorFuncForTiger");

function handleErrorFuncForTiger($msg, $isI18nMsg = false){
	global $_tiger, $_lang;
	$c = $_tiger->error();
	if(true === $isI18nMsg){
		$msg = $_lang->get($msg);
	}
	$c->call($msg);
}