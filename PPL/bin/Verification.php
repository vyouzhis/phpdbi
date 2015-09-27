<?php 
/**
*
* @Copyright (C), 2012, Aiden.
* @Name: Filter.php
* @Author: Aiden
* @Version: Beta 1.0
* @Date: 2012-2-3
* @Description:  过滤器 可以验证提交数据的安全性
* @Class List:
*  	1. Filter
*  @Function List:
*   1.
*  @History:
*      <author>       <time>            <version >        <desc>
*        Aiden       2012-2-3           Beta 1.0           第一次建立该文件
*
*/
  
/**
* @desc  验证类，可以验证提交数据的安全性
* @package  Commons
* @since 2012-2-3 上午06:59:48
* @final  2012-2-3 上午06:59:48
*/
class Verification
{
	/**
	 * @desc 检测网址正则
	 * @var  string
	 * @access public
	 */
	const PREG_URL = '/^http:\/\/[_a-zA-Z0-9-]+(.[_a-zA-Z0-9-]+)*$/';
	
	/**
	 * @desc 检测电子邮箱正则
	 * @var  string
	 * @access public
	 */	
	const PREG_EMAIL = '/^[\w\-\.]+@[\w\-\.]+(\.\w+)+$/';
	
	/**
	 * @desc 检测手机号码正则
	 * @var  string
	 * @access public
	 */	
	const PREG_MOBILE = '/^(13|15|18|14|17)[0-9]{9}$/';
	
	/**
	 * @desc 检测电话号码正则
	 * @var  string
	 * @access public
	 */	
	const PREG_PHONE = '/^[0-9-]{6,13}$/';
	
	/**
	 * @desc 检测邮政编码正则
	 * @var  string
	 * @access public
	 */	
	const PREG_ZIPCODE = '/^[0-9]{6}$/';
	
	/**
	 * @desc 检测是否为中文正则
	 * @var  string
	 * @access public
	 */		
	const PREG_CHINESE = '/[^\x80-\xff]/';
	
	/**
	 * @desc 检测是否为金额正则
	 * @var  string
	 * @access public
	 */	
	const PREG_MONEY = '/^([0-9]{1,}|[0-9]{1,}\.[0-9]{1,2})$/';
	
	/**
	 * @desc 整数正则  
	 * @var  string
	 * @access  const
	 */
	const PREG_INT = '/^\s*\d+\s*$/';
	
    /**
	 * @desc 判断是否为网址
	 * @access public
	 * @param string $url 网址
	 * @return bool
	 */
	 public static  function isUrl($url)
	 {
	 	 return self::_checkPreg(self::PREG_URL, $url);	 
	 } 
  	
	 
	 /**
	  * @desc 判断是否为电子邮件
	  * @access public
	  * @param string $email 电子邮件
	  * @return bool
	  * 
	  */
	 public static  function isEmail($email)
	 {
	 	 return self::_checkPreg(self::PREG_EMAIL, $email);
	 }
	 

  	 /**
	  * @desc 判断是否为手机号码
	  * @access public
	  * @param int $email 手机号码邮件
	  * @return bool
	  */
	 public static  function isMobile($mobile)
	 {
	 	 return self::_checkPreg(self::PREG_MOBILE, $mobile);
	 }	 
	 
	 
  	 /**
	  * @desc 判断是否为电话号码
	  * @access public
	  * @param int $email 电话号码
	  * @return bool
	  */
	 public static  function isPhone($phone)
	 {
	 	 return self::_checkPreg(self::PREG_PHONE, $phone);
	 }	

	 
  	 /**
	  * @desc 判断是否为邮政编码
	  * @access public
	  * @param int $email 邮政编码
	  * @return bool
	  * 
	  */	 
	 public static function isZipcode($code)
	 {
	 	 return self::_checkPreg(self::PREG_ZIPCODE, $code);
	 }
	 
	 /**
	  * @desc 判断是否为中文汉字
	  * @access public 
	  * @param string $string 检测字符串
	  * @return bool
	  */
	 public static function isChinese($str)
	 {
	 	 return !self::_checkPreg(self::PREG_CHINESE, $str); 
	 }
	 
	 /**
	  * @desc 判断是否为金额
	  * @access public
	  * @param string $money 金额
	  * @return bool
	  */
	 public static function isMoney($money)
	 {
	     return self::_checkPreg(self::PREG_MONEY, $money);	
	 }
	 
	 /**
	  * @desc 判断 是否为IP地址
	  * @access public
	  * @param string $ip IP地址
	  * @return bool
	  */
	 public static function isIp($ip)
	 {
	 	 return (bool)ip2long($ip);
	 }
	 
	 /**
	  * @desc 判断是否为正整数 
	  * @access public
	  * @param string $num 正整数字符串（或int $sum）
	  * @return bool
	  */
	 public static function isInt($num)
	 {
	 	 return self::_checkPreg(self::PREG_INT, $num);	
	 }
	 
	 /**
	  * @desc 检测是否符合正则
	  * @access public
	  * @param string $preg 正则表达式
	  * @param string $string 检测的字符串
	  * @return bool
	  */	 
	 private static function _checkPreg($preg, $string)
	 {
	     if (preg_match($preg, $string))
	     {
	     	return true; 
	     }
	     
	     return false;
	 }
	 

}


?>