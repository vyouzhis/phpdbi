<?php
if (!defined('M5CPL'))
exit;

class blog extends BaseTheme{
	
	private $switch;
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
    		$name = $match['params']['switch'];
    		switch ($name) {
    			case 'bi':
    				$this->switch = 0;
    				break;
    			case 'crm':
    				$this->switch = 1;
    				break;
    			default:
    				$this->switch = 0;
    				break;
    		}
    		    		
    		$p = $match['params']['p'];
    		if(empty($p)) $p=0;
    		
    		$blogs = $this->listBlog($p);
    	
    		$page = s_page('/blog/'.$match['params']['switch']."/", $this->tol(), $p, $this->limit);
			    		
    		$click = $this->click();
    		
	    	require_once parent::view();
    }
    
	private function listBlog($p) {
		 
		$p==1 && $p=0; 
		if($p>0) $p=$p-1;
		
		$format = 'select * from blog where cid=%d order by ctime desc LIMIT %d , %d';
		$sql = sprintf($format, $this->switch, $this->limit*$p, $this->limit);	
		
		$res = parent::FetchArray($sql);
		return $res;
	}
	
	private function tol() {
		$format = 'select count(*) as count from blog where cid=%d  LIMIT 1';
		$sql = sprintf($format, $this->switch);	
		$res = parent::FetchOne($sql);		
		return $res['count'];
	}
	
	private function click() {
		$format = 'select id,title,auth from blog  order by rand() desc LIMIT 3';

		$res = parent::FetchArray($format);
		return $res;
	}
    
}