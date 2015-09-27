<?php
if (!defined('M5CPL'))
exit;

/*上传类*/

class upload{
		public $previewsize=0.125  ;   //预览图片比例
		public $preview=0;   //是否生成预览，是为1，否为0
    public $datetime;   //随机数
    public $ph_name;   //上传图片文件名(绝对路径)
    public $_ph_name;   //上传图片文件名(相对路径)
    public $ph_tmp_name;    //图片临时文件名
    public $ph_path;    //上传文件绝对存放路径
    public $_ph_path;    //上传文件相对存放路径
		public $ph_type;   //图片类型
    public $ph_size;   //图片大小
    public $imgsize;   //上传图片尺寸，用于判断显示比例
    public $al_ph_type=array('image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png');    //允许上传图片类型
    public $al_ph_size=1000000;   //允许上传文件大小
  function __construct(){
    $this->set_datatime();
  }
  //根据日期来存放图片文件
  function set_path($root,$path){
  	$Year = date("Y");
  	$Month = date("m");
  	$Day = date("d");
  	$this->ph_path=$path.$Year."/".$Month.$Day."/";//绝对路径
  	$this->_ph_path=str_replace($root,"",$path).$Year."/".$Month.$Day."/";//相对路径
  	}
  function set_datatime(){
   $this->datetime=date("YmdHis");
  }
   //获取文件类型
  function get_ph_type($phtype){
     $this->ph_type=$phtype;
  }
  //获取文件大小
  function get_ph_size($phsize){
     $this->ph_size=$phsize."<br>";
  }
  //获取上传临时文件名
  function get_ph_tmpname($tmp_name){
  	
    $this->ph_tmp_name=$tmp_name;
    $this->imgsize=getimagesize($tmp_name);
  }
  //获取原文件名
  function get_ph_name($phname){
    $this->ph_name=$this->ph_path.$this->datetime.strrchr($phname,"."); //strrchr获取文件的点最后一次出现的位置
		$this->_ph_name=$this->_ph_path.$this->datetime.strrchr($phname,".");
		
		return $this->ph_name;
  }
  //递归建立目录，目录格式为"年/月日/"
  public function mkdirs($dir) {
  	 if(!is_dir($dir)) {
  	 	 if(!$this->mkdirs(dirname($dir))){ return false; } 
  	 	 if(!mkdir($dir,0777)){ return false; } 
  	 } return true; 
  	}
  	
	// 判断上传文件存放目录
  function check_path(){
    if(!file_exists($this->ph_path)){
     $this->mkdirs($this->ph_path);
    }
  }
  
  //判断上传文件是否超过允许大小
  function check_size(){
    if($this->ph_size>$this->al_ph_size){
     $this->showerror("上传图片超过2000KB");
    }
  }
  //判断文件类型
  function check_type(){
   if(!in_array($this->ph_type,$this->al_ph_type)){
         $this->showerror("上传图片类型错误");
   }
  }
  //上传图片
   function up_photo(){
   if(!move_uploaded_file($this->ph_tmp_name,$this->ph_name)){
    $this->showerror("上传文件出错");
   }
  }
  
  //创建缩略图
   function small_img($width, $height,$big_img="") {//原始大图地址，缩略图宽度，高度，缩略图地址
   		if(empty($big_img))$big_img = $this->ph_name;
   		$small_img=$this->ph_path."thumb_".str_replace($this->ph_path,"",$big_img);
			$imgage = getimagesize($big_img); //得到原始大图片
			switch ($imgage[2]) { // 图像类型判断
					case 1:
					$im = imagecreatefromgif($big_img);
					break;
					case 2:
					$im = imagecreatefromjpeg($big_img);
					break;
					case 3:
					$im = imagecreatefrompng($big_img);
					break;
			}
			$src_W = $imgage[0]; //获取大图片宽度
			$src_H = $imgage[1]; //获取大图片高度
			$tn = imagecreatetruecolor($width, $height); //创建缩略图
			imagecopyresampled($tn, $im, 0, 0, 0, 0, $width, $height, $src_W, $src_H); //复制图像并改变大小
			imagejpeg($tn,$small_img); //输出图像
			return $this->_ph_path.basename($small_img);
		}
  //图片预览
   function showphoto(){
      if($this->preview==1){
      if($this->imgsize[0]>2000){
        $this->imgsize[0]=$this->imgsize[0]*$this->previewsize;
        $this->imgsize[1]=$this->imgsize[1]*$this->previewsize;
      }
         echo("<img src=\"{$this->ph_name}\" width=\"{$this->imgsize['0']}\" height=\"{$this->imgsize['1']}\">");
     }
   }
  //错误提示
  function showerror($errorstr){
    echo "<script language=java script>alert('$errorstr');location='java script:history.go(-1);';</script>";
   	exit();
  }
  function save(){
   $this->check_path();
   $this->check_size();
   $this->check_type();
   $this->up_photo();
   //$this->showphoto();
  }
}
?>