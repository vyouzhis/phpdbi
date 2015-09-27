<?php
if (!defined('M5CPL')){exit;}

require_once Core.'/ACLControl.php';  //权限使用的类

/**
 * 
 * 登录或登出用的类 ...
 * @author jsb-015
 * @see ADMIN_SESSION_KEY 
 * array("uid"=>"value 用户ID","mc"=>"value 唯一检查", "mod 各功能模块"=>array("mod1"=>array("read","write"),"mod2"=>array("read")))
 */

class ACLImport extends ACLControl {
	
	public function __construct($stdClass) {
		parent::__construct($stdClass);						  
	}
				
}
