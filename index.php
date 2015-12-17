<?php

// fontawesomeのアイコン一覧を表示させる
$fp = fopen('_variables.scss', 'r');

if ($fp){
  if (flock($fp, LOCK_SH)){
  $cnt = 0;
    while (!feof($fp)) {
      $buffer = fgets($fp);
      $strbuf = split(":", $buffer);
      // 文字列の比較をしてから抽出する
      if(strstr($strbuf[0], 'fa-var-')){
        $arrybuf[$cnt]  = substr($strbuf[0], 8, strlen($strbuf[0]) - 8);

        if($cnt == 0){

        // 先頭のボタンにはチェックを付ける
        $tag_fa = "<label class=\"btn btn-default active\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . $arrybuf[$cnt] . "\"><input type=\"radio\" name=\"btn\" value=\"" . $arrybuf[$cnt] . "\" autocomplete=\"off\" checked><i class=\"fa fa-" . $arrybuf[$cnt] . " fa-fw\"></i>" . "<span style=\"display:none;\">" . $arrybuf[$cnt] . "</span></label>\n";

        }else{

        // faのボタン（タグ）を作成する
        $tag_fa .= "<label class=\"btn btn-default\" data-toggle=\"tooltip\" data-placement=\"top\" data-original-title=\"" . $arrybuf[$cnt] . "\"><input type=\"radio\" name=\"btn\" value=\"" . $arrybuf[$cnt] . "\" autocomplete=\"off\"><i class=\"fa fa-" . $arrybuf[$cnt] . " fa-fw\"></i>" . "<span style=\"display:none;\">" . $arrybuf[$cnt] . "</span></label>\n";

        }

        $cnt = $cnt + 1;

      // versionの取得
      }elseif(strstr($strbuf[0], 'fa-version')){
        $version = substr($strbuf[1], $of = strpos($strbuf[1], '"') + 1, strrpos($strbuf[1], '"') - $of);
      }
    }
    flock($fp, LOCK_UN);
  }else{
    // print('ファイルロックに失敗しました');
  }
}

fclose($fp);

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Font Awesomeのアイコンからファビコン(favicon)を作成するツールです。色、サイズを選択して変換可能です。This online tool is to create favicon files from Font Awesome Icons.">
<meta name="keywords" content="font awesome,変換,favicon,ICO,free,converter">
<meta name="author" content="向井聡">
<title>Font Awesomeのアイコンからfaviconを作成します - Use Font Awesome Icon As Favicon</title>
  <link rel="shortcut icon" href="./favicon.ico">
  <link rel="stylesheet" href="./css/bootstrap.min.css">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="./css/bootstrap-colorpicker.min.css">
  <style type="text/css">
  body { padding-top: 80px; }
  @media ( min-width: 768px ) {
    #banner {
      min-height: 300px;
      border-bottom: none;
    }
    .bs-docs-section {
      margin-top: 8em;
    }
    .bs-component {
      position: relative;
    }
    .bs-component .modal {
      position: relative;
      top: auto;
      right: auto;
      left: auto;
      bottom: auto;
      z-index: 1;
      display: block;
    }
    .bs-component .modal-dialog {
      width: 90%;
    }
    .bs-component .popover {
      position: relative;
      display: inline-block;
      width: 220px;
      margin: 20px;
    }
    .nav-tabs {
      margin-bottom: 15px;
    }
  }
  </style>

  <!--[if lt IE 9]>
    <script src="//oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="//oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

</head>
<body>

<header>
  <div class="navbar navbar-default navbar-fixed-top">
    <div class="container">
      <div class="navbar-header">
        <a href="./" class="navbar-brand"><i class="fa fa-wrench"></i> お役立ちツール</a>
        <button class="navbar-toggle" type="button" data-toggle="collapse" data-target="#navbar-main">
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
      </div>
    </div>
  </div>
</header>

<div class="container">

  <div class="row">

    <!-- Blog Entries Column -->
    <div class="col-lg-12">

    <h1 class="page-header">
    <i class="fa fa-flask"></i> Use Font Awesome Icon As Favicon<br>
    <small>Font Awesome Iconsからfaviconを作成します！</small>
    </h1>

    </div>

  </div>

  <!-- Forms
  ================================================== -->
  <div class="row">

    <div class="col-md-6">

      <form action="./download.php" method="post">
      <fieldset>

      <div class="well bs-component">

        <div class="form-group">

          <label for="select" class="control-label">faviconにするアイコンを1つ選択してください。<br>(Please choose an icon below that converts to a favicon.)</label>

          <div class="form-group">
              <div class="input-group">
                  <span class="input-group-addon" id="basic-addon"><i class="fa fa-filter"></i></span>
                  <input type="text" name="faSearch" id="faSearch" class="form-control" aria-describedby="basic-addon" placeholder="Search icons">
              </div>
          </div>

          <div class="btn-group" data-toggle="buttons" id="fa">

<?php

  echo $tag_fa;

?>

          </div>

        </div>

        <p><?php echo "version: " . $version; ?></p>

      </div>

    </div>

    <div class="col-md-6">

      <div class="well bs-component">

        <div class="form-group">
          <label for="select" class="control-label">サイズ(size)</label>
            <select class="form-control" name="size" id="select">
              <option>16</option>
              <option>24</option>
              <option>32</option>
              <option>48</option>
              <option>64</option>
              <option>96</option>
              <option>128</option>
              <option>256</option>
            </select>
        </div>

        <div class="form-group">
          <label for="select" class="control-label">色(color)</label>
            <div class="input-group demo2">
              <input type="text" name="color" value="#000000" class="form-control" />
              <span class="input-group-addon"><i></i></span>
            </div>
        </div>

        <div class="form-group">
          <label for="select" class="control-label">形式(format)</label>
            <select class="form-control" name="format" id="select">
              <option>ico</option>
              <option>png</option>
            </select>
        </div>
      </div>

      <div class="form-group text-center">
          <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> ダウンロード(Download)</button>
      </div>

      </fieldset>
      </form>

    </div>

  </div>

  <hr>

  <!-- Footer -->
  <footer>
  <div class="row">
    <div class="col-lg-12">
    <p>
    Copyright (C) 2015 <a href="http://tsukuba42195.top/">Akira Mukai</a><br>
    Released under the MIT license<br>
    <a href="http://opensource.org/licenses/mit-license.php" target="_blank">http://opensource.org/licenses/mit-license.php</a>
    </p>
    </div>
    <!-- /.col-lg-12 -->
  </div>
  <!-- /.row -->
  </footer>

</div>

<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
<script src="./js/bootstrap.min.js"></script>
<script src="./js/bootstrap-colorpicker.min.js"></script>
<script>
$(function(){
  $('.demo2').colorpicker();
  $('.bs-component [data-toggle="tooltip"]').tooltip();
});

$('#faSearch').keyup(function(){
  if (!$(this).val()) {
    // 検索文字列無し
    $('#fa label').show();
  } else {
    // 検索文字列有り
    $('#fa label').hide();
    $('#fa label:contains(' + this.value + ')').show();
  }
});
</script>

</body>
</html>
