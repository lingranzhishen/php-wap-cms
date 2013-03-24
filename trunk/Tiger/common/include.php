<?php

/*
 * system level function
 */

function tiger_conf_load(array $config) {
  //TODO parse config and make file (runtime) 
  if (isset($config['time_zone'])) {
    date_default_timezone_set($config['time_zone']);
  }
  if (isset($config['debug']) && $config['debug'] == 1) {
    define('TIGER_DEBUG', 1);
  }
}

function tiger_debug() {
  $params = func_get_args();
  $msg = implode(' || ', $params);
  $traces = debug_backtrace();
  $trace = array_pop($traces);
  $file = $trace['file'];
  $func = $trace['function'];
  $filename = 'debug.log';
  $msg = "[" . date("m-d H:i:s") . "] File:\"" . basename($file) . "\" Function:\"" . $func . "\" Message:" . $msg . "\r\n";
  tiger_file($filename, $msg, 'add');
}

function tiger_file($filename, $msg, $mode = null) {
  $flag = null;
  $path = dirname($filename);
  if (!file_exists($path)) {
    mkdir($path, 0777, true);
  }
  if ($mode) {
    switch ($mode) {
      case "add":
        $flag = FILE_APPEND;
        break;
      case "lock":
        $flag = LOCK_EX;
        break;
      case "a":
        $flag = FILE_APPEND;
        break;
      case "l":
        $flag = LOCK_EX;
        break;
      default:
        break;
    }
  }
  file_put_contents($filename, $msg, $flag);
}

function tiger_halt_func($func = null) {
  static $halt = "";
  if ($func !== null && function_exists($func)) {
    $halt = $func;
  }
  return $halt;
}

function tiger_halt() {
  $halt = tiger_halt_func();
  $params = func_get_args();
  if ($halt) {
    call_user_func_array($halt, $params);
  } else {
    echo "<br/><b>[Tiger]Fatal Error:</b>" . array_shift($params);
  }
  exit;
}

function tiger_transamp($str) {
  $str = str_replace('&', '&amp;', $str);
  $str = str_replace('&amp;amp;', '&amp;', $str);
  $str = str_replace('\"', '"', $str);
  return $str;
}

function tiger_addquote($var) {
  return str_replace("\\\"", "\"", preg_replace("/\[([a-zA-Z0-9_\-\.\x7f-\xff]+)\]/s", "['\\1']", $var));
}

function tiger_languagevar($var = '') {
  return $GLOBALS['_tiger_mami']->lang()->get($var);
}

function tiger_stripvtags($expr, $statement) {
  $expr = str_replace("\\\"", "\"", preg_replace("/\<\?\=(\\\$.+?)\?\>/s", "\\1", $expr));
  $statement = str_replace("\\\"", "\"", $statement);
  return $expr . $statement;
}

function tiger_stripscriptamp($s) {
  $s = str_replace('&amp;', '&', $s);
  return "<script src=\"$s\" type=\"text/javascript\"></script>";
}

function tiger_stripblock($var, $s) {
  $s = str_replace('\\"', '"', $s);
  $s = preg_replace("/<\?=\\\$(.+?)\?>/", "{\$\\1}", $s);
  preg_match_all("/<\?=(.+?)\?>/e", $s, $constary);
  $constadd = '';
  $constary[1] = array_unique($constary[1]);
  foreach ($constary[1] as $const) {
    $constadd .= '$__' . $const . ' = ' . $const . ';';
  }
  $s = preg_replace("/<\?=(.+?)\?>/", "{\$__\\1}", $s);
  $s = str_replace('?>', "\n\$$var .= <<<EOF\n", $s);
  $s = str_replace('<?', "\nEOF;\n", $s);
  return "<?\n$constadd\$$var = <<<EOF\n" . $s . "\nEOF;\n?>";
}
