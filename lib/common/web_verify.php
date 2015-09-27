<?php
if (!defined('M5CPL')){exit;}
/**
 * 共用页面 只是单独的模块类  自定义的不会自动引入要手动式
 * @package 共用页面，所以只要引入BaseModule
 */

//用户手机验证，邮件验证等
class ilogin_verify extends BaseModule {
	
	public function __construct() {
		parent::__construct(__CLASS__);		
	}
		
	/**
	 * @name filter
	 * @package 过滤输出
	 * @see PPL/class/BaseModule::filter()
	 */
	public function filter() {	
		
				
		$file = parent::view();
		require_once $file;
	}
	
	
}