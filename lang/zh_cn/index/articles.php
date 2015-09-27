<?php
if (!defined('M5CPL'))
exit;

class _articles extends LibLang{
	private $_lan_user_list;
	private $class = "";
	
	public function __construct() {
		$this->class = substr(get_class(),1);
		
		$this->_lan = array(
			$this->class=>"新闻资讯首页",			
		);

		$this->list_table();
		
		parent::__construct($this->_lan);
	}


	private function list_table() {
		$info=array(
			"menu"=>"讯菜单",				
			"error_user"=>"index key",
		);

		foreach ($info as $k=>$v) {
			$this->_lan[$this->class."_".$k]=$v;
		}

	}

}

