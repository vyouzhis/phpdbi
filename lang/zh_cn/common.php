<?php
if (!defined('M5CPL'))
exit;

/**
 *
 * 公共语言类 ...
 * @author vyouzhi
 *
 */
class _common extends LibLang {
	private $_lan;
	private $class = "";
	
	public function __construct() {
		$this->class = substr(get_class(),1);
		
		$this->common_err();
		
		parent::__construct($this->_lan);
	}

	private function common_err() {
		$info=array(
			"error_user"=>"用户名错误",
			"error_passwd"=>"密码错误",		
		);

		foreach ($info as $k=>$v) {
			$this->_lan[$this->class."_".$k]=$v;
		}

	}
	

}
