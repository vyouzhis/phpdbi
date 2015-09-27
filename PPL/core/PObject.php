<?php
if (!defined('M5CPL'))
exit;

class PObject extends DBSQL{

	function __construct($stdClass=null)
	{
		parent::__construct();

	}
	/**
	 * @name u_getUid
	 * @package 从 cookie 中获得 uid, 如果曾经登录过
	 * @return Int
	 */
	public function u_getUid() {
		$uid = $_COOKIE[WEB_COOKIE_UID];
		return $uid;
	}

	/**
	 * @name u_getName
	 * @package 从 cookie 中获得 name, 如果曾经登录过
	 * @return String
	 */
	public function u_getName() {
		$name = $_COOKIE[WEB_COOKIE_NAME];
		return $name;
	}
	
	/**
	 * @name u_getSex
	 * @package 从 cookie 中获得 性别, 如果曾经登录过
	 * @return String
	 */
	public function u_getSex() {
		$sex = $_COOKIE[WEB_COOKIE_SEX];
		return $sex;
	}
}


