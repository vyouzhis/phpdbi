<?php
if (!defined('M5CPL')){exit;}

include_once(Dev."/BaseSQLInterFace.php");

class BaseMySQL implements BaseSQL{
	private static $adb;
	private static $link_ID;
	private static $rws = array("r", "w");

	public function __construct(){
		self::$link_ID = array();
		
	}

	/*
	 public final function __clone(){
	 throw new BadMethodCallException("Clone is not allowed");
	 } */

	/**
	 * getInstance
	 *
	 * @static
	 * @access public
	 * @return object LinkSQL instance
	 */
	public function getInstance(){
		return (self::$adb instanceof BaseMySQL ) ? self::$adb : new BaseMySQL();
	}


	/**
	 * SwitchLW
	 *
	 */
	public static function SwitchLW($rw, $config, $key=''){
		if(!isset($L_r) or !isset($L_w)) require(Dev."/config.".$config."inc.php");

		if ($rw=='r'){
			$L = $L_r;
			$hh = hash('md5', $key);
		}else
		$L = $L_w;
		$c = count($L);
		$h = 0;
		if($c>1){
			$h = bin2hex(substr($hh, -2))%$c;
		}

		return $L[$h];
	}

	/**
	 * Query
	 * @access public
	 * @param string $sql
	 * @param array or string $pb
	 * @return array
	 */
	public static function Query($sql, $pb=array()){
	if(count(self::$link_ID)==0){

		foreach(self::$rws as $k=>$v){
			$var = self::SwitchLW($v, "", time());
			self::$link_ID[$k] = @mysqli_connect($var['host'], $var['user'], $var['passwd'], $var['db']) ;
			//OR die("mysql connect error <br />");
			@mysqli_query(self::$link_ID[$k], "SET NAMES utf8;");
		}
	}
		if(empty($sql)) {return false;}
		$res = array();

		$link = self::$link_ID[array_search($pb["rw"], self::$rws)];
			
		$result = mysqli_query($link, $sql);
		if(!$result){
			return false;
		}
		$flag = true;
		if($pb['rw']=='r'){
				
			while ($row = @mysqli_fetch_array($result, MYSQLI_ASSOC)) {
				$res[] = self::hackPress($row);
			}

			return empty($res)?null:$res;
		}elseif($pb['rw']=='w'){
			if($pb['returnid']=='1')
			return array(array("id"=>mysqli_insert_id($link)));
			else
			return $flag;
		}
	}

	/**
	 * FetchArray
	 * @param string $sql
	 * @param array or string $pb
	 * @return array
	 *
	 */
	public static function FetchArray($sql, $pb=array('rw'=>'r')){

		/*******************上传到线上时，务必将memcache注释取消*********************/
		/*$memcache_obj  = new  Memcache ;
		$memcache_obj -> connect ( '192.168.1.14' ,  11211 );
		$var  =  $memcache_obj -> get ( md5($sql) );*/

		if(strlen($var)<5){
			//$result = parent::FetchArray($sql);
			$result = self::Query($sql, $pb);
			//$memcache_obj->set(md5($sql), serialize($result), 0, 60*10);
		}else{
			$result = unserialize($var);
		}
		
		return $result;
	}

	/**
	 * FetchOne
	 * @param string $sql
	 * @param array or string $pb
	 * @return array
	 *
	 */
	public static function FetchOne($sql, $pb=array('rw'=>'r')){
		/*******************上传到线上时，务必将memcache注释取消*********************/
		/*
		$memcache_obj  = new  Memcache ;
		$memcache_obj -> connect ( '192.168.1.14' ,  11211 );
		$var  =  $memcache_obj -> get ( md5($sql) );
		*/
		if(strlen($var)<5){
			//$result = parent::FetchArray($sql);
			$result = self::Query($sql, $pb);
			//$memcache_obj->set(md5($sql), serialize($result), 0, 60*10);
		}else{
			$result = unserialize($var);
		}
		//$res = self::Query($sql, $pb);

		if(is_array($result) && count($result))
		return $result[0];
		else return $result;

	}
	/**
	 * Delete
	 * @param string $sql
	 * @param array or string $pb
	 * @return True or False
	 *
	 */
	public static function Delete($sql, $pb=array('rw'=>'w')){
		return self::Query($sql, $pb);
	}
	/**
	 * Count
	 * @param string $sql
	 * @param array or string $pb
	 */
	public static function Count($sql, $pb=array('rw'=>'w')){
		return self::Query($sql, $pb);
	}

	public static function Insert($sql, $pb=array('returnid'=>'1', 'rw'=>'w')){
		return self::Query($sql, $pb);
	}


	public static function Update($sql, $pb=array('rw'=>'w')){
		return self::Query($sql, $pb);
	}
	public function Free(){

	}
	public static function Close(){
		foreach(self::$link_ID as $k=>$link){
			ysql_close($link);
		}
	}

	private static function hackPress($row) {
		if(defined(ADMIN_SUB_DISABLE) && ADMIN_SUB_DISABLE){
			$session = new SC();
			$us = $session->GetSession(ADMIN_SESSION_KEY);
			$hack = unserialize($us['role'][0]['subpermiss']);
			if(!is_array($hack))return $row;
				
			foreach ($hack as $v) {
				if(array_key_exists($v, $row)){
					$row[$v]="*****";
				}
			}
		}

		return $row;
	}

	/**
	 * @desc 更新记录,执行 UPDATE 操作
	 *
	 * 说明: $arrSets 格式如下:
	 *      $arrSets = array('uid'=>1,
	 *                       'name'=>'yuanwei');
	 *
	 * 解析后SET为: "uid=1,name='yuanwei'"
	 *
	 * @param string $table   表
	 * @param array $arrSets  设置的字段值
	 * @param mixed $where    条件,详细请看 Select()成员
	 * @param string $order   排序字段
	 * @param int $limit      记录行
	 * @param string $group   分组字段
	 * @return boolean
	 */
	public function Updatedata($table, $arrSets, $where = '', $order = '', $limit = '', $group = '',$pb=array('rw'=>'w'))
	{
		$sqlSet = $this->parseUpdateSet($arrSets);
		$sql = sprintf("UPDATE %s SET %s", $table, $sqlSet);
		if ($where != '')
		{
			$sql .= ' WHERE '. $this->parseWhere($where);
		}
		if ($order != '')
		{
			$sql .= ' ORDER BY '. $order;
		}
		if ($group != '')
		{
			$sql .= ' GROUP BY '. $group;
		}
		if ($limit != '')
		{
			$sql .= ' LIMIT '. $limit;
		}
		return self::Query($sql, $pb);
	}

	/**
	 * @desc   解析 UPDATE 操作字段设置
	 * @access   protected
	 * @param   array $updateSet
	 * @return   string
	 */
	public function parseUpdateSet($updateSetting)
	{
		$sqlSetting = '';
		$spr = '';
		if (is_array($updateSetting))
		{
			foreach ($updateSetting as $key => $value)
			{
				$sqlSetting .= $spr . $this->parseField($key) . '=' . $this->returnValue($value);
				$spr = ',';
			}
		}
		else
		{
			$sqlSetting = $updateSetting;
		}
		return $sqlSetting;
	}

	/**
	 * @desc 插入记录,执行 INSERT 操作
	 * @access public
	 * 说明:有关 $arrSets 数组的定义请看: Update()成员
	 *
	 * @param string $table   表名
	 * @param array $arrSets  插入的字段
	 * @param boolean $replace   是否采用 REPLACE INTO 的方式插入记录
	 * @return int
	 */
	public function Insertdata($table, $arrSets, $replaceInto = false, $pb=array('returnid'=>'1', 'rw'=>'w'))
	{
		$ret = $this->parseInsertSet($arrSets);
		$insertOpt = $replaceInto ? 'REPLACE' : 'INSERT';
		$sql = sprintf("%s INTO %s(%s) VALUES(%s)", $insertOpt, $table, $ret['key'], $ret['val']);
		//echo $sql;
		//exit();
		return self::Query($sql, $pb);
	}

	/**
	 * @desc   解析 INSERT 操作字段设置
	 * @access   protected
	 * @param array $insertSetting
	 * @return array
	 */
	public function parseInsertSet($insertSetting)
	{
		$fields = '';  /*字段名*/
		$values = '';  /*值*/
		$spr    = '';  /**/
		foreach ($insertSetting as $key => $value)
		{
			$fields .= $spr . $this->parseField($key);
			$values .= $spr . $this->returnValue($value);
			$spr = ',';
		}
		return array('key' => $fields, 'val' => $values);
	}

	/**
	 *
	 * @desc  解析字段
	 * @var  $fieldName
	 * @access  public
	 *
	 */
	public function parseField($fieldName)
	{
		$fieldName = trim($fieldName);
		if (strstr($fieldName,' ') === false)
		{
			$fieldName = '`' . $fieldName . '`';
		}
		return $fieldName;
	}

	/**
	 *
	 * @desc  根据值的类型返回SQL语句式的值
	 * @access   protected
	 * @param   unknown_type $val
	 * @return   unknown
	 *
	 */
	public function returnValue($val)
	{
		if (is_int($val) || is_float($val))
		{
			return $val;
		}
		else
		{
			$val = addslashes($val);
			return "'$val'";
		}
	}

	/**
	 *
	 * @desc  解析 SQL WHERE 条件
	 * @access   protected
	 * @param   mixed $where
	 * @return   string
	 *
	 */
	public function parseWhere($where)
	{
		$sqlWhere = '1 ';
		if (is_array($where))
		{
			foreach ($where as $key => $value)
			{
				$sqlWhere .= " AND " . $this->parseField($key);
				if (is_array($value))
				{
					foreach ($value as $k => $v)
					{
						$sqlWhere .= sprintf(" %s ", strtoupper($k)) . $this->returnValue($v);
					}
				}
				else
				{
					$sqlWhere .= '='.$this->returnValue($value);
				}
			}  /*end of foreach ($where as $k=>$v)*/
		}
		else
		{
			$sqlWhere = $where;

		}  /*end of if (is_array($where))*/

		return $sqlWhere;
	}

	/**
	 * @desc  返回最后执行 Insert() 操作时表中有 auto_increment 类型主键的值
	 * @access   public
	 * @param   void
	 * @return int
	 */
	public function getLastInsertId()
	{
		/**
	 	* [PHP手册] : 如果 AUTO_INCREMENT 的列的类型是 BIGINT，则 mysqli_insert_id() 返回的值将不正确。
	 	* 可以在 SQL 查询中用 MySQL 内部的 SQL 函数 LAST_INSERT_ID() 来替代。
	 	*/
		$insertId = $this->FetchOne('select last_insert_id()');
		return $insertId ? $insertId : 0;
	}

}




class DBSQL extends BaseMySQL{
}



?>
