<?php 

 class WikiModel extends DBSQL
{

    /**
    *  @desc  是否已进行初始化
    *  @var  bool
    *  @access  private
    */
	public $wike = array( 'gold' => 11120,'baiyin' =>11126,'waihui' =>11131,'jiaoyipingtai' =>11136,'guzhi' =>11140,'oil' =>11144);
	
	//$wikename = array('黄金百科','白银百科','外汇百科','交易平台','股指百科','原油百科');
	function __construct($stdClass=null)
	{
		parent::__construct();

	}
	
	
	/**
	* @desc    获取所有百科子类
	* @access  public
	* @param  int 
	* @return  string 返回option 格式
	*/
	public function getCategory()
	{
		$wike = $this->wike;
		$goldResult  = array();
		foreach ($wike as $value)
		{
			$goldCate=$this->getSubCategory($value);
			if(is_array($goldCate))
			{
				foreach ($goldCate as $k =>$v)
				{
					if(!empty($v['siteurl'])){
		
						$goldResult[$v['siteurl']] = $v['c_id'] ;
					}
				}
			}
		}
		 return array_merge($goldResult,$wike) ;
		
	}
	
	/**
	* @desc    发布文章获取子类下的文章
	* @access  public
	* @param   int 
	* @return  array list
	*/
	public function getSubCateList()
	{
		$wike = $this->wike;
		$wikearr = array();
		foreach ($wike as $value)
		{
			$goldCate=$this->getSubCategory($value);
			$goldResult  = array();
			if(is_array($goldCate))
			{
				foreach ($goldCate as $k =>$v)
				{
					$goldResult[$k]['c_id'] = $v['c_id'];
					$goldResult[$k]['c_name'] =$v['c_name'];
					$goldResult[$k]['list'] = $this->getList($v['c_id'],$page);
					$goldResult[$k]['siteurl'] = !empty($v['siteurl']) ? $v['siteurl'] : "";
				}
			}
			$wikearr[] = $goldResult;
		}
		return $wikearr;
	}
	
	
	/**
	 * @desc    获取子类
	 * @access  public
	 * @param   int
	 * @return  array
	 */
	public function getSubCategory($c_id)
	{
		$select = "c_id,p_id,c_name,subtitle,c_keywords,c_description,siteurl";
    	$where = "WHERE is_hidden = 0 and  p_id =".$c_id;    	 
    	$format = "SELECT %s FROM ".DB_PRE."article_category  %s order by c_id asc";
		$sql = sprintf($format,$select,$where);
		//echo $sql."<br />";
		$result = parent::FetchArray($sql);
		return $result;
	}
	
	/**
	 * @name getList
	 * @param $cate 栏目名称
	 * @package 获取单个栏目内容
	 * @return array or false
	 */
	
	private function getList($c_id,$page,$keywords="",$limit=10){
		$select = "article_id,c_id,title,title_pic,keywords,abstract,add_time,out_time,source,author,link,color";
		$order = "add_time desc,sortrank desc";
		$start= ($page-1)*10;
		$limits= $start.",".$limit;
		 
		$where = "WHERE c_id in(".$c_id.") AND is_display=1 ";
		if($c_id==0){
			$where ="WHERE 1=1 ";
		}
		if(!empty($keywords)){
			$where .= " and (";
			$KArray=explode(" ",$keywords);
			foreach($KArray as $key => $v){
				$where .= " title like '%".$v."%' or ";
				$where .= " keywords like '%".$v."%'";
			}
			$where .=" )";
		}
		$format = "SELECT %s FROM ".DB_PRE."article  %s order by %s limit %s";
		$sql = sprintf($format,$select,$where,$order,$limits);
		//echo $sql."<br />";
		$result = parent::FetchArray($sql);
		return $result;
	}
	
	

    
   

}