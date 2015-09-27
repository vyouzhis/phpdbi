<?php
if (!defined('M5CPL'))
exit;

class pubdata extends BaseTheme{
	
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
    			case 'business':
    				$this->switch = 0;
    				$name="商业数据";
    				break;
    			case 'public':
    				$this->switch = 1;
    				$name="公共数据";
    				break;
    			default:
    				$this->switch = 0;
    				$name="商业数据";;
    				break;
    		}
    		
    		$p = $match['params']['p'];
    		if(empty($p)) $p=0;
    		
    		$Analysis = $this->newAnalysis($p);
    
    		$page = s_page('/analysis/'.$match['params']['switch']."/", $this->tol(), $p, 2);
    		
	    	require_once parent::view();
    }
    

    private function newAnalysis($p) {
    	$p==1 && $p=0;
    	if($p>0) $p=$p-1;
    	$format = "select * from analysis where cid=%d order by ctime desc LIMIT %d , %d";
    	$sql = sprintf($format,  $this->switch, 2*$p, 2);
    	
    	$res = parent::FetchArray($sql);
    	return $res;
    }
    
    private function tol() {
    	$format = 'select count(*) as count from analysis where cid=%d  LIMIT 1';
    	$sql = sprintf($format, $this->switch);
    	$res = parent::FetchOne($sql);
    	return $res['count'];
    }
}