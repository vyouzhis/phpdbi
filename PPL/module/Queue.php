<?php
/*
*		Queue for Xcache by vyouzhi at 2008-5-27
*
*/

/**
* @ignore
*/
if (!defined('M5CPL'))exit;
 
class Queue{
	
	private $q=array();
	private $pre=0;
	private $next=1;
	private $max=10;
	
	function Queue($qdata,$max=10){
		$this->pre=0;
		$this->next=0;
		$this->q[0]=$qdata;
		$this->max=$max-1;
	}
	
	//进队列
	function enQueue($qdata){
		$Leng = $this->getQueueLength();
		//只限 $max 个数据
		if($this->max==$Leng){   
			$this->derearQ();
		}
		array_unshift($this->q,$qdata);
		$this->next++;
		return $this->q;
	}
	//删除某个数据
	function deQueue($qdata){
		$key=array_search($qdata,$this->q);
		if(!is_null($key)){
			unset($this->q[$key]);
			asort($this->q);
			$this->next--;
		}
		return $this->q;
	}
	//删除尾数据
	function derearQ(){
		array_pop($this->q);
		$this->next--;
	}
	
	//获取队列
	function getQueue(){
		return $this->q;
	}
	//获取队列长度
	function getQueueLength(){
		return ($this->next - $this->pre);
	}
	//清空队列
	function clearQueue(){
		unset($this->q);
		unset($this->next);
		unset($this->pre);
	}
}
?>