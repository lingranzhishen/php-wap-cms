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
            'host' => 'localhost',
            'user' => 'root',
            'pwd' => '',
            'db_name' => 'test',
            'char' => 'utf8',
            'pc' => false
        ),
		'template' => array(
			'template_dir' => 'template',
			'delimiter_left' =>  '{',
			'delimiter_right' =>  '}',
			'cache_dir' => 'template_cache',
			'cache_lifetime' => 0
		)
    ),
    'test' => array(
        'time_zone' => 'PRC',
        'lang' => 'zh-cn',
        'debug' => 1,
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
			'delimiter_left' =>  '{',
			'delimiter_right' =>  '}',
			'cache_dir' => 'template_cache',
			'cache_lifetime' => 0,
			'compile_check' => true
		)
    ),
);

//配置
$_config = $tigerAllConfig[$tigerControl];

