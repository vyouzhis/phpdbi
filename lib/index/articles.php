<?php
if (!defined('M5CPL'))
exit;

class articles extends BaseTheme{
	
	private $id;
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
    		$this->id = $match['params']['id'];
    			    	
    		$article = $this->getArticle();
    		$name = $match['params']['switch'];
    		$click = $this->click();
	    	require_once parent::view();
    }
    
    
    private function getArticle() {
    	$format = 'select * from blog where id = %d limit 1;';
    	$sql = sprintf($format, $this->id);
    	
    	$res = parent::FetchOne($sql);
    	return $res;
    }
    
private function click() {
		$format = 'select id,title,auth from blog  order by click desc LIMIT 3';

		$res = parent::FetchArray($format);
		return $res;
	}

}