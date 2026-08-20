<?php
// 新しいアイコン定義データを読み込む
include("icons.data.php");

$tag_fa = "";
$cnt = 0;

// 配列からBootstrap 5のボタンラジオ仕様のタグを生成する
foreach ($icons as $key => $param) {
    // ===================================================
    // 【非表示マップ】豆腐化（Pro版限定など）アイコンのリスト
    // ===================================================
    $skip_icons = [
        'solid-chart-diagram',
        'solid-comment-nodes',
        'brands-css',
        'solid-file-fragment',
        'solid-file-half-dashed',
        'brands-files-pinwheel',
        'solid-hexagon-nodes',
        'solid-hexagon-nodes-bolt',
        'solid-square-binary',
        'brands-square-bluesky',
        // 今後、豆腐化するものを見つけたらここに「'キー名',」の形で自由に追加できます
    ];

    // もし配列内にキーが存在したら、画面への出力をスキップする
    if (in_array($key, $skip_icons)) {
        continue;
    }

    // 検索やJavaScriptでの判別のために、data-style や data-code も埋め込んでおくと便利です
    $checked = ($cnt == 0) ? "checked" : "";
    
    // アイコンのクラス名（Font Awesome 5以降は fa-xxx ではなく solid/regular などの考慮が必要な場合がありますが、
    // 現行の表示互換を維持するため $param['name'] をベースにします）
    $icon_name = $param['name'];

    // スタイル名（solid等）を取得し、プレフィックス（fa-solid等）を作る
    $style = isset($param['style']) ? $param['style'] : 'solid';
    $fa_prefix = "fa-" . $style;

    $tag_fa .= "<input type=\"radio\" class=\"btn-check\" name=\"btn\" id=\"btn-" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "\" value=\"" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "\" data-icon-name=\"" . htmlspecialchars($icon_name, ENT_QUOTES, 'UTF-8') . "\" data-icon-style=\"" . htmlspecialchars($fa_prefix, ENT_QUOTES, 'UTF-8') . "\" autocomplete=\"off\" " . $checked . ">\n";
    $tag_fa .= "<label class=\"btn btn-outline-secondary m-1 fa-icon-label\" for=\"btn-" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "\" data-bs-toggle=\"tooltip\" data-bs-placement=\"top\" data-bs-title=\"" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "\"><i class=\"" . htmlspecialchars($fa_prefix, ENT_QUOTES, 'UTF-8') . " fa-" . htmlspecialchars($icon_name, ENT_QUOTES, 'UTF-8') . " fa-fw\"></i>" . "<span style=\"display:none;\">" . htmlspecialchars($key, ENT_QUOTES, 'UTF-8') . "</span></label>\n";

    $cnt++;
}

// バージョン情報を固定、または定義データ側から持たせる（ここでは仮に 6.x と記述）
$version = "6.x (Custom Data)"; 
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Font Awesomeのアイコンからファビコン(favicon)を作成するツールです。色、サイズを選択して変換可能です。This online tool is to create favicon files from Font Awesome Icons.">
<meta name="keywords" content="font awesome,変換,favicon,ICO,free,converter">
<meta name="author" content="向井聡">

<!-- OGP -->
<meta property="og:title"
    content="Faviconツクール">

<meta property="og:description"
    content="Font Awesomeのアイコンからfaviconを作成します。">

<meta property="og:type"
    content="website">

<meta property="og:url"
    content="https://tsukuba42195.sakura.ne.jp/fontawesome_to_favicon/">

<meta property="og:image"
    content="https://tsukuba42195.sakura.ne.jp/fontawesome_to_favicon/ogp.png">

<meta property="og:site_name"
    content="Faviconツクール">

<meta property="og:locale"
    content="ja_JP">

<meta name="twitter:card"
    content="summary_large_image">

<meta name="twitter:title"
    content="Faviconツクール">

<meta name="twitter:description"
    content="Font Awesomeのアイコンからfaviconを作成します。">

<meta name="twitter:image"
    content="https://tsukuba42195.sakura.ne.jp/fontawesome_to_favicon/ogp.png">

<title>Faviconツクール - Font Awesomeのアイコンからfaviconを作成します（Use Font Awesome Icon As Favicon）</title>
  <link rel="shortcut icon" href="./favicon.ico">
  <!-- Bootstrap 5 CSS -->
  <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.6.0/css/all.min.css">
  
  <style type="text/css">
  body { padding-top: 80px; }
  /* 検索等で非表示にするためのクラス */
  .d-none-important { display: none !important; }

  /* アイコン一覧のスクロールエリア */
  .icon-scroll-area {
    max-height: 350px;
    overflow-y: auto;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 10px;
    background-color: #fff;
  }
  
  /* ラジオボタン選択時の視認性アップ（はっきりとしたアクティブ色に） */
  .btn-check:checked + .fa-icon-label {
    background-color: #0d6efd !important;
    border-color: #0d6efd !important;
    color: #fff !important;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
  }

  /* リアルタイムプレビュー用の背景切り替えボックス */
  .preview-box {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 96px;
    height: 96px;
    font-size: 3rem;
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    transition: background-color 0.2s, color 0.2s;
  }
  .bg-preview-light { background-color: #ffffff; color: #000; }

  /* HTMLコードプレビュー用のスタイル */
  .code-preview-block {
    background-color: #212529;
    color: #f8f9fa;
    font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
    font-size: 0.85rem;
    padding: 0.75rem;
    border-radius: 0.375rem;
    word-break: break-all;
    white-space: pre-wrap;
  }
  </style>
</head>
<body>

<header>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top border-bottom">
    <div class="container">
      <a href="./" class="navbar-brand"><i class="fa-solid fa-wrench"></i> Faviconツクール</a>
    </div>
  </nav>
</header>

<div class="container">

  <div class="row my-4">
    <div class="col-12">
      <h1 class="border-bottom pb-2">
        <i class="fa-solid fa-flask"></i> Use Font Awesome Icon As Favicon<br>
        <small class="text-muted fs-5">Font Awesome Iconsからfaviconを作成します！</small>
      </h1>
    </div>
  </div>

  <!-- Forms ================================================== -->
  <form action="./download.php" method="post">
    <fieldset>
      <div class="row">

        <!-- 左カラム：アイコン選択 -->
        <div class="col-md-6 mb-4">
          <div class="card card-body bg-light h-100">
            <div class="mb-3">
              <label for="faSearch" class="form-label font-weight-bold fw-bold">
                faviconにするアイコンを1つ選択してください。<br>
                <span class="text-muted small">(Please choose an icon below that converts to a favicon.)</span>
              </label>

              <!-- 上部固定エリア：検索窓 -->
              <div class="p-3 bg-white border rounded mb-3 shadow-sm">
                <div class="input-group">
                  <span class="input-group-text" id="basic-addon"><i class="fa fa-filter"></i></span>
                  <input type="text" name="faSearch" id="faSearch" class="form-control" aria-describedby="basic-addon" placeholder="Search icons">
                </div>
              </div>

              <!-- 下部：スクロールするアイコン一覧 -->
              <div class="icon-scroll-area">
                <div id="fa" class="d-flex flex-wrap layout-buttons">
                  <?php echo $tag_fa; ?>
                </div>
              </div>
            </div>
            <p class="mb-0 text-secondary mt-auto">version: <?php echo $version; ?></p>
          </div>
        </div>

        <!-- 右カラム：サイズ・色・ダウンロード ＋ HTML生成エリア（改善） -->
        <div class="col-md-6 mb-4">
          <div class="card card-body bg-light mb-3 h-100 justify-content-between">
            <div>
              <!-- 選択中のアイコンプレビュー表示エリア -->
              <div class="bg-white border rounded shadow-sm p-3 mb-4 text-center">
                <div class="fw-bold mb-2">Preview</div>
                <div id="icon-preview" class="preview-box bg-preview-light shadow-sm mx-auto">
                  <i class="fa fa-question"></i>
                </div>
                <span class="small text-muted d-block mt-2" id="preview-name">-</span>
              </div>

              <div class="mb-3">
                <label for="select-size" class="form-label fw-bold">サイズ(size)</label>
                <select class="form-select" name="size" id="select-size">
                  <option>16</option>
                  <option>24</option>
                  <option selected>32</option>
                  <option>48</option>
                  <option>64</option>
                  <option>96</option>
                  <option>128</option>
                  <option>256</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="color-text" class="form-label fw-bold">色(color)</label>
                <div class="input-group">
                  <!-- テキスト入力欄 -->
                  <input type="text" id="color-text" name="color" value="#000000" class="form-control" placeholder="#000000" maxlength="7">
                  <!-- カラーピッカー -->
                  <input type="color" id="color-picker" value="#000000" class="form-control form-control-color" style="width: 50px; max-width: 50px; padding: 6px;">
                </div>
                <div class="form-check form-switch mt-3">
                  <input class="form-check-input" type="checkbox" role="switch" id="invert-colors" name="invert" value="1">
                  <label class="form-check-label fw-bold" for="invert-colors">色を反転</label>
                  <small class="text-muted d-block">白いアイコン＋選択色の背景に切り替えます。</small>
                </div>
              </div>

              <div class="mb-3">
                <label for="select-format" class="form-label fw-bold">形式(format)</label>
                <select class="form-select" name="format" id="select-format">
                  <option selected>ico</option>
                  <option>png</option>
                </select>
              </div>

              <div class="text-center my-4">
                  <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold"><i class="fa fa-download"></i> ダウンロード(Download)</button>
              </div>
            </div>

            <!-- 新設：HTMLコード自動生成エリア -->
            <div class="border-top pt-3 mt-2">
              <label class="form-label fw-bold text-dark d-flex justify-content-between align-items-center">
                <span><i class="fa fa-code"></i> 導入用HTMLコード</span>
                <button type="button" class="btn btn-sm btn-outline-primary py-1 px-2" id="copy-code-btn">
                  <i class="fa fa-clipboard"></i> コピー
                </button>
              </label>
              <div id="html-code-box" class="code-preview-block"></div>
              <small class="text-muted d-block mt-1">※生成したfaviconをHTMLの &lt;head&gt; 内に記述する際にご使用ください。</small>
            </div>

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
          Copyright (C) 2026 <a href="https://tsukuba42195.sakura.ne.jp.jp/">Akira Mukai</a> | <a href="https://github.com/s0323861/fontawesomeicons_to_favicon" target="_blank">GitHub</a><br>
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
  // Bootstrap 5 のツールチップ初期化
  var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });

  // 要素の取得
  var colorText = document.getElementById('color-text');
  var colorPicker = document.getElementById('color-picker');
  var invertColors = document.getElementById('invert-colors');
  var iconPreview = document.getElementById('icon-preview');
  var selectFormat = document.getElementById('select-format');
  var selectSize = document.getElementById('select-size');
  var htmlCodeBox = document.getElementById('html-code-box');
  var copyCodeBtn = document.getElementById('copy-code-btn');
  var previewNameSpan = document.getElementById('preview-name');

  // プレビューの描画色と背景色を同期する関数
  function syncPreviewColor() {
    if (invertColors.checked) {
      iconPreview.style.color = '#ffffff';
      iconPreview.style.backgroundColor = colorText.value;
    } else {
      iconPreview.style.color = colorText.value;
      iconPreview.style.backgroundColor = '#ffffff';
    }
  }

  // --- 新設：HTMLコードを動的に生成する関数 ---
  function generateHtmlCode() {
    var format = selectFormat.value;
    var size = selectSize.value;
    var code = "";

    if (format === "ico") {
      code = '<link rel="shortcut icon" href="./favicon.ico">';
    } else {
      code = '<link rel="icon" type="image/png" sizes="' + size + 'x' + size + '" href="./favicon-' + size + 'x' + size + '.png">';
    }
    htmlCodeBox.textContent = code;
  }

  colorPicker.addEventListener('input', function() {
    colorText.value = colorPicker.value;
    syncPreviewColor();
  });

  colorText.addEventListener('input', function() {
    var val = colorText.value;
    if (/^#[0-9A-F]{6}$/i.test(val)) {
      colorPicker.value = val;
      syncPreviewColor();
    }
  });

  invertColors.addEventListener('change', syncPreviewColor);

  // フォーマットやサイズが変わったらHTMLコードを再生成
  selectFormat.addEventListener('change', generateHtmlCode);
  selectSize.addEventListener('change', generateHtmlCode);

  // リアルタイムアイコンプレビュー
  function updatePreview() {
    var checkedRadio = document.querySelector('input[name="btn"]:checked');
    if (checkedRadio) {
      var iconName = checkedRadio.getAttribute('data-icon-name') || checkedRadio.value;
      // 追加：PHP側で埋め込んだ fa-solid などのスタイルクラスを取得
      var iconStyle = checkedRadio.getAttribute('data-icon-style') || 'fa-solid';
      var keyName = checkedRadio.value;
      
      // クラス名にスタイルを適用
      iconPreview.innerHTML = '<i class="' + iconStyle + ' fa-' + iconName + '"></i>';
      previewNameSpan.textContent = keyName;
    }
  }

  document.getElementById('fa').addEventListener('change', function() {
    updatePreview();
  });

  // --- 新設：コピーボタンの処理 ---
  copyCodeBtn.addEventListener('click', function() {
    var codeText = htmlCodeBox.textContent;
    navigator.clipboard.writeText(codeText).then(function() {
      // コピー成功時のフィードバック
      var originalText = copyCodeBtn.innerHTML;
      copyCodeBtn.innerHTML = '<i class="fa fa-check"></i> Copied!';
      copyCodeBtn.classList.remove('btn-outline-primary');
      copyCodeBtn.classList.add('btn-success');
      
      setTimeout(function() {
        copyCodeBtn.innerHTML = originalText;
        copyCodeBtn.classList.remove('btn-success');
        copyCodeBtn.classList.add('btn-outline-primary');
      }, 2000);
    }).catch(function(err) {
      alert('コピーに失敗しました。お手数ですが手動で選択してコピーしてください。');
    });
  });

  // 初期読み込み時の各種反映
  updatePreview();
  syncPreviewColor();
  generateHtmlCode();
});

// アイコン検索機能
document.getElementById('faSearch').addEventListener('keyup', function() {
  var filter = this.value.toLowerCase();
  var labels = document.querySelectorAll('#fa .fa-icon-label');
  
  labels.forEach(function(label) {
    var text = label.textContent || label.innerText;
    if (text.toLowerCase().indexOf(filter) > -1) {
      label.classList.remove('d-none-important');
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
</script>

</body>
</html>
