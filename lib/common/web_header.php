<?php
if (!defined('M5CPL')){exit;}
/**
 * 共用页面 只是单独的模块类 会自动 引入
 * @package 共用页面，所以只要引入BaseModule
 */

//页头
class web_header extends BaseModule {
	
	public function __construct() {
		parent::__construct(__CLASS__);
		
	}
	
	
	public function filter() {
		global $match ,$router; 
		
		include parent::view();
	}

}