<?php 
session_start();
//生成验证码图片 
Header("Content-type: image/PNG"); 
srand((double)microtime()*1000000);//播下一个生成随机数字的种子，以方便下面随机数生成的使用

$im = imagecreate(70,20) or die("Cant's initialize new GD image stream!"); //制定图片背景大小
$black = ImageColorAllocate($im, 0,0,0); //设定三种颜色
$white = ImageColorAllocate($im, 255,255,255); 
$gray = ImageColorAllocate($im, 200,200,200);

imagefill($im,0,0,$gray); //采用区域填充法，设定（0,0）

//生成数字
$answer='';
$authnum=0;
for($i=0;$i<2;$i++){
	$randnum=rand(1,10);
	$authnum=$authnum+intval($randnum);
	$answer=$answer.intval($randnum)."+";
}
$answer=rtrim($answer,"+");
$answer=$answer.'=?';
//$authnum=''.$answer;
//while(($authnum=rand()%100000)<10000); //生成随机的五们数
//将五位整数验证码绘入图片 
//$_COOKIE['authnum']=$authnum;
//error_log("auth:$authnum",0);
//setCookie("xda_reply_authnum",$authnum,null,'/');
$_SESSION['xda_verify1']=$authnum;


//imagestring($im, 5, 10, 3, $authnum, $black);
imagestring($im, 5, 10, 3, $answer, $black);
// 用 col 颜色将字符串 s 画到 image 所代表的图像的 x，y 座标处（图像的左上角为 0, 0）。
//如果 font 是 1，2，3，4 或 5，则使用内置字体

/*
for($i=0;$i<200;$i++) //加入干扰象素 
{ 
$randcolor = ImageColorallocate($im,rand(0,255),rand(0,255),rand(0,255));
imagesetpixel($im, rand()%70 , rand()%30 , $randcolor); 
} 
 
 */
ImagePNG($im); 
ImageDestroy($im);