<?php

/*
 * 配置文件
 *
 */


//配置选择
$tigerControl = 'test';

$tigerAllConfig = array(
    'default' => array(
        'debug' => 0,
        'time_zone' => 'PRC',
        'lang' => array(
            'path' => 'lang',
            'local' => 'zh-cn',
        ),
        'db' => array(
            'sql' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'pwd' => '',
            'db_name' => 'test',
            'char' => 'utf8',
            'pc' => false
        ),
        'template' => array(
            'template_dir' => 'template',
            'delimiter_left' => '{',
            'delimiter_right' => '}',
            'cache_dir' => 'cache/template',
            'cache_lifetime' => 0
        ),
        'log' => array(
            'path' => 'log'
        ),
    ),
    'test' => array(
        'debug' => 1,
        'time_zone' => 'PRC',
        'lang' => array(
            'path' => 'lang',
            'local' => 'zh-cn',
        ),
        'db' => array(
            'sql' => 'mysql',
            'host' => 'localhost',
            'user' => 'root',
            'pwd' => '',
            'db_name' => 'test',
            'char' => 'utf8',
            'pc' => false
        ),
        'template' => array(
            'template_dir' => 'template',
            'delimiter_left' => '{',
            'delimiter_right' => '}',
            'cache_dir' => 'cache/template',
            'cache_lifetime' => 0,
            'compile_check' => true
        ),
        'log' => array(
            'path' => 'log'
        ),
    ),
);

//配置
$_config = $tigerAllConfig[$tigerControl];

