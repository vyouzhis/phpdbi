<?php
/**
 * @author www.klfy.org
 * @copyright 2011
 * @filename function.class.php
 */

@header("Content-Type: text/html; charset=UTF-8");
//if (!defined('VALIDREQUEST')) die ('Access Denied.');
class information
{
 //系统判断语言
 function get_client_oslang()
 {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,4);
  if (preg_match("/zh-c/i",$lang))
  {
   $oslang = "简体中文";
  }
  elseif (preg_match("/zh/i",$lang))
  {
   $oslang = "繁體中文";
  }
  elseif (preg_match("/en/i",$lang))
  {
   $oslang = "English";
  }
  elseif (preg_match("/fr/i",$lang))
  {
   $oslang = "French";
  }
  elseif (preg_match("/de/i",$lang))
  {
   $oslang = "German";
  }
  elseif (preg_match("/jp/i",$lang))
  {
   $oslang = "Japanese";
  }
  elseif (preg_match("/ko/i",$lang))
  {
   $oslang = "Korean";
  }
  elseif (preg_match("/es/i",$lang))
  {
   $oslang = "Spanish";
  }
  elseif (preg_match("/sv/i",$lang))
  {
   $oslang = "Swedish";
  }
  else
  {
   $oslang = "Other";
  }
  return $oslang ;
 }

 //判断当前IP
 function get_client_ip()
 {
  if ($_SERVER['REMOTE_ADDR'])
  {
   $cip = $_SERVER['REMOTE_ADDR'];
  }
  elseif (getenv("REMOTE_ADDR"))
  {
   $cip = getenv("REMOTE_ADDR");
  }
  elseif (getenv( "HTTP_CLIENT_IP"))
  {
   $cip = getenv("HTTP_CLIENT_IP");
  }
  else
  {
   $cip = "unknown";
  }
  return $cip ;
 }

 //判断当前操作系统
 function get_client_os()
 {
  $agent = $_SERVER['HTTP_USER_AGENT'];
  $os = false;
  if (eregi('win',$agent) && strpos($agent,'95'))
  {
   $os = 'Windows 95';
  }
  elseif (eregi('win 9x',$agent) && strpos($agent,'4.90'))
  {
   $os = 'Windows ME';
  }
  elseif (eregi('win',$agent) && ereg('98',$agent))
  {
   $os = 'Windows 98';
  }
  elseif (eregi('win',$agent) && eregi('nt 6.1',$agent))
  {
   $os = 'Windows 7';
  }
  elseif (eregi('win',$agent) && eregi('nt 5.1',$agent))
  {
   $os = 'Windows XP';
  }
  elseif (eregi('win',$agent) && eregi('nt 5',$agent))
  {
   $os = 'Windows 2000';
  }
  elseif (eregi('win',$agent) && eregi('nt',$agent))
  {
   $os = 'Windows NT';
  }
  elseif (eregi('win',$agent) && ereg('32',$agent))
  {
   $os = 'Windows 32';
  }
  elseif (eregi('linux',$agent))
  {
   $os = 'Linux';
  }
  elseif (eregi('unix',$agent))
  {
   $os = 'Unix';
  }
  elseif (eregi('sun',$agent) && eregi('os',$agent))
  {
   $os = 'SunOS';
  }
  elseif (eregi('ibm',$agent) && eregi('os',$agent))
  {
   $os = 'IBM OS/2';
  }
  elseif (eregi('Mac',$agent) && eregi('PC',$agent))
  {
   $os = 'Macintosh';
  }
  elseif (eregi('PowerPC',$agent))
  {
   $os = 'PowerPC';
  }
  elseif (eregi('AIX',$agent))
  {
   $os = 'AIX';
  }
  elseif (eregi('HPUX',$agent))
  {
   $os = 'HPUX';
  }
  elseif (eregi('NetBSD',$agent))
  {
   $os = 'NetBSD';
  }
  elseif (eregi('BSD',$agent))
  {
   $os = 'BSD';
  }
  elseif ( ereg( 'OSF1',$agent))
  {
   $os = 'OSF1';
  }
  elseif ( ereg( 'IRIX',$agent))
  {
   $os = 'IRIX';
  }
  elseif (eregi('FreeBSD',$agent))
  {
   $os = 'FreeBSD';
  }
  elseif (eregi('teleport',$agent))
  {
   $os = 'teleport';
  }
  elseif (eregi('flashget',$agent))
  {
   $os = 'flashget';
  }
  elseif (eregi('webzip',$agent))
  {
   $os = 'webzip';
  }
  elseif (eregi('offline',$agent))
  {
   $os = 'offline';
  }
  else
  {
   $os = 'Unknown';
  }
  return $os ;
 }

 //判断当前浏览器类型
 function get_client_browser()
 {
  if (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 9.0"))
  {
   $browser = "Internet Explorer 9.0";
  }
  if (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 8.0"))
  {
   $browser = "Internet Explorer 8.0";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 7.0"))
  {
   $browser = "Internet Explorer 7.0";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"MSIE 6.0"))
  {
   $browser = "Internet Explorer 6.0";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/4"))
  {
   $browser = "Firefox 4";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/3"))
  {
   $browser = "Firefox 3";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Firefox/2"))
  {
   $browser = "Firefox 2";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))
  {
   $browser = "Google Chrome";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Safari"))
  {
   $browser = "Safari";
  }
  elseif (strpos($_SERVER["HTTP_USER_AGENT"],"Opera"))
  {
   $browser = "Opera";
  }
  else
  {
   $browser = $_SERVER["HTTP_USER_AGENT"] ;
  }
  return $browser ;
 }

}
?>