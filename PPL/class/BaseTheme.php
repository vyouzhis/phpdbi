<?php
/*
 * 基类模版装载
 *
 */

class BaseTheme extends BaseLang{
	private $stdClass;
	private $headClass = WEB_HEADER; //自动在 view 那儿加入头部
	private $footClass = WEB_FOOT;  // 与 ajax 值 相关，会自动加入脚部
	public $porg=null;     // 获取 post or get 
	public $limit=LIMITS;   // 默认的 limit 
	public $ajax=0;   // 0 会自动加入脚 部， 1 就不会

	public function __construct($stdClass=null) {
		$this->stdClass = $stdClass;
		parent::__construct($stdClass);
		$this->porg = PorG();
		$this->ParentCookie();
	}
	/**
	 * @name Show
	 * @param String $arg
	 * @package 所有的 lib 入口 ...
	 * @return include or echo
	 *
	 */
	public function Show($arg=null){
		
		global $router, $match;

		if($arg!=null){
			$this->stdClass = $arg;
		}
		
		$rest = $this->view();
		if($rest!=false){
			include $rest;
		}else{
			echo $rest;
		}
	}
	
	private function ParentCookie(){
			global $router, $match; 
    		//将传参插入cookie

			$info=$this->porg;
			$parent_id = intval($info['aid']); // 推荐人或代理人
			$recommend_id = intval($info['utid']);
			//封锁upid=26229的所有链接
    		//if($parent_id==26229){header("Location:http://www.elon.com/404");$parent_id="";}
		    /*获取parentID和recommendID，并存入cookie*/
			if($parent_id)
			{
				setcookie ( 'aid' ,  $parent_id ,  time ()+ 86400 ,  "/" ,DOMAIN );
			}
			/** 推荐人id **/
			if($recommend_id)
			{
				setcookie ( 'recommend_id' ,  $recommend_id ,  time ()+ 86400 ,  "/" ,DOMAIN );
			}
	}

	/**
	 * @name view
	 * @param unknown_type $arg
	 * @package give you a temple html
	 */
	public function view($arg=null) {
		global $router, $match;
		$path = $this->stdClass;
		if($arg!=null){
			$path = $arg;
			//echo $path;
		}else{
			if(!is_file(LIBS)) CacheLibs();
			include(LIBS);
			$m_lib = unserialize($_libs);
			$dir = explode("lib", $m_lib[$this->stdClass]);
			$path = substr($dir[1],0, -4);
			//echo $path;
		}
		// 引入 头文件
		
		$this->common($this->headClass);
		
		$rest = display($path);
		
		return  $rest;
	}
	
	/**
	 *
	 * @name header
	 * @param String $arg
	 * @package common header html
	 * @return include temple html
	 */

	public function header($arg=WEB_HEADER) {
		$this->headClass = $arg;
	}

	/**
	 *
	 * @name foot
	 * @param String $arg
	 * @package common foot html
	 * @return include temple html
	 */
	public function foot($arg=WEB_FOOT) {
		$this->footClass = $arg;
	}

	/**
	 *
	 * @name common
	 * @param $arg
	 * @package inclue default common lib
	 */
	private function common($arg=null) {
		global $BASETHEME_COMMON_ACTION;
		if($arg && $this->ajax==0){
			routing($arg);
			if(class_exists($arg)){
				$v = new $arg;

				if(get_parent_class($v) == "BaseModule" && $BASETHEME_COMMON_ACTION){
					$v->filter();
					
				}
			}

		}
	}


	/**
	 *
	 * @name article_right
	 * @param $arg
	 * @package inclue category/category_right_article.html
	 * //主要是文章终端页面 和列表页面用到的
	 */
	
	public function article_right(){
		require_once Bin.'/CategoryModel.php';
 		$cmodel = new CategoryModel() ; 
    	$hot_top = $cmodel->getInformation(0,0,1,"c");  
   		$hot = $cmodel->getInformation("117,12,18,20,19,17,120,11113,14,16",1,5,"c");
   		require_once THEME. "/category/category_right_article".".html";
	}
	/* category_right_article_wiki */
	public function wiki_article_right()
	{
		require_once Bin.'/CategoryModel.php';
		$cmodel = new CategoryModel() ;
		$hot_top = $cmodel->getInformation(0,0,1,"c");
		$hot = $cmodel->getInformation("117,12,18,20,19,17,120,11113,14,16",1,5,"c");
	/* 	//热门排行
		$sql = "SELECT * FROM el_article WHERE c_id = ".$article['c_id']." ORDER BY click DESC LIMIT 10";
		//echo $sql;
		$hottop = parent::FetchArray($sql); //$this->getInformation(0,0,10,"c");
		//var_dump($hottop); exit;
		//最新更新
		$sql = "SELECT * FROM el_article WHERE c_id = ".$article['c_id']." ORDER BY add_time DESC LIMIT 10";
		
		$newtop = parent::FetchArray($sql);
		 */
		
		require_once THEME. "/category/category_right_article_wiki".".html";
	}
	
	/**
	* @desc    热门排行|最新更新
	* @access  public
	* @param   int c_id
	* @return  array 
	*/
	public function hotandnew($c_id,$action)
	{
		if(empty($c_id))
		{return false;}
		//热门排行
		$sql = "SELECT * FROM el_article WHERE c_id = ".$c_id." ORDER BY click DESC LIMIT 10";
		$hottop = parent::FetchArray($sql); //$this->getInformation(0,0,10,"c");
		//最新更新
		$sql = "SELECT * FROM el_article WHERE c_id = ".$c_id." ORDER BY add_time DESC LIMIT 10";
		$newtop = parent::FetchArray($sql);
		require_once THEME. "/category/category_right_article_wiki_hotnew".".html";
		
		//return array("hottop"=>$hottop,"newtop"=>$newtop);
		
	}
	public function _MUrl($key) {
		global $router, $match;
		return $router->url($key);
	}
	
	
	

	/**
	 *
	 * @name __destruct
	 */
	function  __destruct () {
		// 最后加入脚模版
				
		$this->common($this->footClass);
		//echo "<br />foot<br />";
	}

}
