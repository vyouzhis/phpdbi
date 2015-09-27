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
 * @exception 权限使用的类
 */
require_once Core.'/ACLAction.php';  //设置权限

class ACLControl extends ACLAction {	
	
	private $stdClass;
	public function __construct($stdClass){
		parent::__construct();
		$this->stdClass = $stdClass;
	}
		
	/**
	 * @name acl_fetchRole
	 * @package 从数据取出该用户的权限
	 * @return array
	 */	
	public  function acl_fetchRole() {
		$uid = parent::acl_getUid();
		
		$format ="SELECT g.permissions,g.subpermiss FROM ".DB_PRE_CORE."user_info u, ".DB_PRE_CORE."group g where u.types=%d and g.types=%s and u.gid = g.id and u.uid=%d ";
		
		$sql = sprintf($format, DB_TYPE_DIFFERENCE, DB_TYPE_DIFFERENCE, $uid);
		
		$res = $this->FetchArray($sql);
	
		$ses = $this->acl_getSessionInfo();
		$ses['role'] = $res;
		$this->acl_setSessionInfo($ses);
	}
	
	/**
	 * @name acl_fetchMyRole
	 * @package 获取用户的权限数组
	 * @return String
	 */
	public function acl_fetchMyRole() {
		$res =  $this->acl_getSessionInfo();
		return $res['role'];		
	}
	
	/**
	 * @name acl_myRole
	 * @package 使用权限	 
	 * @param string $action
	 * @return true or false
	 */

	public function acl_myRole($action) {
		$act = array(
		"read"=>0,
		"create"=>1,
		"edit"=>2,
		"remove"=>3,
		"search"=>4);
		
		$ses = $this->acl_getSessionInfo();
		
		if(empty($ses['role']))return false;
		
		$role = $ses['role'][0];
		if(empty($role)) return false;
		$role = unserialize($role['permissions']);
		$crtl = $act[$action];
				
        $keyClass = $this->getMain($this->stdClass)."_".$this->stdClass;
		
		if(!array_key_exists($keyClass, $role)) return  false;
		
		if(!array_key_exists($crtl, $role[$keyClass])) return false;
		
		return true;
	}

	private function getMain($lib) {
		$format = "SELECT lib FROM `".DB_PRE_CORE."roles`  where types=%d and id = (select pid from ".DB_PRE_CORE."roles where types=%d and lib='%s' limit 1) limit 1";
		$sql = sprintf($format,DB_TYPE_DIFFERENCE, DB_TYPE_DIFFERENCE, $lib);
		
		$m = parent::FetchOne($sql);
		return $m['lib'];
	}
		
}
?>
