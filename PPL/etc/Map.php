<?php
if (!defined('M5CPL')){exit;}
/*
 * route map
 * @/(?[A-Za-z]{2}_[A-Za-z]{2})$ // custom regex, matches language codes like "en_us" etc.
 * /posts/[*:title][i:id]        // Matches "/posts/this-is-a-title-123"
 * /output.[xml|json:format]?    // Matches "/output", "output.xml", "output.json"
 * /[:controller]?/[:action]?    // Matches the typical /controller/action format
 * 更随和点，只要 类名对应 就行了
 */
		
// 以下代码只含网站首页，关于，资讯，学院等
$routeMap = array(
	/* 样例用的  */
/* array('GET|POST','/ -- uri', 'home#index -- desctiption', 'home -- action'),  */
	array('method'=>'GET|POST',  
		'uri'=>'/', 
		'params'=>'home#index', 
		'module'=>'home'), /* index 首页*/
		
	array('method'=>'GET|POST',
			'uri'=>'/index/sitemap',
			'params'=>'home#index',
			'module'=>'sitemapxml'), /* 网站地图*/
	
	array('method'=>'GET|POST',
			'uri'=>'/ajax',
			'params'=>'home#index',
			'module'=>'ajax'), /* 网站地图*/
	
	array('method'=>'GET|POST',  
		'uri'=>'/blog/[bi|crm:switch]/[i:p]?', 
		'params'=>'home#index', 
		'module'=>'blog'), /* blog 首页*/
	
	array('method'=>'GET|POST',  
		'uri'=>'/articles/[i:id].html', 
		'params'=>'home#index', 
		'module'=>'articles'), /* 文章 首页*/
	
	array('method'=>'GET|POST',  
		'uri'=>'/our/guy', 
		'params'=>'home#index', 
		'module'=>'guy'), /* 合作商 首页*/
	
	array('method'=>'GET|POST',  
		'uri'=>'/analysis/[public|business:switch]/[i:p]?', 
		'params'=>'home#index', 
		'module'=>'pubdata'), /* 公共数据分析 首页*/

);