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

 
class SaltCheck  {
	private $Session;
		
	public function __construct() {
		$this->Session = new SC();
		$this->Session->StartSession();
	}
	
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
		
		/*echo "<br />";
		echo "session内部".$this->Session->GetSession(SALT);
		echo "<br />";
		echo $salt;
		echo "<br />";*/
		
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
		/*echo "session内部检测".$sess_salt;
		echo "传递过来".$salt;*/
		if($sess_salt == $salt){	
			//使用之后就去掉
			//$this->Session->SetSession(SALT, md5(rand(1, 999999)));		
			return true;	
		}
		else return false;
	}

}