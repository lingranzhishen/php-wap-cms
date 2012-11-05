<?php

/*
 * 配置文件
 *
 */


//配置选择
$tigerControl = 'test';

$tigerAllConfig = array(
    'default' => array(
        'time_zone' => 'PRC',
        'lang' => 'zh-cn',
        'debug' => 0,
        'db' => array(
            'sql' => 'mysql',
            'host' => 'localhost:3306',
            'user' => 'root',
            'pwd' => '123456',
            'db_name' => 'test',
            'char' => 'utf8'
        ),
    ),
    'test' => array(
        'time_zone' => 'PRC',
        'lang' => 'zh-cn',
        'debug' => 1,
        'db' => array(
            'sql' => 'mysql',
            'host' => 'localhost:3306',
            'user' => 'root',
            'pwd' => '123456',
            'db_name' => 'test',
            'char' => 'utf8'
        ),
    ),
);


//配置
$_config = $tigerAllConfig[$tigerControl];
?>
