<?php
if (!defined('M5CPL')){exit;}

/**
 * 
 * 用在后台总栏目菜单上 ...
 * @author jsb-015
 *
 */
class BasePrograma extends BaseLang{
	private $programa;
	public function __construct($progName='') {
		parent::__construct();
		$this->programa = $progName;		
	}
	
	public function getProg(){		
		return parent::_Lang($this->programa);
	}
}
