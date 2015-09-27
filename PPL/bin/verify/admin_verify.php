<?php
session_start();
require_once("./captcha.php");
$captcha = new SimpleCaptcha();

// OPTIONAL Change configuration...
//$captcha->wordsFile = 'words/es.php';
//$captcha->session_var = 'secretword';
//$captcha->imageFormat = 'png';
//$captcha->scale = 3; $captcha->blur = true;
//$captcha->resourcesPath = "/var/cool-php-captcha/resources";

// OPTIONAL Simple autodetect language example
/*
if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
    $langs = array('en', 'es');
    $lang  = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
    if (in_array($lang, $langs)) {
        $captcha->wordsFile = "words/$lang.php";
    }
}
*/

// Image generation
$captcha->width=80;
$captcha->height=30;
$captcha->minWordLength=3;
$captcha->maxWordLength=3;
$captcha->wordsFile="";
//$captcha->scale=1;
$captcha->session_var = "xda_verify";
$captcha->fonts = array(
        'Antykwa'  => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'AntykwaBold.ttf'),
        'Candice'  => array('spacing' =>-1,'minSize' => 19, 'maxSize' => 20, 'font' => 'Candice.ttf'),
        'DingDong' => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'Ding-DongDaddyO.ttf'),
        'Duality'  => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'Duality.ttf'),
        'Heineken' => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'Heineken.ttf'),
        'Jura'     => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'Jura.ttf'),
        'StayPuft' => array('spacing' =>-1,'minSize' => 19, 'maxSize' => 20, 'font' => 'StayPuft.ttf'),
        'Times'    => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'TimesNewRomanBold.ttf'),
        'VeraSans' => array('spacing' => -1, 'minSize' => 19, 'maxSize' => 20, 'font' => 'VeraSansBold.ttf'),
    );
$captcha->CreateImage();

?>