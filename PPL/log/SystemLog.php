<?php
if (!defined('M5CPL'))
exit;


/**
 *
 * 类用在 多语言 类上面 ...
 * @author jsb-015
 *
 */
class SystemLog extends DBSQL{
	private $_log;

	public function __construct() {
		$this->_log="";
	}
	
	public function saveLog($uid, $action) {
		global $router, $match;
		
		
		$format = "INSERT INTO `".DB_PRE."log` (`lid`, `uid`, `action`, `ip`, `ctime`)";
		$format .=" VALUES ('%s', '%d', '%d', '%s', '%d');";
		
		$sql = sprintf($format, $match['action'], $uid, $action, GetIP(), time());
		
		parent::Insert($sql);
	}
}