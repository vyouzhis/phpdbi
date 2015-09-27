<?php
if (!defined('M5CPL')){exit;}
/**
 * 共用页面 只是单独的模块类  自定义的不会自动引入要手动式
 * @package 共用页面，所以只要引入BaseModule
 */

//页中间
class admin_navbar extends BaseModule {
	
	public function __construct() {
		parent::__construct(__CLASS__);		
	}
		
	/**
	 * @name filter
	 * @package 过滤输出
	 * @see PPL/class/BaseModule::filter()
	 */
	public function filter() {				
		parent::filter();
	}
	
	/**
	 * 
	 * @name url
	 * @param unknown_type $routeName
	 * @param array $params
	 * @package 返回url
	 */
	public function url($routeName, array $params = array()) {
		global $router, $match;
				
		return $router->url($routeName, $params);
	}
	
}

