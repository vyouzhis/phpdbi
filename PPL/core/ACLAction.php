<?php
if (!defined('M5CPL')){exit;}
/**
 * ACLAction
 *
 * @package ACLAction
 * @version 0.1
 * @copyright 2008 by vyouzhi
 * @author Cristian Rodriguez <vyouzhi@163.com>
 * @license BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 * @exception 设置权限
 */

require_once Core.'/ACLRole.php';   //装载权限规则的类

class ACLAction extends ACLRole {
		
	public function __construct(){
		parent::__construct();
	}
	
	/**
	 * checkLibrary
	 * Enter description here ...
	 */
	public function acl_checkLibrary() {
		;
	}
	
	/**
	 * @name acl_addLibrary
	 * @package new  group
	 * @param array $lib_array  array("lib name"=>array("lib name"=>array("read","create"), "lib name"=>array("read")), "5"=>array("dd"=>));
	 * @param String $groupName
	 * @param String $desc
	 * @param int $position
	 * @return false or group id
	 */
	public function acl_addLibrary($lib_array, $groupName, $desc, $position) {
		if(!is_array($lib_array)) return false;
		
		$format = "INSERT INTO `".DB_PRE_CORE."group` (`name`, `desc`, `position`, `permissions`, `types`)";
		$format .= " VALUES ( '%s', '%s', '%d', '%s', ".DB_TYPE_DIFFERENCE.")";
		
		$sql = sprintf($format, $groupName, $desc, $position, json_encode($lib_array));
		$res = $this->Insert($sql, GetPB("w","1"));
		
		if (!is_array($res)) {
			return false;
		}else return $res[0]['id'];		
	}
	
	
}
?>
