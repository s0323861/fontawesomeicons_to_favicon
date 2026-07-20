<?php

// fontawesomeのアイコン一覧を表示させる
$fp = fopen('_variables.scss', 'r');

if ($fp){
    $cnt = 0;
    while (!feof($fp)) {
        $buffer = fgets($fp);
        $strbuf = explode(":", $buffer);
        // 文字列の比較をしてから抽出する
        if(strstr($strbuf[0], 'fa-var-')){
            $arrybuf[$cnt]  = substr($strbuf[0], 8, strlen($strbuf[0]) - 8);

            if($cnt == 0){
                // 先頭のボタンにはチェックを付ける（Bootstrap 5のボタンラジオ仕様）
                $tag_fa = "<input type=\"radio\" class=\"btn-check\" name=\"btn\" id=\"btn-" . $arrybuf[$cnt] . "\" value=\"" . $arrybuf[$cnt] . "\" autocomplete=\"off\" checked>\n";
                $tag_fa .= "<label class=\"btn btn-outline-secondary m-1 fa-icon-label\" for=\"btn-" . $arrybuf[$cnt] . "\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" data-bs-title=\"" . $arrybuf[$cnt] . "\"><i class=\"fa fa-" . $arrybuf[$cnt] . " fa-fw\"></i>" . "<span style=\"display:none;\">" . $arrybuf[$cnt] . "</span></label>\n";
            }else{
                // faのボタン（タグ）を作成する
                $tag_fa .= "<input type=\"radio\" class=\"btn-check\" name=\"btn\" id=\"btn-" . $arrybuf[$cnt] . "\" value=\"" . $arrybuf[$cnt] . "\" autocomplete=\"off\">\n";
                $tag_fa .= "<label class=\"btn btn-outline-secondary m-1 fa-icon-label\" for=\"btn-" . $arrybuf[$cnt] . "\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" data-bs-title=\"" . $arrybuf[$cnt] . "\"><i class=\"fa fa-" . $arrybuf[$cnt] . " fa-fw\"></i>" . "<span style=\"display:none;\">" . $arrybuf[$cnt] . "</span></label>\n";
            }

            $cnt = $cnt + 1;

        // versionの取得
        }elseif(strstr($strbuf[0], 'fa-version')){
            $version = substr($strbuf[1], $of = strpos($strbuf[1], '"') + 1, strrpos($strbuf[1], '"') - $of);
        }
    }
}

fclose($fp);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Font Awesomeのアイコンからファビコン(favicon)を作成するツールです。色、サイズを選択して変換可能です。This online tool is to create favicon files from Font Awesome Icons.">
<meta name="keywords" content="font awesome,変換,favicon,ICO,free,converter">
<meta name="author" content="向井聡">
<title>Font Awesomeのアイコンからfaviconを作成します - Use Font Awesome Icon As Favicon</title>
  <link rel="shortcut icon" href="./favicon.ico">
  <!-- Bootstrap 5 CSS (CDN経由の例。ローカル配置の場合はパスを変更してください) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/class/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
  
  <style type="text/css">
  body { padding-top: 80px; }
  /* 検索等で非表示にするためのクラス */
  .d-none-important { display: none !important; }
  </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top border-bottom">
    <div class="container">
      <a href="./" class="navbar-brand"><i class="fa fa-wrench"></i> Faviconツクール</a>
    </div>
  </nav>
</header>

<div class="container">

  <div class="row my-4">
    <div class="col-12">
      <h1 class="border-bottom pb-2">
        <i class="fa fa-flask"></i> Use Font Awesome Icon As Favicon<br>
        <small class="text-muted fs-5">Font Awesome Iconsからfaviconを作成します！</small>
      </h1>
    </div>
  </div>

  <!-- Forms ================================================== -->
  <form action="./download.php" method="post">
    <fieldset>
      <div class="row">

        <div class="col-md-6 mb-4">
          <div class="card card-body bg-light">
            <div class="mb-3">
              <label for="faSearch" class="form-label font-weight-bold">
                faviconにするアイコンを1つ選択してください。<br>
                <span class="text-muted">(Please choose an icon below that converts to a favicon.)</span>
              </label>

              <div class="input-group mb-3">
                  <span class="input-group-text" id="basic-addon"><i class="fa fa-filter"></i></span>
                  <input type="text" name="faSearch" id="faSearch" class="form-control" aria-describedby="basic-addon" placeholder="Search icons">
              </div>

              <div id="fa" class="d-flex flex-wrap layout-buttons">
                <?php echo $tag_fa; ?>
              </div>
            </div>
            <p class="mb-0 text-secondary">version: <?php echo $version; ?></p>
          </div>
        </div>

        <div class="col-md-6 mb-4">
          <div class="card card-body bg-light mb-3">
            <div class="mb-3">
              <label for="select-size" class="form-label">サイズ(size)</label>
              <select class="form-select" name="size" id="select-size">
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

            <div class="mb-3">
              <label for="color-text" class="form-label">色(color)</label>
              <div class="input-group">
                <!-- テキスト入力欄 -->
                <input type="text" id="color-text" name="color" value="#000000" class="form-control" placeholder="#000000" maxlength="7">
                <!-- カラーピッカー（ボタンの代わり） -->
                <input type="color" id="color-picker" value="#000000" class="form-control form-control-color" style="width: 50px; max-width: 50px; padding: 6px;">
              </div>
            </div>

            <div class="mb-3">
              <label for="select-format" class="form-label">形式(format)</label>
              <select class="form-select" name="format" id="select-format">
                <option>ico</option>
                <option>png</option>
              </select>
            </div>
          </div>

          <div class="text-center">
              <button type="submit" class="btn btn-primary btn-lg"><i class="fa fa-download"></i> ダウンロード(Download)</button>
          </div>
        </div>

      </div>
    </fieldset>
  </form>

  <hr>

  <!-- Footer -->
  <footer class="py-3">
    <div class="row">
      <div class="col-12">
        <p class="text-muted">
          Copyright (C) 2021 <a href="https://tsukuba42195.sakura.ne.jp.jp/">Akira Mukai</a><br>
          Released under the MIT license<br>
          <a href="http://opensource.org/licenses/mit-license.php" target="_blank">http://opensource.org/licenses/mit-license.php</a>
        </p>
      </div>
    </div>
  </footer>

</div>

<!-- Bootstrap 5 JavaScript Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
  // Bootstrap 5 のツールチップ初期化 (Vanilla JS)
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });

});

// アイコン検索機能のVanilla JS化
document.getElementById('faSearch').addEventListener('keyup', function() {
  var filter = this.value.toLowerCase();
  var labels = document.querySelectorAll('#fa .fa-icon-label');
  
  labels.forEach(function(label) {
    var text = label.textContent || label.innerText;
    if (text.toLowerCase().indexOf(filter) > -1) {
      label.classList.remove('d-none-important');
      // ラジオボタン本体（直前の要素）も連動させる
      if(label.previousElementSibling && label.previousElementSibling.classList.contains('btn-check')) {
        label.previousElementSibling.classList.remove('d-none-important');
      }
    } else {
      label.classList.add('d-none-important');
      if(label.previousElementSibling && label.previousElementSibling.classList.contains('btn-check')) {
        label.previousElementSibling.classList.add('d-none-important');
      }
    }
  });
});

document.addEventListener("DOMContentLoaded", function() {
  // Bootstrap 5 のツールチップ初期化
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });

  // カラー連動処理
  var colorText = document.getElementById('color-text');
  var colorPicker = document.getElementById('color-picker');

  // ピッカーで色を変えたらテキストに反映
  colorPicker.addEventListener('input', function() {
    colorText.value = colorPicker.value;
  });

  // テキストを直接書き換えたらピッカーに反映（有効なヘキサカラーの場合のみ）
  colorText.addEventListener('input', function() {
    var val = colorText.value;
    // #付きの6桁の16進数チェック
    if (/^#[0-9A-F]{6}$/i.test(val)) {
      colorPicker.value = val;
    }
  });
});
</script>

</body>
</html>
