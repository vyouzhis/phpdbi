<?php
/**
 * @exception 引入项目文件
 * @copyright by vyouzhi and aaron on 20080809
 * @name APP_DB
 * @version 0.01
 */

ini_set("suhosin.session.cryptdocroot", "Off"); //跨域的问题
ini_set("suhosin.cookie.cryptdocroot", "Off");
define("M5CPL", "d8a3d75ac2f6b243d869f1f2927bbcb6"); //定义全局php 文件exit key

define("APP_DB",__FILE__);
define("Root",dirname(APP_DB));
define("PPL", Root."/PPL");
define("Bin",PPL."/bin");
define("Dev",PPL."/dev");
define("Etc",PPL."/etc");
define("Core",PPL."/core");
define("Module",PPL."/module");
define("Log",PPL."/log");
define("PClass", PPL."/class");

define("Lib", "./lib");  // 存放模块
define("Data", Root."/data");  // 存放临时及附件文件
define("Lang", Root."/lang");  // 存放语言文件


require_once(Etc."/config.php");  //配置文件
require_once(Bin."/function.php"); //常用函数，以后有常的也加在这儿
require_once(Bin."/Page.php"); //加载分页
require_once(Dev."/ADB.php"); // 数据库连接 代码 
require_once(Bin.'/SC.php');  //Session class   
require_once(Core."/PObject.php"); //  基类
require_once(PClass.'/BaseLang.php');  //多语言
require_once(PClass.'/BaseTheme.php'); //模版化
require_once(PClass.'/BaseModule.php'); //模块功能
require_once(PClass.'/BaseJson.php'); //模块功能



require_once(Core.'/Router.php');  // 路由

define("DEBUG", 1);
//error_reporting(0);
// errot debug
ErrorDebug();

// data 目录是否可写
data_writable();

//强制为 UTF8
insert_charset_header();


?>
