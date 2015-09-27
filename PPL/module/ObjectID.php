<?php
if (!defined('M5CPL'))
exit;

/**
 * 
 * hash("adler32", ip2long($ip).mt_rand ()).dechex(time()).   dechex(time()) . dechex(time()).count
 * @author vyouzhi
 *
 */
class ObjectID {
	
	public function GetObjectID() {
		$ip = GetIP();
		return hash("adler32", ip2long($ip).mt_rand ());
	}
	
	public function getTimeHex() {
		return dechex(time());
	}
	
	public function cookie() {
		setcookie ( "TestCookie" ,  $value ,  time ()+ 3600 ,  "/~rasmus/" ,  "example.com" ,  1 );
	}
}