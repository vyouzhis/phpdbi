<?php
if (!defined('M5CPL')){exit;}

/**
 * 
 * 登录或登出用的类 ...
 * @author jsb-015
 * @see ADMIN_SESSION_KEY 
 * array("uid"=>"value 用户ID",
 * 		 "mc"=>"value 唯一检查",
 * 		 "lib 各功能模块 "=>array(
 * 		 array("lib name"=>"总菜单1",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 * 	     array("lib name"=>"总菜单2",array("lib1"=>array("read","write"),"lib2"=>array("read"))),
 *      ));
 */

 
class ACLInit extends DBSQL {
	private $Session;
		
	public function __construct() {
		$this->Session = new SC();
		$this->Session->StartSession();
		
		parent::__construct();		
	}
	
	/**
	 * @name acl_getUid
	 * @package get user uid
	 * @return 0 or uid
	 */
	public function acl_getUid() {
		
		$res = $this->Session->GetSession(ADMIN_SESSION_KEY);
		if(empty($res)) return 0;
		return $res['uid'];
	}
	/**
	 * @name acl_getNickName
	 * @package get user nickname
	 * @return 0 or string
	 */
	public function acl_getNickName() {
		$res = $this->Session->GetSession(ADMIN_SESSION_KEY);
		if(empty($res)) return 0;
		return $res['nickname'];
	}
	
	public function acl_getName() {
		$res = $this->Session->GetSession(ADMIN_SESSION_KEY);
		if(empty($res)) return 0;
		return $res['name'];
	}
	
	/**
	 * @name acl_getSessionInfo
	 * @package get user all session info
	 * @return null or array
	 */
	public function acl_getSessionInfo() {
		$res = $this->Session->GetSession(ADMIN_SESSION_KEY);
		return $res;
	}
	
	/**
	 * @name acl_setSessionInfo
	 * @param String $ses 
	 * @package set sessinfo in ADMIN_SESSION_KEY
	 */
	public function acl_setSessionInfo($ses) {
		$this->Session->SetSession(ADMIN_SESSION_KEY, $ses);
	}
	
	/**
	 * @name acl_getCM
	 * @package get cm value
	 * @return null or String
	 */
	public function acl_getCM() {
		$res = $this->Session->GetSession(ADMIN_SESSION_KEY);
		if(empty($res)) return null;
		return $res['cm'];
	}

	/**
	 * @name acl_getSalt
	 * @package use login,pls use the salt md5(salt+passwd),and only post the md5 to server ...
	 * @return Salt String
	 */
	public function acl_getSalt() {
		// put the salt in login page, js_md5 will md5(salt+passwd), and post the result to server, no the plaintext
		// because only one will sonrt the network, only catch the md5
		// but we will use https 
		$salt = md5(time());
		$this->Session->SetSession(SALT, $salt);
		return $salt;		
	}
	
	/**
	 * @name acl_checkSalt	 
	 * @param String $salt
	 * @package check salt 
	 * @return true or false
	 */
	public function acl_checkSalt($salt) {
		$sess_salt = $this->Session->GetSession(SALT);
		
		if($sess_salt == $salt){	
			//使用之后就去掉
			$this->Session->SetSession(SALT, md5(rand(1, 999999)));		
			return true;	
		}
		else return false;
	}
	
	/**
	 *
	 * @name acl_Login	 
	 * @param $name
	 * @param $passwd
	 * @package $sess_salt
	 * @package user login action
	 * @return  0 or -1 name error, -2 passwd error
	 */
	function acl_Login($name, $passwd, $sess_salt) {
		
			
		$format = "select uid, cm, passwd,nickname  from ".DB_PRE_CORE."user_info WHERE types=%d and  name='%s' limit 1 ";
		$sql = sprintf($format, DB_TYPE_DIFFERENCE, $name);
		
		$res = parent::FetchOne($sql);
		
		if(is_array($res) ){
			// use the salt in here
			//echo $passwd."--".md5($res['passwd'].$sess_salt);
			
			if($passwd != md5($res['passwd'].$sess_salt)) return -2;
								
			$cm = md5(time());
			$format = "UPDATE ".DB_PRE_CORE."user_info SET `cm` = '%s', `ltime`=%d WHERE `types`=%d and `uid`='%d';";
			$sql = sprintf($format,  $cm,  time(), DB_TYPE_DIFFERENCE, $res['uid']);
			//die($sql);
			parent::Update($sql);
			
			$ses['cm'] = $cm;									
			$ses["uid"] = $res['uid'];
			$ses['nickname'] = $res['nickname'];
			$ses['name'] = $name;
			
			$this->acl_setSessionInfo($ses);
			
			return 0;
		}else return -1;
	}

	/**
	 * @name Logout
	 * @package user logout
	 */
	function acl_Logout() {
		$this->acl_setSessionInfo(null);
		unset($_SESSION[ADMIN_SESSION_KEY]);
	}


}