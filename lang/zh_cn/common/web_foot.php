<?php
if (!defined('M5CPL'))
exit;

class _web_foot extends LibLang{
	private $_lan_user_list;
	private $class = "";
	
	public function __construct() {
		$this->class = substr(get_class(),1);
		
		$this->_lan = array(
			$this->class=>"网站首页",			
		);

		$this->list_table();
		
		parent::__construct($this->_lan);
	}


	private function list_table() {
		$info=array(
			"menu"=>"首页菜单",
			"tt"=>"xxx",		
			"tt"=>"xxx",		
			"error_user"=>"index key",
		);

		foreach ($info as $k=>$v) {
			$this->_lan[$this->class."_".$k]=$v;
		}

	}

}

