<?php

if (!defined('M5CPL'))exit;
/*
 *s_page
 *@param $url
 *@param $total
 *@param $page
 *@param $limit
 *@return [上一页][1][2][3][下一页]
 */
function s_page($url,$total,$page,$limit=20,$name=''){
	if(!empty($name)) $name = '&'.$name;
	$ceil = ceil($total/$limit);
	
	$ceil >1 && $ceil_limit=5;

	if($ceil >1 && $page>=5){
		$ceil_limit=10;
	}
	if($page>=8){
		$ceil-$page >10? $st=$page-5:$st=$ceil-10;
		$ceil_limit += $st-1;
		if($ceil - $page <8){
			$st = $page-2;
			$ceil_limit = $ceil;
		}
		if($ceil - $page ==10){
			$st = $page-2;
			$ceil_limit =$st+9;
		}
	}else {
		$st=1;
	}
	$ceil <$ceil_limit && $ceil_limit=$ceil;
	$spage = '';
	for ($st;$st<=$ceil_limit;$st++){
		if ($page == $st || (empty($page) && $page==$st-1)) {
			$page==0&&$page=1;
			$spage .="<li class='active'><a href='#'>[$page]</a></li>";
		}else{		
			$spage .= "<li><a href='".$url.$st."' >[$st]</a></li>";
		}
	}

	if($page>=$ceil){
		$npage = "<li class='active'><a href='#'>[已到尾页]</a></li>";
	}else {		 
		$npage = "<li><a href='".$url.($page+1)."' >[下一页]</a></li>";
		$npage .= "<li><a href='".$url.$ceil."' >[到尾页]</a></li>";
	}

	$ppage = ($page>7) ? "<li><a href='".$url."1' >[回首页]</a></li>" : '';

	$s = $ceil>1?$ppage.$spage.$npage:$spage;
	
	return  $s;
}

/*
 *s_page
 *@param $url
 *@param $total
 *@param $page
 *@param $limit
 *@return [上一页][1][2][3][下一页]
 */
function search_page($url,$total,$page,$limit=20,$name=''){
	$ceil = ceil($total/$limit);
	$ceil >1 && $ceil_limit=5;
	global $P2HConfig;
	$isStatic=($P2HConfig['isStatic']===true);
	if($ceil >1 && $page>=5){
		$ceil_limit=10;
	}
	if($page>=8){
		$ceil-$page >10? $st=$page-5:$st=$ceil-10;
		$ceil_limit += $st-1;
		if($ceil - $page <8){
			$st = $page-2;
			$ceil_limit = $ceil;
		}
		if($ceil - $page ==10){
			$st = $page-2;
			$ceil_limit =$st+9;
		}
	}else {
		$st=1;
	}
	$ceil <$ceil_limit && $ceil_limit=$ceil;
	$spage = '';
	for ($st;$st<=$ceil_limit;$st++){
		if ($page == $st || (empty($page) && $page==$st-1)) {
			$page==0&&$page=1;
			$spage .="[$page]";
		}else{
			$spage .= "<a href='".$url."?".$name."&pag=".$st."&tol=".$total."'>[$st]</a>";
		}
	}

	if($page>=$ceil){
		$npage = "[已到尾页]";
	}else {
		$npage = "<a href='".$url."?".$name."&pag=".($page+1)."&tol=".$total."'>[下一页]</a>";
		$npage .= "<a href='".$url."?".$name."&pag=".$ceil."&tol=".$total."'>[到尾页]</a>";
	}

	$ppage = ($page>7) ? "<a href='".$url."?".$name."&pag=1&tol=".$total."'>[回首页]</a>" : '';
	return $ceil>1?$ppage.$spage.$npage:$spage;
}

function s_pageajax($url,$total,$page,$limit=20,$name=''){
	$ceil = ceil($total/$limit);
	$ceil >1 && $ceil_limit=5;
	if($ceil >1 && $page>=5){
		$ceil_limit=10;
	}
	if($page>=8){
		$ceil-$page >10? $st=$page-5:$st=$ceil-10;
		$ceil_limit += $st-1;
		if($ceil - $page <8){
			$st = $page-2;
			$ceil_limit = $ceil;
		}
		if($ceil - $page ==10){
			$st = $page-2;
			$ceil_limit =$st+9;
		}
	}else {
		$st=1;
	}
	$ceil <$ceil_limit && $ceil_limit=$ceil;
	$spage = '';
	for ($st;$st<=$ceil_limit;$st++){
		if ($page == $st || (empty($page) && $page==$st-1)) {
			$page==0&&$page=1;
			$spage .="<font>[$page]</font>&nbsp;";
		}else
		$spage .= "<a  href='#show_guest' newhref='".$url."?".$name."&pag=".$st."&tol=".$total."#viewpage'><font>[$st]</font></a>&nbsp;";
	}

	if($page>=$ceil){
		$npage = "<font>[已到尾页]</font>";
	}else {

		$npage = "<a href='#show_guest' newhref='".$url."?".$name."&pag=".($page+1)."&tol=".$total."#show_guest'>[下一页]</a>";
		$npage .= "<a href='#show_guest' newhref='".$url."?".$name."&pag=".$ceil."&tol=".$total."#show_guest'>[到尾页]</a>";
	}
	$ppage = ($page>7) ? "<a href='#show_guest' newhref='".$url."?".$name."&pag=1&tol=".$total."#show_guest'>[回首页]</a>" : '';
	return $ceil>1?$ppage.$spage.$npage:$spage;
}

/**
 *
 * 兼容P2H类的分页
 *@param $url
 *@param $total
 *@param $page
 *@param $limit
 *@return [上一页][1][2][3][下一页]
 */
function show_page($url,$total,$page,$limit=20,$where='') {
	if(!empty($where)) $where = '&'.$where;
	$ceil = ceil($total/$limit);
	$ceil >1 && $ceil_limit=5;
	if($ceil >1 && $page>=5){
		$ceil_limit=10;
	}
	if($page>=8){
		$ceil-$page >10? $st=$page-5:$st=$ceil-10;
		$ceil_limit += $st-1;
		if($ceil - $page <8){
			$st = $page-2;
			$ceil_limit = $ceil;
		}
		if($ceil - $page ==10){
			$st = $page-2;
			$ceil_limit =$st+9;
		}
	}else {
		$st=1;
	}
	$ceil <$ceil_limit && $ceil_limit=$ceil;

	for ($st;$st<=$ceil_limit;$st++){
		if ($page == $st || (empty($page) && $page==$st-1)) {
			$page==0&&$page=1;
			$spage .="<font style='background:#00a0cb;padding:1px 6px;color:#fff;margin-right:15px'>$page</font>";
		}else{
			$urls = P2H::RW($url."?pag=".$st.$where);
			$spage .= "<a href='".$urls."'><font>$st</font></a>";
		}
	}

	if($page>=$ceil){
		$npage = "<font>[已到尾页]</font>";
	}else {

		$urls = P2H::RW($url."?pag=".($page+1).$where);
		$urlend = P2H::RW($url."?pag=".$ceil.$where);
		$npage = "<a href='".$urls."'>下一页</a>";
		$npage .= "<a href='".$urlend."'>到尾页</a>";
	}
	$furl = P2H::RW($url."?pag=1".$where);
	$ppage = ($page>7) ? "<a href='".$furl."'>回首页</a>" : '';
	return $ceil>1?$ppage.$spage.$npage:$spage;
}
?>