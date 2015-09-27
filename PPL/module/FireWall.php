<?php
if (!defined('M5CPL'))
exit;


class FireWall {
	private $Session;
	
	public function __construct() {
		$this->Session = new SC();
		$this->Session->StartSession();
				
	}
	/**
	 * @name getSalt
	 * @package use login,pls use the salt $.md5($.md5(passwd)+salt),and only post the md5 to server ...
	 * @return Salt String
	 */
	public function getSalt() {
		// put the salt in login page, js_md5 will $.md5($.md5(passwd)+salt), and post the result to server, no the plaintext
		// because only one will sonrt the network, only catch the md5
		// but we will use https 
		$salt = md5(time());
		$this->Session->SetSession(SALT, $salt);
		return $salt;		
	}
	
	/**
	 * @name checkSalt	 
	 * @param String $salt
	 * @package check salt 
	 * @return true or false
	 */
	public function checkSalt($salt) {
		$sess_salt = $this->Session->GetSession(SALT);
		if($sess_salt == $salt)return true;
		else return false;
	}
}