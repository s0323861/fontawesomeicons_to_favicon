<?php

$fa = $_POST["btn"];
$fa = urldecode($fa);

// PHP Font Awesome to PNG
include("icons.data.php");

// 3つのフォントファイルをスタイル別に定義
// サーバー上の実際の配置パス・ファイル名に合わせて調整してください
$fontFiles = [
    'solid'   => './fa-solid-900.ttf',
    'regular' => './fa-regular-400.ttf',
    'brands'  => './fa-brands-400.ttf'
];

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

  // 【変更点】HTMLエンティティ（&#xe494;など）をUTF-8文字列にデコード
  $text = html_entity_decode($iParam['code'], ENT_QUOTES, 'UTF-8');

  // 【プランB変更点】アイコンのstyle属性（solid/regular/brands）を取得
  $iconStyle = isset($iParam['style']) ? $iParam['style'] : 'solid';

  // 【プランB変更点】該当するフォントファイルを自動選択（存在しないスタイル名はsolidにフォールバック）
  $font = isset($fontFiles[$iconStyle]) ? $fontFiles[$iconStyle] : $fontFiles['solid'];
  
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

  // 【重要】目的のアイコンを生成したら、ここでループを強制終了してパスを保持する
  break;
}

// フォーマット(ico or png)
$format = $_POST["format"];

if (file_exists($fileName)) {
    if($format == "ico"){
        $destination = $outputDir . '/' . $fa . '.ico';
        
        // 【外部ライブラリ不使用】PNGデータをダイレクトにICOバイナリへラップする
        $png_data = file_get_contents($fileName);
        $png_size = strlen($png_data);
        
        // ICOファイルのヘッダーをバイナリで構築 (1アイコン、幅/高は $outputSize)
        // 幅・高さが256以上の場合はバイナリ上 0 を指定する仕様
        $ico_w = ($outputSize >= 256) ? 0 : $outputSize;
        $ico_h = ($outputSize >= 256) ? 0 : $outputSize;
        
        $ico_header = pack("vvv", 0, 1, 1); // Reserved(0), Type(1=ICO), Count(1)
        $ico_dir    = pack("CCCCvvVV", 
            $ico_w,         // Width
            $ico_h,         // Height
            0,              // Color Count
            0,              // Reserved
            1,              // Color Planes
            32,             // Bits per pixel (32bit)
            $png_size,      // Size of PNG data
            22              // Offset of PNG data (Header 6 bytes + Dir 16 bytes = 22)
        );
        
        // ヘッダー + ディレクトリ構造 + PNG本体 を結合してICOとして保存
        file_put_contents($destination, $ico_header . $ico_dir . $png_data);
        
        // 元ネタのPNGファイルはここで削除
        unlink($fileName); 
    } else {
        $destination = $fileName;
    }
} else {
    die("エラー: 元となるPNG画像（" . $fileName . "）が見つかりません。");
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

// ダウンロード後に最終出力ファイルを削除する(delete files)
unlink($destination);



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
