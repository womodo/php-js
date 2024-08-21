<?php
// dompdfの読み込み
require_once("./lib/dompdf/autoload.inc.php");

use Dompdf\Dompdf;
use Dompdf\Options;


// DOMPDFの設定
$options = new Options();
$options->set('isHtml5ParserEnabled', true);    // HTML5対応
$options->set('isRemoteEnabled', true);         // 外部のリソース(画像、CSSなど)を使用する場合に必要

$dompdf = new Dompdf($options);

$date = date("Y年 n月 j日");

// PDFにする内容をHTMLで記述
$html = <<< EOM
<html>
  <head>
    <meta charset="utf-8">
    <style>
    html {
        font-family: ipagp;
    }
    @page {
        margin: 6mm;
    }
    body {
        margin: 0;
    }
    table {
        border-collapse: collapse;
    }
    #main {
        border: 0.1mm solid #000;
        padding-top: 2mm;
        padding-left: 5mm;
        padding-right: 5mm;
    }
    #deviceTbl td {
        border: 0.1mm solid #000;
        text-align: center;
    }
    #planTbl td {
        border: 0.1mm solid #000;
    }
    </style>
  </head>
  <body>
    <div id="main">
        <div style="text-align:right;">発行日 ： $date</div>
        <div style="text-align:center; padding-bottom:5mm;">
            <span style="font-size:1.2em; border:1px solid #000; display:inline-block; width:60mm; padding:2;">計 画 工 事 票</span>
        </div>
        <div style="padding-bottom:5mm;">
            <table>
                <tr>
                    <td style="width:26mm;">分類</td>
                    <td>第２工場</td>
                </tr>
                <tr>
                    <td style="vertical-align:top;">工事件名</td>
                    <td>第二工場 1課 3P92 DN60014(PN40) 主軸関係<br>
                        設備待機中に運転準備が落ちた</td>
                </tr>
                <tr>
                    <td>標準書番号</td>
                    <td></td>
                </tr>
            </table>
        </div>
        <div style="padding-bottom:5mm;">
            <table>
                <tr>
                    <td style="width:26mm;">前回実績日</td>
                    <td style="width:20mm; text-align:right;">2023</td>
                    <td style="width:6mm; text-align:right;">年</td>
                    <td style="width:12mm; text-align:right;">8</td>
                    <td style="width:6mm; text-align:right;">月</td>
                    <td style="width:12mm; text-align:right;">10</td>
                    <td style="width:6mm; text-align:right;">日</td>
                </tr>
                <tr>
                    <td style="width:26mm;">今回予定日</td>
                    <td style="width:20mm; text-align:right;">2024</td>
                    <td style="width:6mm; text-align:right;">年</td>
                    <td style="width:12mm; text-align:right;">8</td>
                    <td style="width:6mm; text-align:right;">月</td>
                    <td style="width:12mm; text-align:right;">10</td>
                    <td style="width:6mm; text-align:right;">日</td>
                </tr>
                <tr>
                    <td style="width:26mm;">計画着工日</td>
                    <td style="width:20mm; text-align:right;">2024</td>
                    <td style="width:6mm; text-align:right;">年</td>
                    <td style="width:12mm; text-align:right;">8</td>
                    <td style="width:6mm; text-align:right;">月</td>
                    <td style="width:12mm; text-align:right;">12</td>
                    <td style="width:6mm; text-align:right;">日</td>
                </tr>
                <tr>
                    <td style="width:26mm;">計画完了日</td>
                    <td style="width:20mm; text-align:right;"></td>
                    <td style="width:6mm; text-align:right;">年</td>
                    <td style="width:12mm; text-align:right;"></td>
                    <td style="width:6mm; text-align:right;">月</td>
                    <td style="width:12mm; text-align:right;"></td>
                    <td style="width:6mm; text-align:right;">日</td>
                    <td style="width:24mm;"></td>
                    <td style="width:26mm;">工事周期</td>
                    <td>（</td>
                    <td style="width:30mm;">1年</td>
                    <td>）</td>
                </tr>
            </table>
        </div>

        <div style="padding-bottom:5mm;">
            <table id="deviceTbl" style="width:100%;">
                <tr>
                    <td rowspan="2" style="vertical-align:top;">No</td>
                    <td colspan="3">機器</td>
                    <td colspan="2">大項目</td>
                    <td>中項目</td>
                    <td>小項目</td>
                    <td>種別</td>
                </tr>
                <tr>
                    <td>初期値</td>
                    <td>注意値</td>
                    <td>限界値</td>
                    <td>測定単位</td>
                    <td>判定</td>
                    <td>今回測定値</td>
                    <td>前回測定値</td>
                    <td>測定日</td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align:top;">01</td>
                    <td colspan="3" style="height:6mm;"></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height:6mm;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align:top;">02</td>
                    <td colspan="3" style="height:6mm;"></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height:6mm;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td rowspan="2" style="vertical-align:top;">03</td>
                    <td colspan="3" style="height:6mm;"></td>
                    <td colspan="2"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr>
                    <td style="height:6mm;"></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>
    </div>
    <div>
        <table id="planTbl">
            <tr>
                <td rowspan="2" style="padding-left:2mm; padding-right:2mm; line-height:1;">工事<br>計画</td>
                <td style="width:20mm; text-align:center;">作成</td>
                <td style="width:20mm; text-align:center;">審査</td>
                <td style="width:20mm; text-align:center;">承認</td>
                <td rowspan="2" style="width:48.4mm; border:0;">
                <td rowspan="2" style="padding-left:2mm; padding-right:2mm; line-height:1;">工事<br>結果</td>
                <td style="width:20mm; text-align:center;"></td>
                <td style="width:20mm; text-align:center;">審査</td>
                <td style="width:20mm; text-align:center;">承認</td>
            </tr>
            <tr>
                <td style="height:20mm;"></td>
                <td style="height:20mm;"></td>
                <td style="height:20mm;"></td>
                <td style="height:20mm;"></td>
                <td style="height:20mm;"></td>
                <td style="height:20mm;"></td>
            </tr>
        </table>
    </div>
  </body>
</html>
EOM;

// HTMLをDOMPDFに読み込む
$dompdf->loadHtml($html);
// 用紙のサイズと向きを設定 (A4サイズ、縦向き)
$dompdf->setPaper('A4', 'portrait');
// PDFをレンダリング
$dompdf->render();
// PDFをブラウザに表示
$dompdf->stream("smaple.pdf", array("Attachment" => 0));
?>