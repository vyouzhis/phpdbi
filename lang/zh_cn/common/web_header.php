<?php
if (!defined('M5CPL'))
exit;

class _web_header extends LibLang{
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
			"title"=>"炒黄金开户,白银开户,外汇开户投资平台-义隆金融",		
			"keywords"=>"黄金投资,炒黄金,黄金开户,炒黄金开户,炒黄金开户平台,白银投资,炒白银,白银开户,炒白银开户,炒外汇,外汇投资,外汇开户,炒外汇开户,炒外汇开户平台",		
			"description"=>"义隆金融是权威专业的外汇黄金投资公司，受国际著名金融监管机构FSA监管、24小时提供外汇开户、外汇交易、白银开户、黄金投资咨询服务,是最理想的炒黄金、炒白银、炒外汇平台",		
			"error_user"=>"index key",
		);

		foreach ($info as $k=>$v) {
			$this->_lan[$this->class."_".$k]=$v;
		}

	}

}

