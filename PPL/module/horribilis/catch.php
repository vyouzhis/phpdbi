<?php
if (!defined('M5CPL')){exit;}
/**
 *
 * @copyright(c) 2014
 * @author vyouzhi
 * @package lib
 * @version 0.01  php
 */

class CatchAction {
	private  $mcol;
	private  $document;	
	function init() {
		
		if(function_exists("session_status")){
			if(@session_status()!=PHP_SESSION_ACTIVE) @session_start();
		}else @session_start();
		$uuid = $this->CheckFlag();

		$this->document = array();
		
		if(preg_match('/\/404/i', $_SERVER['REQUEST_URI'])==1){			
			return ;
		}
		if(preg_match('/\/injax/i', $_SERVER['REQUEST_URI'])==1){
			return ;
		}
		
		$this->document['ctime'] = time(); // 处理生成时间顺序 
		$this->document['PorG'] = PorG();
		$this->document['SERVER'] = $_SERVER;
		$this->document['SESSION'] = $_SESSION;
		$this->document['COOKIE'] = $_COOKIE;
		$this->document['uuid'] = $uuid;
		//$this->mcol = date("Yz", time());
		$this->mcol = 'ok';
		$this->Insert();		
	}

	private function Insert() {
		if(class_exists("MongoClient")){
			
			try
			{
				$m = new MongoClient("mongodb://192.168.1.220:27017");
				//$m = new MongoClient("mongodb://".$__mongodb['host'].":".$__mongodb['post']);
				$db = $m->h;
				$col ="el_".$this->mcol;
				$collection = $db->$col;
				$collection->insert($this->document);
			}
			catch ( MongoConnectionException $e )
			{
				echo '<p>Couldn\'t connect to mongodb, is the "mongo" process running?'.var_dump($e).'</p>';
				exit();
			}

		}
		else{
			echo "<p>class Mongoclient is not exit</p>";
		}
	}

	private function CheckFlag() {
		$_aves = $_COOKIE['__aves'];
		if(empty($_aves)){
			$uuid = $this->GetObjectID();
			$first = $this->hexTime();
			$last = $this->hexTime();
			$now = $this->hexTime();
			$count = 1;
		}else{
			$aves = explode(".", $_aves);
			$uuid = $aves[0];
			$first = $aves[1];
			$last = $aves[3];
			$now = $this->hexTime();
			$count = $aves[4]+1;
		}
		$this->setFlag($uuid, $first, $last, $now, $count);
		return $uuid;
	}

	private function setFlag($uuid=0, $first=0, $last=0, $now=0, $count=1) {
		// 设置一年时间，如果不删除
		//hash("adler32", ip2long($ip).mt_rand ()).dechex(time()).   dechex(time()) . dechex(time()).count

		$value = $uuid.".".$first.".".$last.".".$now.".".$count;

		setcookie ( "__aves" ,  $value ,  time ()+ 31557600 ,  "/" ,  DOMAIN ,  0 );

	}

	private function hexTime() {
		return dechex(time());
	}
	private function GetObjectID() {
		$ip = GetIP();
		return hash("adler32", ip2long($ip).mt_rand ());
	}
}
