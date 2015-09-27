<?php
if (!defined('M5CPL'))
exit;

interface BaseLangInterface{
	public function _Lang($key);
}

/**
 *
 * 类用在 多语言 类上面 ...
 * @author jsb-015
 *
 */
class LibLang implements BaseLangInterface{
	private $_lang;

	public function __construct($lang) {
		$this->_lang = $lang;
	}

	/**
	 * @name _Lang
	 * @see PPL/class/BaseLangInterface::_Lang()
	 * @param String $key
	 * @package 反射通过 key 读取语言 value
	 * @return false or value 
	 */
	public function _Lang($key){		
		if(array_key_exists($key, $this->_lang)){
			return $this->_lang[$key];
		}else return false;
	}
}

/**
 *
 * 用在获取多语言类上面 ...
 * @author jsb-015
 *
 */
class BaseLang extends PObject implements BaseLangInterface{
	private $stdClass;
	private $langClass;
	private $langComm;
	
	public function __construct($stdClass=null) {
		parent::__construct($stdClass);
		
		$this->load(LANG_COMMON);

		if($stdClass == null) return;

		$this->load($stdClass);

	}

	/**
	 * 
	 * @name load
	 * @param unknown_type $stdClass
	 * @package load lang file
	 * @return null
	 */
	private function load($stdClass) {

		$this->stdClass = $stdClass;
		$seg = getSeg();
	
		if(!is_file(LIBS)) CacheLibs();
			include(LIBS);
		$m_lib = unserialize($_libs);
		//echo $m_lib[$this->stdClass]."--";
		
		$langDir = "";
		if( array_key_exists($this->stdClass, $m_lib)){	
			$dn = dirname($m_lib[$this->stdClass]);		
			$dir = explode("lib", $dn);					
			$langDir = $dir[1];
		}
		
		if(is_file(LANG.$seg.$langDir.$seg.$this->stdClass.".php")){			
			include_once LANG.$seg.$langDir.$seg.$this->stdClass.".php";
			
		}else if(is_file(LANG.$this->stdClass.".php")){
			include_once LANG.$this->stdClass.".php";
		}
		else{
			//echo LANG.$seg.$langDir.$seg.$this->stdClass.".php<br />";
			return;
			//throw new \Exception("Lib '{$stdClass}' lang does not exist.");
		}
		$class = "_".$this->stdClass;
		if(class_exists($class)){
			if($stdClass == LANG_COMMON){
				$this->langComm = new $class();
			}else{
				$this->langClass = new $class();
			}
		}
	}

	/**
	 * @name _Lang
	 * @see PPL/class/BaseLangInterface::_Lang()
	 * @param String $key
	 * @package 通过 key 输入语言 value
	 */
	public function _Lang($key="") {
		if(empty($key)) $key=$this->stdClass;
		$obj = array($this->langComm, $this->langClass);
		foreach ($obj as $k=>$v) {			
			$value = $this->find($v, $key);			
			if($value != false) return $value;
		}
			
		return "lang key:{$key} is not exist --";
	}
	
	/**
	 * 
	 * @name find
	 * @param unknown_type $object
	 * @param unknown_type $key
	 * @package find lang from key
	 * @return value
	 */
	private function find($object, $key) {
		if(is_object($object)){
			$value = $object->_Lang($key);
		}		
		return $value;
		
	}
	
/**
	 * @package 重新再定义一下，可以获得短的语言公共 key ;
	 * @see PPL/class/BaseLang::_Lang()
	 */
	public function _CLang($key) {

		return $this->_Lang(LANG_COMMON."_".$key);
	}
	
	/**
	 * @package 重新再定义一下，可以获得短的语言 key,但公共的key 获取采用 $this->_CLang("key");
	 * @see PPL/class/BaseLang::_Lang()
	 */
	public function _MLang($key) {

		return $this->_Lang($this->stdClass."_".$key);
	}

}