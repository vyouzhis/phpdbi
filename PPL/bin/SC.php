<?php
/**
 *
 *
 *
 */

class SC{

	private static $sc;
	public function __construct()
	{
	}

	public final function __clone()
	{
		throw new BadMethodCallException("Clone is not allowed");
	}

	/**
	 * getInstance
	 *
	 * @static
	 * @access public
	 * @return
	 */
	public static function getInstance()
	{
		return (self::$sc instanceof SC ) ? self::$sc : new SC();
	}

	public function StartSession(){
		
		session_set_cookie_params(0, '/', SESSION_DOMAIN);
		
		@session_start();
	}

	public function SetSession($key, $value){
		$_SESSION[$key] = $value;
	}

	/**
	 * @name GetSession	 
	 * @param unknown_type $key
	 * @package get session
	 * @return null or object
	 */
	public function GetSession($key){
		if(isset($_SESSION[$key])) return $_SESSION[$key];
		else return null;
	}

	public function DelSession($key){
		unset($_SESSION[$key]);
	}

	public function SetCookie($key, $value, $extime=3600){
		global $CDomain;
		setcookie($key, $value, time()+$extime, "/", $CDomain);
	}

	public function GetCookie($key){
		if(isset($_COOKIE[$key])) return $_COOKIE[$key];
		else return null;
	}

	public function DelCookie($key){
		global $CDomain;
		setcookie($key, '', time()-60*60*24, "/", $CDomain, 1);
	}

}



?>