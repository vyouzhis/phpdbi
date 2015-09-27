<?php

/**
 * @exception 常用的小函数放在这儿
 * @copyright by vyouzhi on 20080811
 * @name function.php
 */
/**
 * @ignore
 */
if (!defined('M5CPL'))
exit;



/**
 * @desc 截取字符串
 * Enter description here ...
 * @param string  $string 截取字符
 * @param int     $start  开始位置
 * @param int     $length  截取长度
 * @param bool    $mode
 * @param string  $dot
 * @param string  $encode 编码格式
 * @return string $strcut
 */
function str_cut($string,$length,$start=0,$mode=false,$dot='',$encode='utf-8')
{
	$strlen = strlen($string);
	if($strlen <= $length)
	{
		return $string;
	}
	$string = str_replace(array('&nbsp;','&amp;','&quot;','&lt;','&gt;','&#039;'), array(' ','&','"','<','>',"'"), $string);
	$strcut = '';
	if($encode == 'utf-8')
	{
		$n = $tn = $noc = 0;
		while($n < $strlen)
		{
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126))
			{
				$tn = 1; $n++; $noc++;
			}
			elseif(194 <= $t && $t <= 223)
			{
				$tn = 2; $n += 2; $noc += 2;
			}
			elseif(224 <= $t && $t < 239)
			{
				$tn = 3; $n += 3; $noc += 2;
			}
			elseif(240 <= $t && $t <= 247)
			{
				$tn = 4; $n += 4; $noc += 2;
			}
			elseif(248 <= $t && $t <= 251)
			{
				$tn = 5; $n += 5; $noc += 2;
			}
			elseif($t == 252 || $t == 253)
			{
				$tn = 6; $n += 6; $noc += 2;
			}
			else
			{
				$n++;
			}
			if($noc >= $length)
			{
				break;
			}

		}
		if($noc > $length)
		{
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
	}
	else
	{
		for($i = 0; $i < $length; $i++)
		{
			$strcut .= ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
		}
	}
	$strcut = str_replace(array('&','"','<','>',"'"), array('&amp;','&quot;','&lt;','&gt;','&#039;'), $strcut);
	return $strcut.$dot;
}

function CUrl( $url,  $javascript_loop = 0, $timeout = 5 )
{
	$url = str_replace( "&amp;", "&", urldecode(trim($url)) );


	$ch = curl_init();
	curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
	curl_setopt( $ch, CURLOPT_URL, $url );
	curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
	curl_setopt( $ch, CURLOPT_ENCODING, "" );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true ); //获取的信息以文件流的形式返回
	curl_setopt( $ch, CURLOPT_AUTOREFERER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );   // # required for https urls
	curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
	curl_setopt( $ch, CURLOPT_MAXREDIRS, 10 );
	$content = curl_exec( $ch );
	$response = curl_getinfo( $ch );
	curl_close ( $ch );

	//目前先不使用这个
	//    if ($response['http_code'] == 301 || $response['http_code'] == 302)
	//    {
	//        ini_set("user_agent", "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1");
	//
	//        if ( $headers = get_headers($response['url']) )
	//        {
	//            foreach( $headers as $value )
	//            {
	//                if ( substr( strtolower($value), 0, 9 ) == "location:" )
	//                    return get_url( trim( substr( $value, 9, strlen($value) ) ) );
	//            }
	//        }
	//    }

	if (    ( preg_match("/>[[:space:]]+window\.location\.replace\('(.*)'\)/i", $content, $value) || preg_match("/>[[:space:]]+window\.location\=\"(.*)\"/i", $content, $value) ) &&
	$javascript_loop < 5
	)
	{
		return CUrl( $value[1], $javascript_loop+1 );
	}
	else
	{
		return array( $content, $response );
	}
}

/*
 *  debug test
 */
function ErrorDebug() {
	if(DEBUG){
		ini_set('display_errors',1);

		//set the options in an array
		$options = array(
          'snippet_num_lines' => 10,
          'background_text'  => 'PPL Error',
          'display_line_numbers' => true,
        'snippet_num_lines' => 10,
       'error_reporting_on' => E_ERROR | E_WARNING | E_PARSE
		);
		//require the script and run the function passing the options ar argument
		require(Core.'/php_error.php');
		\php_error\reportErrors( $options );
	}
}

/**
 * UTF-8 中文切字
 * @param    string    需要切分的字符串
 * @param    int    切分开始处
 * @param    int    切分的长度
 * @return    int    切分后的字符串
 */
function msubstr($str, $start, $length=NULL) {
	if (strlen($str) < $length) {
		return $str;
	}

	preg_match_all("/./u", $str, $ar);

	if (func_num_args() >= 3) {
		$end = func_get_arg(2);
		return join("", array_slice($ar[0], $start, $end)) . '...';
	} else {
		return join("", array_slice($ar[0], $start)) . '...';
	}
}

/**
 +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 +----------------------------------------------------------
 * @return string
 +----------------------------------------------------------
 */
function S($str, $start=0, $length, $dot = '', $charset="utf-8", $suffix=true) {
	/* if(function_exists("mb_substr"))
	 return mb_substr($str, $start, $length, $charset);
	 elseif(function_exists('iconv_substr'))
	 return iconv_substr($str,$start,$length,$charset); */

	if (mb_strlen($str, $charset) <= $length)
	return $str;

	$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
	$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
	$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
	$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
	preg_match_all($re[$charset], $str, $match);

	$slice = join("", array_slice($match[0], $start, $length));

	//if($suffix) return $slice;

	return $slice . $dot;
}

// 浏览器友好的变量输出
function D($var, $file=0, $line=0, $echo=true, $label=null, $strict=true) {

	$label = ($label === null) ? '' : rtrim($label) . ' ';
	if (!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var, true);
			$output = "<pre>" . $label . htmlspecialchars($output, ENT_QUOTES) . "</pre>";
		} else {
			$output = $label . print_r($var, true);
		}
	} else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if (!extension_loaded('xdebug')) {
			$output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
			$output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
		}
	}
	if ($echo) {
		echo($output)."<br />".$file ."<br />".$line .":" .__METHOD__ ;
		exit;
		//return null;
	}else
	return $output;
}

/**
 * ShowMsg
 *
 * @param string $msg
 * @param string $url
 * @param int $type
 */
function ShowMsg($msg, $url='', $limittime=0) {

	$htmlhead = "<html>\r\n<head>\r\n<title>系统提示</title>\r\n";
	$htmlhead .= "<meta http-equiv=\"Content-Type\" content=\"text/html;\" />\r\n";
	$htmlhead .= "<base target='_self'/>\r\n</head>\r\n";
	$htmlhead .= "<body leftmargin='0' topmargin='0'>\r\n<center>\r\n<script>\r\n";

	$htmlfoot = "</script>\r\n</center>\r\n</body>\r\n</html>\r\n";

	if ($limittime == 0)
	$litime = 0;
	else
	$litime = $limittime;

	if ($url == "-1") {
		if ($limittime == 0)
		$litime = 3000;
		$url = "javascript:history.go(-1);";
	}

	if ($url == "") {
		$msg = "<script>alert(\"" . str_replace("\"", "“", $msg) . "\");</script>";
	} else {
		$func = "var pgo=0;
                        function JumpUrl(){
                        if(pgo==0){ location='$url'; pgo=1; }
                        }\r\n";

		$rmsg = $func;
		$rmsg .= "document.write(\"<br/>";
		$rmsg .= "<div style='width:400px;padding-top:4px;height:24;font-size:10pt;border-left:1px solid #b9df92;border-top:1px solid #b9df92;border-right:1px solid #b9df92;background-color:#def5c2;'>提示信息：</div>\");\r\n";
		$rmsg .= "document.write(\"<div style='width:400px;height:100;font-size:10pt;border:1px solid #b9df92;background-color:#f9fcf3'><br/><br/>\");\r\n";
		$rmsg .= "document.write(\"" . str_replace("\"", "“", $msg) . "\");\r\n";
		$rmsg .= "document.write(\"";

		//if($onlymsg==0){
		if ($url != "javascript:;" && $url != "") {
			$rmsg .= "<br/><br/><a href='" . $url . "'>如果你的浏览器没反应，请点击这里...</a>";
		}
		$rmsg .= "<br/><br/></div>\");\r\n";
		if ($url != "javascript:;" && $url != "") {
			$rmsg .= "setTimeout('JumpUrl()',$litime);";
		}
		//}else{ $rmsg .= "<br/><br/></div>\");\r\n"; }
		$msg = $htmlhead . $rmsg . $htmlfoot;
	}

	echo $msg;
	exit();
}

/**
 *  insert_charset_header
 *
 *
 */
function insert_charset_header() {
	header('Content-Type: text/html; charset=UTF-8');
}
/**
 *  insert_json_header
 *
 *
 */
function insert_json_header() {
	header('Content-Type: application/json; charset=UTF-8');
}
/**
 * PostorGet
 * @author by vyouzhi
 * @param string $action
 * @return string
 *   extract(array_map('itrim',string));
 */
function PorG() {
	$request = array();
	if (isset($_REQUEST)) {
		$request = $_REQUEST;
		foreach ($request as $k=>$v) {
			if(is_array($v)){
				foreach ($v as $ks => $vs) {
					$ks = check($ks);
					$v[$ks] = check($vs);
				}
				$request[$k]=$v;
			}else
			//unset($request[$k]);
			$request[check($k)] = check($v);
		}


		return $request;
	}
}

function check($str) {
	$str = htmlspecialchars(stripslashes($str));
	$str = @mysql_escape_string($str);
	$str = htmldecode($str);
	return $str;
}

/**
 * @desc   过滤特殊字符  //   放在这儿好了
 * @access public
 * @param  string $str
 * @return string $str
 */
function htmldecode($str)
{
	if(empty($str)) return;
	if($str=="") return $str;

	$bugcode = array(
	//hex base64 ..
		"%3C","%3E","'","iframes","css","object",	
	//html
		"<br/>",
	//sql
		"select","join","union","where","insert","delete","update","like","drop","create","modify","rename","alter",
		"into","load_file","outfile",	
	// javascript
		"alert","<script>","script","</script>","prompt",	
	//any code
		"\0","%00","\r","<",">",'$','%','&lt;','&gt;',			
	);

	foreach ($bugcode as $value) {
		$str = str_ireplace($value, "", $str);
	}

	return trim($str);
}

/**
 *
 * 刷新当前页面 ...
 */
function flush_page() {
	echo "<script language=JavaScript> location.replace(location.href);</script>";
}
/**
 * display
 *
 */
function display($filename) {
	if (is_file(THEME . "/" . $filename . ".htm"))
	return  THEME . "/" . $filename . ".htm";
	elseif (is_file(THEME . "/" . $filename . ".html"))
	return  THEME . "/" . $filename . ".html";
	else {
		return false;
	}
}

function microtime_float() {
	list($usec, $sec) = explode(" ", microtime());
	return ((float) $usec + (float) $sec);
}

/**
 * MySQL 默认读写
 * @param string $rw  r w
 * @param string $key  如果 $key = "1" 并且 rw = "w" 的时候，代表有返回值
 * @param int $extime
 * @return array
 */
function GetPB($rw='r', $key='', $extime=0, $type=0) {
	$returnid = 0;

	$key == '' && $key = md5(time());
	$key == 1 && $returnid = 1;

	return array("rw" => $rw, "key" => $key, "extime" => $extime, "returnid" => $returnid, "type" => $type);
}

/**
 *
 *
 *
 */
function DebugSQL($st=0, $val='') {
	global $DEBUG_LOGFILE;

	if ($st == 0) {
		if (defined('DEBUG_SQL')) {
			$start_time = microtime_float();
			if (defined('DEBUG_LOG')) {

				$log = "xjc in line. " . __LINE__ . "\n";
				$log .= "xjc in func: " . $_SERVER[PHP_SELF] . "\n";
				$log .= "xjc is :<br />" . $val . "\n";
				$logfile = file_get_contents(Root . "/temp/logfile");
				file_put_contents(Root . "/temp/logfile", $logfile . $log);
			}
		}
	} else {

		if (defined('DEBUG_SQL')) {

			$end_time = microtime_float();
			$logfile = file_get_contents(Root . "/temp/logfile");
			$log .= "link sql use time: " . ($end_time - $start_time ) . "\n";
			file_put_contents(Root . "/temp/logfile", $logfile . $log);
		}
	}
}

/**
 * ckeditor 分页
 * @Param $content , type string
 * @Param $pageIndex, type int
 * @Param $url, type string;
 *
 * return array, array[0] is the content, array[1] is the index
 */
function getPageIndex($content = null, $pageIndex = 1, $url = '') {
	$result = "";
	$index = "";
	$resultArray = array();
	if ($content != null) {

		$mode = '#<div style="page-break-after: always;">(.*)<span style="display: none;">&nbsp;</span></div>#iUs';

		$arr = preg_split($mode, $content);

		$pageSum = count($arr);
		//set the contents
		if ($pageSum > 0) {
			$result = $arr[$pageIndex - 1];
		}

		//set the index
		if (count($arr) == 1) {
			$index = "共[1]页，第[1]页";
		} else {
			$index = "共[$pageSum]页,";
			for ($i = 0; $i < count($arr); $i++) {
				$index .= '[<a href="' . $url . '?pageIndex=' . ($i + 1) . '">' . ($i + 1) . '</a>]';
			}
		}
	}
	$resultArray[0] = $result;
	$resultArray[1] = $index;
	return $resultArray;
}

/**
 * 是否显示
 * @Param $status , type string
 * return string
 */
function displayShow($status) {

	if ($status == 'F')
	return '否';
	if ($status == 'A')
	return '是';
}

/**
 * 是否显示
 * @Param $status , type string
 * return string
 */
function is_hide($status) {
	if ($status == 0)
	return false;
	elseif ($status == 1)
	return true;
}

function putErrorPNG() {
	echo '<img src="' . MPIC_1 . '/static/images/common/error.png" title="sorry,加载失败" />';
}

function GetIP() {
	$unknown = 'unknown';
	if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'],$unknown)){
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	}elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'],$unknown)){
		$ip = $_SERVER['REMOTE_ADDR'];
	} /*$ip = preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : $unknown; */
	if(false !== strpos($ip, ','))
	$ip = @reset(explode(',', $ip));

	return $ip;
}

//去除链接
function strip_href($cont) {
	return preg_replace('/\<a(.*)href(.*)\<\/a\>/', '', $cont);
}

/* fck分页
 <div style="page-break-after: always;">
 <span style="display: none;">&nbsp;</span></div>
 */

function fck_page($cont) {
	//D($cont);
	$cont = htmlspecialchars_decode($cont);
	$pattern = "%<div style=\"page-break-after: always;?\">\\s*<span style=\"display: none;?\">&nbsp;</span>\\s*</div>%iUs";
	$strSplit = preg_split($pattern, $cont, -1, PREG_SPLIT_NO_EMPTY);

	return $strSplit;
}

/**
 * 整数安全处理，仅返回大于等于0的整数
 * */
function numeric(& $variint) { //判断是否为数字
	if (!isset($variint))
	return 0;
	if (!is_numeric($variint))
	return 0;

	//首字符0处理
	$str_len = strlen($variint);
	for ($i = 0; $i < $str_len; $i++) {
		if ($variint [$i] != '0')
		break;
	}
	if ($i > 0 && $variint > 0) {
		$variint = substr($variint, $i, $str_len);
		$str_len = strlen($variint);
	}

	//数字安全处理
	if ($str_len > 0) {
		if (!preg_match("/^[0-9]+$/", $variint)) {
			return 0;
		} else {
			$variint = substr($variint, 0, 10);
			//兼容MYSQL中INT无符号最大值4294967295
			$variint = ($variint > 4294967295) ? 4294967295 : $variint;
			return $variint;
		}
	} else {
		return 0;
	}
}

/* * 格式化输出信息* */
if (!function_exists('prf')) {

	function prf($data) {
		echo '<br/><pre>';
		print_r($data);
		echo '</pre><br/>';
		echo '<hr color="';
		if (function_exists('rndcolor')) {
			echo rndcolor();
		} else {
			echo '#ccc';
		}
		echo '"/>';
	}

}
/* * 颜色随机器* */
if (!function_exists('rndcolor')) {

	function rndcolor() {
		$str = '#';
		$arr = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
		for ($i = 0; $i < 6; $i++) {
			$str .= $arr[array_rand($arr)];
		}
		return $str;
	}

}

/**
 * 转化 \ 为 /
 *
 * @param	string	$path	路径
 * @return	string	路径
 */
function dir_path($path) {
	$path = str_replace('\\', '/', $path);
	if (substr($path, -1) != '/')
	$path = $path . '/';
	return $path;
}


function test($msg) {
	if (isset($_REQUEST['test']) && $_REQUEST['test'] == true)
	print_r($msg);
}



function U($root, $date, $id){
	return $root.date('Ymd', intval($date)).'/'.$id.'.html';
}

/*
 * IO statrt
 */

/**
 *
 * flush LIBS ...
 */
function flush_routing() {
	@unlink(LIBS);
}

function routing($lib){
	if(!is_file(LIBS)) CacheLibs();
	require(LIBS);
	$_libs = unserialize($_libs);

	if(is_file($_libs[$lib])){
		require_once($_libs[$lib]);
	}else{
		$one_file =  dirname(dirname(dirname(__FILE__)))."/".$_libs[$lib];
		if(is_file($one_file))
		require_once($one_file);
	}

}

function import($filename, $ext='.php') {
	$filename.=$ext;
	if (!file_exists_case(LIB.$filename))
	return NULL;

	require_cache(LIB.$filename);
}

function data_writable() {
	if ( !is_writable ( Data )) {
		die('The '.Data.' is not writable') ;
	}
}

/**
 * @name getSeg
 * @package win return \ , linux return /
 * @return String
 */
function getSeg() {
	$seg = "/";
	if(substr(PHP_OS,0,3) == 'WIN'){
		$seg = "\\";
	}
	return $seg;
}

/**
 * 得到某个目录下所有文件夹和文件
 * string $dir 目录路径
 * return array('files'=>array(...),'dirs'=>array(...))
 * @package 还不够完善
 * @see ACLRole
 */
function find_all_files($dir)
{
	$seg = getSeg();
	$lib_name  = function( $name )
	{
		return basename($name, ".php");
	};

	$root = scandir($dir);
	if($root==false)return false;
	foreach($root as $value)
	{
		if($value === '.' || $value === '..') {continue;}
		if(is_file("$dir$seg$value") ) {

			$result[$lib_name($value)]="$dir$seg$value";
			continue;
		}
		$sub_root = find_all_files("$dir$seg$value");
		if($sub_root == false)continue;
		foreach($sub_root as $value)
		{
			if(substr($value,-4) == ".php"){
				if($lib_name($value) == "index"){
					$result[basename(dirname($value))."_".$lib_name($value)]=$value;
				}else
				$result[$lib_name($value)]=$value;
			}
		}
	}
	return $result;
}

/*
 * 生成 Lib 模块
 */
function CacheLibs(){
	if(is_file(LIBS))return;

	$result = find_all_files(Lib);

	$libs_serialize = serialize($result);

	$php_start = "<?php if (!defined('M5CPL'))exit; ";
	dir_create(LIBDIR);
	file_put_contents(LIBS, $php_start." \$_libs='".$libs_serialize."'; return \$_libs;");
}

// 优化的require_once
function require_cache($filename) {
	static $files = array();
	if (!isset($files[$filename])) {
		if (file_exists_case($filename)) {
			require $filename;
			$files[$filename] = true;
		}
	}
	return $files[$filename];
}

// 区分大小写的文件存在判断
function file_exists_case($filename) {
	if (is_file($filename)) {

		$is_win = strstr(PHP_OS, 'WIN') ? true : false;
		if ($is_win) {
			if (basename(realpath($filename)) != basename($filename))
			return false;
		}
		return true;
	}
	return false;
}


/**
 * 创建目录
 *
 * @param	string	$path	路径
 * @param	string	$mode	属性
 * @return	string	如果已经存在则返回true，否则为flase
 */
function dir_create($path, $mode = 0777) {
	if (is_dir($path))
	return TRUE;
	$ftp_enable = 0;
	$path = dir_path($path);
	$temp = explode('/', $path);
	$cur_dir = '';
	$max = count($temp) - 1;
	for ($i = 0; $i < $max; $i++) {
		$cur_dir .= $temp[$i] . '/';
		if (@is_dir($cur_dir))
		continue;
		@mkdir($cur_dir, 0777, true);
		@chmod($cur_dir, 0777);
	}
	return is_dir($path);
}

/**
 * 拷贝目录及下面所有文件
 *
 * @param	string	$fromdir	原路径
 * @param	string	$todir		目标路径
 * @return	string	如果目标路径不存在则返回false，否则为true
 */
function dir_copy($fromdir, $todir) {
	$fromdir = dir_path($fromdir);
	$todir = dir_path($todir);
	if (!is_dir($fromdir))
	return FALSE;
	if (!is_dir($todir))
	dir_create($todir);
	$list = glob($fromdir . '*');
	if (!empty($list)) {
		foreach ($list as $v) {
			$path = $todir . basename($v);
			if (is_dir($v)) {
				dir_copy($v, $path);
			} else {
				copy($v, $path);
				@chmod($path, 0777);
			}
		}
	}
	return TRUE;
}

/**
 * 转换目录下面的所有文件编码格式
 *
 * @param	string	$in_charset		原字符集
 * @param	string	$out_charset	目标字符集
 * @param	string	$dir			目录地址
 * @param	string	$fileexts		转换的文件格式
 * @return	string	如果原字符集和目标字符集相同则返回false，否则为true
 */
function dir_iconv($in_charset, $out_charset, $dir, $fileexts = 'php|html|htm|shtml|shtm|js|txt|xml') {
	if ($in_charset == $out_charset)
	return false;
	$list = dir_list($dir);
	foreach ($list as $v) {
		if (pathinfo($v, PATHINFO_EXTENSION) == $fileexts && is_file($v)) {
			file_put_contents($v, iconv($in_charset, $out_charset, file_get_contents($v)));
		}
	}
	return true;
}

/**
 * 设置目录下面的所有文件的访问和修改时间
 *
 * @param	string	$path		路径
 * @param	int		$mtime		修改时间
 * @param	int		$atime		访问时间
 * @return	array	不是目录时返回false，否则返回 true
 */
function dir_touch($path, $mtime = TIME, $atime = TIME) {
	if (!is_dir($path))
	return false;
	$path = dir_path($path);
	if (!is_dir($path))
	touch($path, $mtime, $atime);
	$files = glob($path . '*');
	foreach ($files as $v) {
		is_dir($v) ? dir_touch($v, $mtime, $atime) : touch($v, $mtime, $atime);
	}
	return true;
}

/**
 * 目录列表
 *
 * @param	string	$dir		路径
 * @param	int		$parentid	父id
 * @param	array	$dirs		传入的目录
 * @return	array	返回目录列表
 */
function dir_tree($dir, $parentid = 0, $dirs = array()) {
	global $id;
	if ($parentid == 0)
	$id = 0;
	$list = glob($dir . '*');
	foreach ($list as $v) {
		if (is_dir($v)) {
			$id++;
			$dirs[$id] = array('id' => $id, 'parentid' => $parentid, 'name' => basename($v), 'dir' => $v . '/');
			$dirs = dir_tree($v . '/', $id, $dirs);
		}
	}
	return $dirs;
}

/**
 * 删除目录及目录下面的所有文件
 *
 * @param	string	$dir		路径
 * @return	bool	如果成功则返回 TRUE，失败则返回 FALSE
 */
function dir_delete($dir) {
	$dir = dir_path($dir);
	if (!is_dir($dir))
	return FALSE;
	$list = glob($dir . '*');
	foreach ($list as $v) {
		is_dir($v) ? dir_delete($v) : @unlink($v);
	}
	return @rmdir($dir);
}
/*
 * IO end
 */


/**
 * @name PermissionMap
 * @package 自动装载 后台 lib
 */
function PermissionMap() {
	global  $routeMap;

	if(!is_file(LIBS)) CacheLibs();
	include(LIBS);
	$m_lib = unserialize($_libs);
	$admin_map_urls = unserialize(ADMIN_MAP_URL);

	foreach ($m_lib as $k=>$v) {
		$amu = basename(dirname($v));
		$lib =  substr(basename($v),0,-4);

		if(!preg_match("/index/i", $lib)){

			if(in_array($amu, $admin_map_urls)){
				$routeMap[] = array('method'=>'GET|POST',
			'uri'=>'/'.$lib.'/['.ADMIN_ACL_ACTION.':action]',  /* ADMIN_ACL_ACTION 定义基本权限 */
			'params'=>'usersController#doAction', 
			'module'=>$lib); 
			}
		}
	}
}

/**
 * @name get_article_url
 * @$article_id 文章ID
 * @time 文章的发表时间 格式为20140909
 * @package 根据文章的类别ID 获取文章的连接地址
 */
if(!function_exists("get_article_url")){
	function get_article_url($cid = '' ,$article_id = '' ,$time = ''  ){
		global $CategoryIdName ;
		$url = '' ;
		if(!$cid || !$article_id ){
			return $url ;
		}
		if(!$time){
			$time = date("Ymd",time());
		}
		$typname_id_array = array();
		$is_category = false ; //是否在配置文件定义了分类
		//echo Etc ;exit();
		include_once Etc.'/Category.php'; //加载配置文件
		
		
		$is_category = (isset($CategoryIdName[$cid ]))?true:false ;
		if($is_category){
			//如果定义了分类
			$url = WEB."/".$CategoryIdName[$cid]."/".$time."-".$article_id.".html" ;
		}else{
			$url = WEB."/article/".$article_id.".html";
		}
		return $url ;
	}
}

/**
 * @name get_category_url
 * @$cid 类别ID
 * @package 根据文章的类别ID 获取类别的连接地址
 */

if(!function_exists("get_category_url")){
	//include_once Etc.'/Category.php'; //加载配置文件
	function get_category_url($cid = '' ){
		global $CategoryIdName ;
		$url = '' ;
		if(!$cid ){
			return $url ;
		}
		$typname_id_array = array();
		$is_category = false ; //是否在配置文件定义了分类
		$is_category = (array_key_exists($cid, $CategoryIdName))?true:false ;
		if($is_category){
			//如果定义了分类
			$url = WEB."/".$CategoryIdName[$cid].".html" ;
		}else{
			$url = WEB."/list/{$cid}.html" ;
		}
		return $url ;
	}
}

/**
 * @name get_cate_url
 * @$cid 类别ID
 * @package 根据文章的类别ID 获取类别的英文部分url
 */

if(!function_exists("get_cate_url")){
	function get_cate_url($cid = '' ){
		global $CategoryNameId ;
		$url = '' ;
		if(!$cid ){
			return $url ;
		}
		$typname_id_array = array();
		$is_category = false ; //是否在配置文件定义了分类
		include_once Etc.'/Category.php'; //加载配置文件
		$typname_id_array = array_flip($CategoryNameId);
		$is_category = (array_key_exists($cid, $typname_id_array))?true:false ;
		if($is_category){
			//如果定义了分类
			$url = $typname_id_array[$cid] ;
		}else{
			$url = "search" ;
		}
		return $url ;
	}
}
