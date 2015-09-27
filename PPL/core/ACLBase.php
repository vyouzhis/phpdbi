<?php
if (!defined('M5CPL')){exit;}
/**
 * ACL
 *
 * @package ACL
 * @version 0.1
 * @copyright 2008 by vyouzhi
 * @author Cristian Rodriguez <vyouzhi@163.com>
 * @license BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 * @exception 过程检查是否登录用的
 * @see ADMIN_SESSION_KEY 
 * array("uid"=>"value 用户ID",
 * 		 "mc"=>"value 唯一检查",
 * 		 "lib 各功能模块 "=>array(
 * 		 array("lib name"=>"总菜单1",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 * 	     array("lib name"=>"总菜单2",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 *      ));
 */

require_once Core.'/ACLInit.php';    //登录或登出用的类

class ACLBase extends ACLInit{
	
	public function __construct() {
		parent::__construct();		
	}
	
	/**
	 * @name CheckAccess
	 * @package will check cm and uid
	 * @return False or true
	 */
	public function acl_CheckAccess(){
		$ses = $this->acl_getSessionInfo();
		
		if(empty($ses))return false;
		 		
		// CHECK AGAIN
		$format = "SELECT uid FROM `".DB_PRE_CORE."user_info` WHERE types=%d and uid =%d and cm='%s' LIMIT 1";
		$sql = sprintf($format, DB_TYPE_DIFFERENCE,$ses['uid'], $ses['cm']);
		$res = parent::FetchOne($sql);
		
		if(is_array($res)) return true;
		else {
			$this->acl_Logout();
			return false;
		}
	}

}
?>