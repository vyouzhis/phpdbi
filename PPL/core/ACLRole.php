<?php
if (!defined('M5CPL')){exit;}
/**
 * ACLRole
 *
 * @package ACLRole
 * @version 0.1
 * @copyright 2008 by vyouzhi
 * @author Cristian Rodriguez <vyouzhi@163.com>
 * @license BSD {@link http://www.opensource.org/licenses/bsd-license.php}
 * @exception 装载权限规则的类
 * @see ADMIN_SESSION_KEY
 * array("uid"=>"value 用户ID",
 * 		 "mc"=>"value 唯一检查",
 * 		 "lib 各功能模块 "=>array(
 * 		 array("index"=>"总菜单1",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 * 	     array("index"=>"总菜单2",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 *      ));
 */

require_once Core.'/ACLBase.php';    //过程检查是否登录用的
class ACLRole extends ACLBase{
	private $stdClass;

	public function __construct(){
		parent::__construct();
	}

	public function acl_init(&$stdClass) {
		$this->stdClass = $stdClass;

	}

	/**
	 * @name acl_flush_libfile
	 * @package 更新模块文件 ...
	 *
	 */
	public function acl_flush_libfile() {
		flush_routing();
		//flush_page();
	}

	/**
	 * @name acl_load_lib
	 * @package 系统后台装载模块
	 * @return array or null
	 */

	public function acl_load_lib() {
		global $BASETHEME_COMMON_ACTION;
		if(file_exists(LIBS)){
			$_libs = include LIBS;
		}else{
			return null;
		}

		$libs = unserialize($_libs);

		$lib_array = array();
		$BASETHEME_COMMON_ACTION = false;
		foreach ($libs as $k=>$v){
			if(substr($v, -4)!=".php")continue;

			if($k == get_class($this->stdClass)){
				$lib = $this->stdClass;
			}
			else if( class_exists($k)){
				continue;
			}else {
				require_once  $v;
				if(class_exists($k)){
					$lib = new $k;
				}else continue;

			}
			$seg = getSeg();
			
			$name = explode($seg, $v);

			switch (get_parent_class ( $lib )) {
				case "BasePrograma":
					//后台主菜单栏
					if(method_exists($lib, "getProg")){
						$lib_array[$name[count($name)-2]][$k] = $lib->getProg();
					}

					break;
				case "Permission":
					//后台子目录列表
					if(method_exists($lib, "get_name")){
						if($lib->get_name() != null){
							$list = array("name"=>$lib->get_name(),
								 "desc"=>$lib->get_desc(),
								 "ismenu"=>$lib->get_ismenu());
							$lib_array[$name[count($name)-2]][$k] = $list;
								
						}
					}
						
					break;
				default:
					;
					break;
			}

			unset($lib);
		}
		$BASETHEME_COMMON_ACTION = true;
		if(empty($lib_array)) return null;

		$this->acl_save_lib($lib_array);

		return $lib_array;
	}

	/**
	 * @name acl_save_lib
	 * @package save lib for admin ON DUPLICATE KEY UPDATE
	 * @return false
	 */
	private function acl_save_lib($lib_arr) {
		if(count($lib_arr) < 1) return ;

		$add_menu = "INSERT INTO `".DB_PRE_CORE."roles` ( `pid`, `lib`, `name`, `desc`, `ismenu`, `types`)";
		$add_menu .= " VALUES ( '%d', '%s', '%s', '%s', '%d', '%d')";

		$update_menu = "UPDATE `".DB_PRE_CORE."roles` SET `name` = '%s', `desc`='%s' WHERE `id` = %d and types=%d;";

		$slist = "SELECT id FROM `".DB_PRE_CORE."roles` where lib='%s' and types =%d limit 1";

		$pid = 0;
		foreach ($lib_arr as $k=>$v) {
			if(!isset($v[$k.'_index']))continue;

			$sql_list = sprintf($slist, $k, DB_TYPE_DIFFERENCE);

			$res = $this->FetchOne($sql_list);
			if(empty($res['id'])){
				$pid = 0;
				$sql = sprintf($add_menu, $pid, $k, $v[$k.'_index'], '','', DB_TYPE_DIFFERENCE);
				$pid = $this->Insert($sql, GetPB("w","1"));
				if(isset($pid[0]['id'])){
					$pid = $pid[0]['id'];
				}else{
					throw new \Exception("save_lib '{$sql}' sql insert error, aclrole.php.");
				}
			}else{
				$pid = $res['id'];
				$sql = sprintf($update_menu, $v[$k.'_index'],'', $pid, DB_TYPE_DIFFERENCE);
				$this->Update($sql);

			}
			if(!is_array($v)) continue;
			foreach ($v as $sub_k=>$sub_v) {
				if(!is_array($sub_v)) continue;
				$sql = sprintf($slist, $sub_k, DB_TYPE_DIFFERENCE);
				$res = $this->FetchOne($sql);
				if(empty($res)){
					$sql = sprintf($add_menu, $pid, $sub_k, $sub_v['name'], $sub_v['desc'], $sub_v['ismenu'], DB_TYPE_DIFFERENCE);
					$this->Insert($sql);
				}else{
					$id = $res['id'];
					$sql = sprintf($update_menu, $sub_v['name'],$sub_v['desc'], $id, DB_TYPE_DIFFERENCE);
					$this->Update($sql);
				}

			}
		}
	}
}

?>