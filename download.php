<?php

$fa = $_POST["btn"];
$fa = urldecode($fa);

// PHP Font Awesome to PNG
include("icons.data.php");

// Font Awesome TTF file
$font = "fontawesome-webfont.ttf";

// 色の指定(favicon color)
$colorcode = $_POST["color"];
$colorcode = preg_replace("/#/", "", $colorcode);
// can not use 255,0,255 as it is reserved color for background triming
if(strtolower($colorcode) == "ff00ff"){
  $colorcode = "000000";
}
// 「******」という形になっているはずなので、2つずつ「**」に区切る
// そしてhexdec関数で変換して配列に格納する
$array_colorcode['red'] = hexdec(substr($colorcode, 0, 2));
$array_colorcode['green'] = hexdec(substr($colorcode, 2, 2));
$array_colorcode['blue'] = hexdec(substr($colorcode, 4, 2));

// サイズの指定(favicon size)
$outputSize = $_POST["size"];

// 出力先(destination folder name)
$outputDir = './output/' . $outputSize;

$size = $width = $height = $outputSize*3;
$fontSize = $outputSize;
$padding = (int)ceil(($outputSize/25));

// The text to draw
foreach($icons as $iKey=>$iParam)
{
  if($iKey != $fa) continue;
  $text = $iParam['code'];
  $fileName = sprintf("%s/%s.png", $outputDir, $iKey); 
  $dirPath = dirname($fileName);
  
  if(!is_dir($dirPath) || !file_exists($dirPath))
  {
    mkdir_recursive($dirPath, 0777);
  }
  
  // Create the image
  $im = imagecreatetruecolor($width, $height);
  imagealphablending($im, false);

  // Create some colors
  $fontC = imagecolorallocate($im, $array_colorcode['red'], $array_colorcode['green'], $array_colorcode['blue']);

  $bgc = imagecolorallocatealpha($im, 255, 0, 255, 127);
  imagefilledrectangle($im, 0, 0, $width,$height, $bgc);
  imagealphablending($im, true);

  // Add the text
  list($fontX, $fontY) = ImageTTFCenter($im, $text, $font, $fontSize);
  imagettftext($im, $fontSize, 0, $fontX, $fontY, $fontC, $font, $text);

  // Using imagepng() results in clearer text compared with imagejpeg()
  imagealphablending($im,false);
  imagesavealpha($im,true);
  imagetrim($im, $bgc, $padding);
  imagecanvas($im, $outputSize, $bgc, $padding);
  imagepng($im, $fileName);
  imagedestroy($im);

}

// The PHP ICO Generator
require('./php-ico-master/class-php-ico.php');

// フォーマット(ico or png)
$format = $_POST["format"];

// 出力先(file path)
if($format == "ico"){
  $destination = $dirPath . '/' . $fa . '.ico';
}else{
  $destination = $fileName;
}

// ファビコンの作成(generate favicon using The PHP ICO Generator)
if($format == "ico"){
  $ico_lib = new PHP_ICO($fileName);
  $ico_lib->save_ico($destination);
}

// ファイル名(file name)
if($format == "ico"){
  $fname = $fa . '.ico';
}else{
  $fname = $fa . '.png';
}

// ダウンロードの処理(download)
header('Content-Type: application/force-download');
header('Content-Length: ' . filesize($destination));
header('Content-disposition: attachment; filename="' . $fname . '"');
readfile($destination);

// ダウンロード後ファイルを削除する(delete files)
unlink($fileName);
if($format == "ico"){
  unlink($destination);
}

function mkdir_recursive($pathname, $mode)
{
    is_dir(dirname($pathname)) || mkdir_recursive(dirname($pathname), $mode);
    return is_dir($pathname) || @mkdir($pathname, $mode);
}

function ImageTTFCenter($image, $text, $font, $size, $angle = 45) 
{
    $xi = imagesx($image);
    $yi = imagesy($image);

    // First we create our bounding box for the first text
    $box = imagettfbbox($size, $angle, $font, $text);

    $xr = abs(max($box[2], $box[4]));
    $yr = abs(max($box[5], $box[7]));

    // compute centering
    $x = intval(($xi - $xr) / 2);
    $y = intval(($yi + $yr) / 2);

    //echo $x;echo '|';  echo $y;exit;
    return array($x, $y);
}

function imagetrim(&$im, $bg, $pad=null){

    // Calculate padding for each side.
    if (isset($pad)){
        $pp = explode(' ', $pad);
        if (isset($pp[3])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[3]);
        }else if (isset($pp[2])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[2], (int) $pp[1]);
        }else if (isset($pp[1])){
            $p = array((int) $pp[0], (int) $pp[1], (int) $pp[0], (int) $pp[1]);
        }else{
            $p = array_fill(0, 4, (int) $pp[0]);
        }
    }else{
        $p = array_fill(0, 4, 0);
    }

    // Get the image width and height.
    $imw = imagesx($im);
    $imh = imagesy($im);

    // Set the X variables.
    $xmin = $imw;
    $xmax = 0;

    // Start scanning for the edges.
    for ($iy=0; $iy<$imh; $iy++){
        $first = true;
        for ($ix=0; $ix<$imw; $ix++){
            $ndx = imagecolorat($im, $ix, $iy);
            if ($ndx != $bg){
                if ($xmin > $ix){ $xmin = $ix; }
                if ($xmax < $ix){ $xmax = $ix; }
                if (!isset($ymin)){ $ymin = $iy; }
                $ymax = $iy;
                if ($first){ $ix = $xmax; $first = false; }
            }
        }
    }

    // The new width and height of the image. (not including padding)
    $imw = 1+$xmax-$xmin; // Image width in pixels
    $imh = 1+$ymax-$ymin; // Image height in pixels

    // Make another image to place the trimmed version in.
    $im2 = imagecreatetruecolor($imw+$p[1]+$p[3], $imh+$p[0]+$p[2]);

    // Make the background of the new image the same as the background of the old one.
    $bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
    imagefill($im2, 0, 0, $bg2);
  imagealphablending($im2, true);

    // Copy it over to the new image.
    imagecopy($im2, $im, $p[3], $p[0], $xmin, $ymin, $imw, $imh);

    // To finish up, we replace the old image which is referenced.
    imagealphablending($im2,false);
    imagesavealpha($im2,true);
    $im = $im2;
    //imagedestroy($im2);
}

function imagecanvas(&$im, $size, $bg, $padding)
{
  $srcW = imagesx($im);
  $srcH = imagesy($im);
  
  $srcRatio = $srcW/$srcH;
  
  $im2 = imagecreatetruecolor($size, $size);
  $bg2 = imagecolorallocatealpha($im2, ($bg >> 16) & 0xFF, ($bg >> 8) & 0xFF, $bg & 0xFF, 127);
  //imagefilledrectangle($im2, 0, 0, $size,$size, $bg2);
  imagefill($im2, 0, 0, $bg2);
  imagealphablending($im2, true);
  
  // init
  $dstX = $dstY = $srcX = $srcY = 0;
  $dstW = $dstH = $size;

  // if source size is smaller than output size
  if($srcW < $size && $srcH < $size)
  {
    $dstW = $srcW; $dstH = $srcH;
  }
  // if source is bigger than output
  else
  {
    // use padding
    // if horizontal long
    if($srcW > $srcH)
    {
      $dstW = $size - $padding;
      $dstH = (int)(($dstW/$srcW)*$srcH);
    }
    // if vertically long or equal(square)
    else
    {
      $dstH = $size - $padding;
      $dstW = (int)(($dstH/$srcH)*$srcW);
    }  
  }
  
  $dstX = (int)(($size - $dstW)/2);
  $dstY = (int)(($size - $dstH)/2);
  
  // imagecopyresampled ( resource $dst_image , resource $src_image , int $dst_x , int $dst_y , int $src_x , int $src_y , int $dst_w , int $dst_h , int $src_w , int $src_h )
  imagecopyresampled($im2, $im, $dstX, $dstY, $srcX, $srcY, $dstW, $dstH, $srcW, $srcH);
  
  imagealphablending($im2,false);
  imagesavealpha($im2,true);
  $im = $im2;
  //imagedestroy($im2);
}

?>
