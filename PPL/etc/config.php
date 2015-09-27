<?php
/**
 * @exception 配置文件
 * @copyright by vyouzhi on 20080809
 * @name config.php
 */
/**
* @ignore
*/
if (!defined('M5CPL'))exit;

//设置默认时区为中国
date_default_timezone_set('PRC');
// 定义数据库
define('DBS', "dbs");

//// 定义表
define('DB_PRE', "d_");  //

// 定义路由根 默认为空
define("ROOT","");

//序列化数据目录
define("LIBDIR", Data."/libs");
//序列化模块
define("LIBS", LIBDIR."/libs_serialize.php");
//序列化URI
define("DEFURL", LIBDIR."/urls_serialize.php");

//域名

define("DOMAIN", "dbi.com");


/**
 * 设置全局域SESSION
 */
define("SESSION_DOMAIN",DOMAIN);

define("WEB", "http://www.".DOMAIN);  //首页
define("WEB_JS", "http://www.".DOMAIN."/static/js/");  //网站 js 
define("WEB_CSS", "http://www.".DOMAIN."/static/css/"); //网站 css
define("WEB_IMG", "http://www.".DOMAIN."/static/images/");// 网站 img
define("WEB_FONTS", "http://www.".DOMAIN."/static/fonts/");// 网站 img  
define("USERS_LOGIN", "http://i.".DOMAIN);  //用户中心 首页

define("WEB_UPLOAD_PIC","http://st.yx.com/"); //配置后台上传的图片域名

define("USER_CENTER_DOMAIN","http://i.elon.com/"); //配置会员中心的域名

/*
 * default themes
 */
define("THEME", Root."/themes/default/temple");

/**
 *  默认头文件模块
 */
define("WEB_HEADER", "web_header");

/**
 * 默认脚文件模块
 */
define("WEB_FOOT", "web_foot");


/**
 * 定义模块式为 BaseTheme 公共模块使用
 */
$BASETHEME_COMMON_ACTION = true;

/**
 * 公共模版默认路径
 */
define("COMMON_TEMPLE", "common/");

/*
 * 默认语言
 */
define("LANG", Lang."/zh_cn/"); 
define("LANG_COMMON", "common"); //公共语言，包括标题之类的

/**
 * 前台COOKIE UID
 */
define("WEB_COOKIE_UID", "__uid");

/**
 * 前台COOKIE name
 */
define("WEB_COOKIE_NAME", "__name");

/**
 *  盐粒
 */
define("SALT", "SALT");
/**
 * mongodb host and name
 * 
 */

$__mongodb = array("host"=>"192.168.1.22", "port"=>27017);

/**
 * 
 * 默认分页数
 * @var int
 */
define("LIMITS", 5);


/**
 * 
 * crm url
 * @var int
 */
define("CRMURL", "www.s.com");

/* route map */
include 'Map.php';
?>
