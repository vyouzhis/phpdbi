<?php
if (!defined('M5CPL'))
exit;

/**
 * 
 * @package 处理 json 结构的模块，ajax 提交 get or post 都以 json 形式提交
 * @author vyouzhi
 *
 */
class BaseJson extends BaseTheme{
	private $Session;
	
	public function __construct() { 
        parent::__construct(__CLASS__); 
        $this->ajax=1;    	    	
    	insert_json_header();
    	$this->Session = new SC();
		$this->Session->StartSession();
    } 
    
	/**
	 * @name SetSessionInfo
	 * @param array or string $value
	 * @package 设置 session
	 */
	public function SetSessionInfo($value) {
		$this->Session->SetSession(WEB_SESSION_VERIFY, $value);
	}
	
	/**
	 * @name GetSessionInfo
	 * @package 设置 session
	 * @return array
	 */
	public function GetSessionInfo() {
		return $this->Session->GetSession(WEB_SESSION_VERIFY);
	}
	
	/**
	 * @name IsUser
	 * @package 发送验证码之前，验证手机号码是否存在
	 * @return array
	 */
	public function IsUser($phone) {
	/*查询是否存在此账户*/
		$where ="where login_md5='".md5($phone)."'";
		$format = "select user_id from ".DB_PRE."user_main %s limit 1 ";
		$sql = sprintf($format,$where);
		
		$res = parent::FetchOne($sql);
		if(is_array($res) ){
			return true;
		}
		else return false;
	}
}
 