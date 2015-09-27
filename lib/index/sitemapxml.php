<?php
if (!defined('M5CPL'))
exit;

class sitemapxml extends BaseTheme{
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
    		$this->ajax = 1;    	
			$result = $this->index();
	    	
    }
    
    private function index() {
    	header('Content-Type: text/xml');
    	$cidstr = "11113,120,17,117,18,19,20,12,11114,11115,24,14,16,39,43,41";
    	$result= $this->getArticle($cidstr,0,500);
    	$echo .='<?xml version="1.0" encoding="UTF-8"?><urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
		foreach ($result as $k => $v) {
			$echo .='<url>
			 <loc>'.$v['link'].'</loc>
			 <lastmod>'.date("Y-m-d",$v['add_time']).'</lastmod>
			</url>';
		}
		$echo .='</urlset>';
		echo $echo;
    }
    
    /**
     * @name getArticle
     * @param c_id 栏目ID
     * @param limit 取出信息条数
     * @param flag 类型(头条、推荐等)
     * @package 获取网站公告信息
     * @return array or false
     */
    
    private function getArticle($c_id,$start="0",$limit="4",$flag="",$is_pic="",$order="add_time desc,sortrank desc"){
    	global $CategoryIdName ;
    	 
    	$select = "article_id,c_id,title,title_pic,abstract,add_time,out_time,link,color";
    	$where = "WHERE c_id in (".$c_id.")";
    	if(!empty($is_pic)){//是否需要图片
    		$where .= " and is_pic=".$is_pic;
    	}
    	if(!empty($flag)){//是否需要文章属性为：头条.如果等于P，则无flag条件
    		$where .= " and flag='".$flag."'";
    	}
    	if($start!=0){//是否从第一条开始
    		$limit = $start.",".$limit;
    	}
    	$format = "SELECT %s FROM ".DB_PRE."article  %s order by %s limit %s";
    	$sql = sprintf($format,$select,$where,$order,$limit);
    	//echo $sql."<br />";
    	$result = parent::FetchArray($sql);
    	foreach ($result as $key => $v)
    	{
    		if(empty($v['link']))
    		{
    			$result[$key]['link'] = WEB."/".$CategoryIdName[$v['c_id']].'/'. date("Ymd",$v['add_time'])."-".$v['article_id'].".html";
    		}
    	}
    	return $result;
    }
    
   
   /**
	 * @name getfLink 链接信息
	 * @param typeid 类型 
	 * @param order 排序
	 * @return array or false
	 */
	 
    private function getfLink($typeid="",$order="sortrank desc,addtime desc"){
    	$where = "WHERE status=1";
    	if(!empty($typeid)){
    		$where .=" AND typeid=".$typeid;
    		}
    	$format = "SELECT * FROM ".DB_PRE."flink %s order by %s";
    	$sql = sprintf($format,$where,$order);
    	$result = parent::FetchArray($sql);
    	return $result;
    }
    
    /**
	 * @name  专家问答
	 * @param typeid 类型 
	 * @param order 排序
	 * @return array or false
	 */
	 
    private function getReply($start=0,$limit,$order="M.m_time desc"){
    	$select = "M.m_id,M.user_id,M.username,M.type_id,M.m_body,M.m_time,M.isreply,R.reply_name,R.reply_body,R.reply_time";
    	$where = "WHERE M.isreply=1 and M.type_id=8";
    	if($start!=0){
    		$limit = $start.",".$limit;
    		}
    	$format = "SELECT %s FROM ".DB_PRE."message_main M left join ".DB_PRE."message_reply R on M.m_id=R.m_id %s order by %s limit %s";
    	$sql = sprintf($format,$select,$where,$order,$limit);
			//echo $sql."<br />";
			$result = parent::FetchArray($sql);
    	return $result;
    }
//获取推荐视频
    private function queryCommendVideo(){
    	$video = array();
    	$sql_top = "SELECT fid , title ,link,flashurl ,titlepic,flashtime,flashsay,addtime FROM  ".DB_PRE."flash WHERE `is_display` = '1' ORDER BY fid DESC  LIMIT 5  " ;
    	
    	$video = parent::FetchArray($sql_top);
    	return $video ; 
    }
}