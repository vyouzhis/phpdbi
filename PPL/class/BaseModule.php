<?php
if (!defined('M5CPL'))
exit;

/**
 * 
 * 引入 module 类
 * @author vyouzhi
 *
 */
class BaseModule extends BaseLang{
	private $stdClass;
	private $stdObject;
	public function __construct($stdClass=null) {
		parent::__construct($stdClass);
		$this->stdClass = $stdClass;		
	}
		
	public function filter(){		
		//echo COMMON_TEMPLE.$this->stdClass;
		include_once display(COMMON_TEMPLE.$this->stdClass);
	}
	
	public function view() {
		return display(COMMON_TEMPLE.$this->stdClass);
	}
	

}