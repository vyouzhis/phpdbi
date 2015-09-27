<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of image
 *
 * @author Administrator
 */
//image.php?file=http://www.xda.cn&size=200*300
//http://localhost/xap/image.php?file=http://localhost/xap/abc.jpg&size=200_300
//参数file为图片url
//size为长宽200_300

define("DEFAULT_IMG", "http://img2.xda-china.com/android/static/images/default.jpg");
define("THUMB_DIR", "./upload/thumb");

//$size = $_GET['size'];
//$file = $_GET['file'];
//使用 thumbsize(url,'200_150');返回生成的缩略图url地址
//比如thumbsize('http://localhost/xap/upload/imgs/20100101/abc.jpg','200_300');返回http://localhost/xap/upload/thumb/200_150//20100101/abc_200_150.jpg
function thumbsize($file, $size) {
    if (!preg_match("/(\.gif|\.jpeg|.\jpg|\.png|\.bmp)/i", $file)) {
        return $url;
    }

    if ($size) {
        if (!is_dir(THUMB_DIR . "/" . $size)) {
            mkdir(THUMB_DIR . "/" . $size, 777);
        }

        $path = explode("/", $file);
        $fullfile = array_pop($path);
        $second=array_pop($path);
        if(!is_dir(THUMB_DIR . "/" . $size."/".$second)){
            mkdir(THUMB_DIR . "/" . $size."/".$second, 777);
        }
        $wh = explode("_", $size);
        if (count($wh) != 2) {
            return $file;
        }
        $width = intval($wh[0]);
        $height = intval($wh[1]);
        if ($width == 0 || $height == 0) {
            return $file;
        }

        $ext = substr($fullfile, strrpos($fullfile, '.') + 1);
        ;
        $noext = substr($fullfile, 0, strrpos($fullfile, '.'));

        $thumbfile = THUMB_DIR . "/" . $size . "/$second/" . "$noext" . "_$width" . "_$height" . "." . $ext;

        if (file_exists($thumbfile)) {
            return MPIC_1 . $thumbfile;
        } else {
            if (resizeImage($file, $width, $height, $thumbfile)) {
                return MPIC_1 . $thumbfile;
            }
        }
    } else {
        return $url;
    }
}


function resizeImage($src, $maxwidth, $maxheight, $newpath) {

    list( $width, $height, $type ) = getimagesize($src);

    switch ($type) {
        case 1: $imgCreate = 'imagecreatefromgif';
            break;
        case 2: $imgCreate = 'imagecreatefromjpeg';
            break;
        case 3: $imgCreate = 'imagecreatefrompng';
            break;
        default: return false;
    }

    $im = $imgCreate($src);
    if (!$im)
        return false;
    if (($maxwidth && $width > $maxwidth) || ($maxheight && $height > $maxheight)) {
        if ($maxwidth && $width > $maxwidth) {
            $widthratio = $maxwidth / $width;
            $RESIZEWIDTH = true;
        }
        if ($maxheight && $height > $maxheight) {
            $heightratio = $maxheight / $height;
            $RESIZEHEIGHT = true;
        }
        if ($RESIZEWIDTH && $RESIZEHEIGHT) {
            if ($widthratio < $heightratio) {
                $ratio = $widthratio;
            } else {
                $ratio = $heightratio;
            }
        } elseif ($RESIZEWIDTH) {
            $ratio = $widthratio;
        } elseif ($RESIZEHEIGHT) {
            $ratio = $heightratio;
        }
        $newwidth = $width * $ratio;
        $newheight = $height * $ratio;
        if (function_exists("imagecopyresampled")) {
            $newim = imagecreatetruecolor($newwidth, $newheight);
            imagecopyresampled($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        } else {
            $newim = imagecreate($newwidth, $newheight);
            imagecopyresized($newim, $im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        }
        switch ($type) {
            case 1: imagegif($newim, $newpath);
                break;
            case 2: imagejpeg($newim, $newpath);
                break; // best quality
            case 3: imagepng($newim, $newpath);
                break; // no compression
            default:
                break;
        }
        ImageDestroy($newim);
        return true;
    } else {
        switch ($type) {
            case 1: imagegif($im, $newpath);
                break;
            case 2: imagejpeg($im, $newpath);
                break; // best quality
            case 3: imagepng($im, $newpath);
                break; // no compression
            default:
                break;
        }
        ImageDestroy($im);
        return true;
    }
}
