<?php
if (!defined('M5CPL'))
exit;

class guy extends BaseTheme{
	
	
	public function __construct() { 
        parent::__construct(__CLASS__); 
    } 
	
    /**
     * 入口文件
     * @see PPL/class/BaseTheme::Show()
     * @param
     */
    public function Show($arg = NULL) {
    		global $router, $match; 
    		
	    	require_once parent::view();
    }
    

}