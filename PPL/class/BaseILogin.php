<?php
if (!defined('M5CPL')){exit;}

/**
 *
 * 用户中心后台的类
 * @author vyouzhi
 *
 */
require_once Lib.'/common/icore_menu.php';
class BaseILogin extends BaseTheme {
	private $stdClass;
	private $stdObject;
	private $Session;

	public function __construct($stdClass=null) {
		global $router, $match;
		$this->Session = new SC();
		$this->Session->StartSession();

		parent::__construct($stdClass);
		$this->stdClass = $stdClass;
		
		$res = true;
		
		if(!in_array($this->stdClass , unserialize(WEB_ICORE_ACTION)))
			$res = $this->IsLogin();
	
		if($res==false){
			$this->ajax = 1;
			ShowMsg(parent::_CLang("error_nologin"), $router->url('icore_login'));
		}
	}
	
	/**
	 * @name IsLogin
	 * @package 检查是否登录
	 * @return bool
	 */
	private function IsLogin() {
		$uid = $this->u_getUid();
		if(intval($uid)>0)return true;
		
		$this->u_Logout();
		return false;
	}

	/**
	 * @name u_getUid
	 * @package 从 SESSION 中获得 uid, 如果曾经登录过,与 parent 不一样
	 * @return Int
	 */
	public function u_getUid() {
		$res = $this->GetSessionInfo();
		if(empty($res)) return 0;
		return $res['uid'];
	}

	/**
	 * @name u_getName
	 * @package 从 SESSION 中获得 name, 如果曾经登录过,与 parent 不一样
	 * @return String
	 */
	public function u_getName() {
		$res = $this->GetSessionInfo();
		if(empty($res)) return 0;
		return $res['name'];
	}

	/**
	 * @name u_VerifyUrl
	 * @package 获取验证码的地址 a 参数放着，以后可能用得到
	 * @return String url
	 */
	public function u_VerifyUrl() {
		global $router;
		return $router->url("img_verify")."?a=".time();
	}
	/**
	 * @name u_checkVerify
	 * @package 对比验证码。从session中获取以phone为标志位的CODE码，并进行比较。
	 * @param unknown_type $ver
	 * @return bool
	 */
	public function u_checkVerify($phone,$code) {
		$verify = $this->Session->GetSession(WEB_SESSION_VERIFY);
		if($verify[$phone] != $code) return false;
		return true;
	}
	
	/**
	 * @name u_getSalt
	 * @package 生成 salt
	 * @return String
	 */
	public function u_getSalt() {
		$salt = md5(time());
		$this->Session->SetSession(SALT, $salt);
		return $salt;
	}

	/**
	 * @name u_checkSalt
	 * @param String $salt
	 * @package check salt
	 * @return true or false
	 */
	public function u_checkSalt($salt) {
		
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
	 * @name u_Login
	 * @param $login  email or phone num
	 * @param $passwd
	 * @param int $type=0  0 email, 1, phone
	 * @param $sess_salt
	 * @package user login action 使用 密码登录
	 * @return  0 ok,  -1 login error, -2 salt error , -3 empty error
	 */
	function u_Login($login, $passwd, $type=0) {
		
		if(empty($login)) return -1;
		if(empty($passwd)) return -2;
		if($type!=0 && $type!=1) return -4;
		/*查询是否存在此账户,且密码相等*/
		if ($type == 0){
			 $where = "where (email='".$login."' or login_md5='".md5($login)."') and password='".md5($passwd."99elon")."'";
		}
		/*查询是否存在此账户*/
		else if($type == 1 ){
			/*并验证验证码*/
			if($this->u_checkVerify($login,$passwd)){
				$where = "where (email='".$login."' or login_md5='".md5($login)."')";
			}
			else{
				return -3;
			}
		}else{/*其他全部抛弃*/
			return -4;
		}
		/*查询是否存在此账户*/
		$format = "select user_id,  password, real_name, sex, email, mobile, login_md5 from ".DB_PRE."user_main %s limit 1 ";
		$sql = sprintf($format,$where);
		
		$res = parent::FetchOne($sql);

		if(is_array($res) ){
			
			$ses["uid"] = $res['user_id'];
			$ses['name'] = $res['real_name'];
			$ses['sex'] = $res['sex'];
				
			$this->SetSessionInfo($ses);

			/**
			 * 设置  BaseTheme 的全域名  cookie
			 */
			setcookie ( WEB_COOKIE_UID ,  $res['user_id'] ,  time ()+ 31557600 ,  "/" ,DOMAIN );
			setcookie ( WEB_COOKIE_NAME ,  $res['real_name'] ,  time ()+ 31557600 ,  "/" ,DOMAIN );
			setcookie ( WEB_COOKIE_SEX ,  $res['sex'] ,  time ()+ 31557600 ,  "/" ,DOMAIN );
				
			return 0;
		}else return -1;
	}



	/**
	 * @name SetSessionInfo
	 * @param array or string $value
	 * @package 设置 session
	 */
	private function SetSessionInfo($value) {
		$this->Session->SetSession(WEB_SESSION_KEY, $value);
	}

	/**
	 * @name GetSessionInfo
	 * @package 设置 session
	 * @return array
	 */
	private function GetSessionInfo() {
		return $this->Session->GetSession(WEB_SESSION_KEY);
	}


	/**
	 * @name Logout
	 * @package user logout
	 */
	public function u_Logout() {
		$this->SetSessionInfo(null);
		unset($_SESSION[WEB_SESSION_KEY]);
			setcookie ( WEB_COOKIE_UID ,  "" ,  time ()+ 1 ,  "/" ,DOMAIN );
			setcookie ( WEB_COOKIE_NAME ,  "" ,  time ()+ 1 ,  "/" ,DOMAIN );
			setcookie ( WEB_COOKIE_SEX ,  "" ,  time ()+ 1 ,  "/" ,DOMAIN );
	}
	/**
	 * @name u_right
	 * @package 加载用户中心右边的菜单
	 */
	public function u_right(){
		$menu = new icore_menu();
		$menu->filter();
		
	}
}