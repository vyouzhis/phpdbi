<?php
if (!defined('M5CPL')){exit;}

//动态引入别的模块，比如左边菜单栏
require_once Lib.'/common/admin_menu.php';
require_once Lib.'/common/admin_navbar.php';
require_once Log.'/SystemLog.php';

/**
 * @name BasePerminterface
 * define in config.php ADMIN_ACL_ACTION read|create|edit|remove|search
 * @author vyouzhi
 *
 */
interface BasePerminterface{
	public function read($arg);
	public function create($arg);
	public function edit($arg);
	public function remove($arg);
	public function search($arg);
}

/**
 *
 * 用在后台某一模块的功能类上面，该类包含了模版，语言 ...
 * @author jsb-015
 *
 */
class Permission extends BaseTheme implements BasePerminterface{
	private $mod_name;  // 模块的名称
	private $mod_desc;  // 模块的描述
	private $mod_ismenu; // 是0 action 还是  1 菜单
	private $acl;
	private $log; //log记录

	public function __construct($stdClass=null) {
		global $router, $match;
		parent::__construct($stdClass);
		$res = $this->IsLogin();

		if($res==false && !in_array($stdClass, explode(",",LOGIN_MODULE))){

			ShowMsg(parent::_Lang("error_any"), $router->url('admin_login'));
		}
		$this->log = new SystemLog();
		
	}

	/**
	 * @name header
	 * @param String $arg default ADMIN_HEADER
	 * @package 重新一下 header 让其可以自动加入页头
	 * @see PPL/class/BaseTheme::header()
	 */
	public function header($arg=WEB_HEADER) {
		$this->headClass = $arg;
		parent::header($this->headClass);
	}

	/**
	 * @name foot
	 * @param String $arg default  ADMIN_FOOT
	 * @package 重新一下 foot 让其可以自动加入页脚
	 * @see PPL/class/BaseTheme::foot()
	 */
	public function foot($arg=WEB_FOOT){
		$this->footClass = $arg;
		parent::foot($this->footClass);
	}

	/**
	 *
	 * @name checkRole
	 * @param String $action
	 * @package 检查用户自己的权限
	 * @return true or false
	 */
	public function checkRole($action) {
		global $router, $match;
		$res = parent::acl_myRole($action);
		
		if($res == false) ShowMsg(parent::_Lang("error_permission"), $router->url('admin_index'));
	}

	/**
	 * @name get_name
	 * @package 获得 lib name
	 * @return string
	 */
	public function get_name() {
		return $this->mod_name;
	}

	/**
	 * @name get_desc
	 * @package 获得 lib desc
	 * @return String
	 */
	public function get_desc() {
		return $this->mod_desc;
	}

	/**
	 * @name get_ismenu
	 * @package 获得 menu 状态 0 主栏目 1 栏目 2 action
	 * @return String
	 */
	public function get_ismenu() {
		return $this->mod_ismenu;
	}

	/**
	 * @name set_name
	 * @package 设计栏目名
	 * @param String $arg
	 */
	public function set_name($arg) {
		$this->mod_name = $arg;
	}

	/**
	 * @name set_desc
	 * @package 设置简介
	 * @param String $arg
	 */
	public function set_desc($arg) {
		$this->mod_desc = $arg;
	}
	/**
	 * @name setMainMenu
	 * @package 标识为主栏目
	 */
	public function setMainMenu() {
		$this->mod_ismenu = 0;
	}

	/**
	 * @name setMenu
	 * @package 标识为栏目
	 */
	public function setMenu() {
		$this->mod_ismenu = 1;
	}
	/**
	 * @name setAction
	 * @package 标识为action
	 */
	public function setAction() {
		$this->mod_ismenu = 2;
	}

	/**
	 * @name IsLogin
	 * @package 检查是否登录 ...
	 */
	public function IsLogin() {

		return $this->acl_CheckAccess();
	}

	/**
	 * @name Menu
	 * @package 这儿处理菜单栏
	 */
	public function left_menu() {
		global $router, $match;

		$menu = new admin_menu();
		$menu->filter();
	}
	/**
	 * @name navbar
	 * @package 这儿处理 navbar
	 */
	public function navbar() {
		$navbar = new admin_navbar();
		$navbar->filter();
	}

	public function read($arg){
		$this->checkRole(__FUNCTION__);
		$this->log->saveLog(parent::acl_getUid(), 0);
	}

	public function create($arg){
		$this->checkRole(__FUNCTION__);
		$this->log->saveLog(parent::acl_getUid(), 1);
	}

	public function edit($arg){
		$this->checkRole(__FUNCTION__);
		
		$this->log->saveLog(parent::acl_getUid(), 2);
	}
	public function remove($arg){
		$this->checkRole(__FUNCTION__);
		$this->log->saveLog(parent::acl_getUid(), 3);
	}
	public function search($arg){
		$this->checkRole(__FUNCTION__);
		$this->log->saveLog(parent::acl_getUid(), 4);
	}
	
	public function Url_Read() {
		global $router;
		return $router->url($this->stdClass, array("action"=>"read"));
	}
	
	public function Url_Create() {
		global $router;
		return $router->url($this->stdClass, array("action"=>"create"));
	}
	
	public function Url_Edit() {
		global $router;
		return $router->url($this->stdClass, array("action"=>"edit"));
	}
	
	public function Url_Remove() {
		global $router;
		return $router->url($this->stdClass, array("action"=>"remove"));
	}
	
	public function Url_Search() {
		global $router;
		return $router->url($this->stdClass, array("action"=>"search"));
	}
}
