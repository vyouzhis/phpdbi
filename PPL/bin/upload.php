<?php 
if (!defined('M5CPL'))exit;
class upload {
	
	private $name;
	private $type;
	private $allowType = array('txt','html','php','js','css','image/jpeg','image/jpg','image/gif','image/png','doc','swf','rar','zip');
	private $tmp;
	private $size;
	private $maxSize = 10000000;
	private $path;
	private $error=1;
	
	function __construct($field,$path,$types='',$maxSize='') {
		if(empty($_FILES)) {
			$this->error=-1;
			exit;
		}
		$file = $_FILES[$field];
		$this->name = $file['name'];
		$this->type = $file['type'];
		$this->tmp = $file['tmp_name'];
		$this->size = $file['size'];
		$this->path = $path.$file['name'];
		if(!empty($types)) $this->allowType = $types;
		if(!empty($maxSize)) $this->maxSize = $maxSize;
	}
	
	//获取文件名
	public function getName() {
		return $this->name;
	}
	
	//移动文件
	function move() {
		if(!$this->checkType()) return false;
		if(!$this->checkSize()) return false;
		if (move_uploaded_file($this->tmp, iconv('utf-8', 'gb2312',$this->path))) return true;
		else return false;	
	}
	
	//检查格式
	function checkType() {
		if(in_array($this->type,$this->allowType)) {
			return true;
		}else{
			$this->error = -3;
			return false;
		}
	}
	
	//检查大小
	function checkSize() {
		
		if($this->size>$this->maxSize) {
			$this->error = -2;
			return false;
		}else{
			return true;	
		}	
	}
	
	function getMsg() {
		switch ($this->error) {
		case 1:	
			return '添加成功';
			break;	
		case -1:	
			return '没有上传任何文件';
			break;	
		case -2:
			return '所上传的文件大小超出'.$this->maxSize.'bytes';
			break;
		case -3:
			return '文件格式不允许';
			break;
		default:
			return '未知的错误';
		}
	}
}
	
?>