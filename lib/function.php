<?

if (!defined('wq'))
  die();

/* 类加载 */

function __autoload() {
  $class = $className . '.php';
  if (file_exists($class))
    require_once $class;
  else {
    throw new Exception("Class: $className does not exist"); //主动抛出异常，这里不用try catch
    //TODO log
  }
}

/* 异常处理 */

function wq_exception_handler($exception) {
  echo "<b>Exception:</b> ", $exception->getMessage();
  //TODO log
}

set_exception_handler('wq_exception_handler'); //自定义一个exception处理程序
