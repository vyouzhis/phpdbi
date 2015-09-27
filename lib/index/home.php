<?php
if (!defined('M5CPL'))
exit;

class home extends BaseTheme{
	
	
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
    		
    		$blogs = $this->newBlog();
	    	require_once parent::view();
    }
    

    private function newBlog() {
    	$sql = "select id,title,`desc`,img,ctime from blog order by ctime desc limit 6;";
    	$res = parent::FetchArray($sql);
    	return $res;
    }
}