<?php
// TCPDF ライブラリを読み込む
require_once('tcpdf-lib/tcpdf.php');

// TCPDF オブジェクト初期化
$pdf = new TCPDF("L", "mm", "A4", true, "UTF-8");
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->setTitle("タイトル");

// フォント定義
// $GOTHIC = $pdf->addTTFfont('tcpdf-lib/fonts/ipag.ttf');
$pdf->setFont('kozgopromedium', '', 10);

// ページ追加
$pdf->AddPage("P"); // P:Portrait(縦長) L:Landscape(横長)

// 現在時刻の出力
$pdf->Write(4, date("Y年n月j日 H:i"));
$pdf->Ln();


// 表のヘッダー
$pdf->Cell(40, 10, '列1', 1);
$pdf->Cell(40, 10, '列2', 1);
$pdf->Cell(40, 10, '列3', 1);
$pdf->Ln();

// データ行1
$pdf->Cell(40, 10, 'データ1', 1);
$pdf->Cell(40, 10, 'データ2', 1);
$pdf->Cell(40, 10, 'データ3', 1);
$pdf->Ln();

// データ行2
$pdf->Cell(40, 10, 'データ4', 1);
$pdf->Cell(40, 10, 'データ5', 1);
$pdf->Cell(40, 10, 'データ6', 1);
$pdf->Ln();

$html = '
<table border="1" cellspacing="3" cellpadding="4">
    <tr>
        <th>列1</th>
        <th>列2</th>
        <th>列3</th>
    </tr>
    <tr>
        <td>データ1</td>
        <td>データ2</td>
        <td>データ3</td>
    </tr>
    <tr>
        <td>データ4</td>
        <td>データ5</td>
        <td>データ6</td>
    </tr>
</table>
';

// HTMLを使って表を出力
$pdf->writeHTML($html, true, false, true, false, '');


// ハンコ枠のサイズと位置を設定
$stamp_width = 30; // ハンコ枠の幅
$stamp_height = 30; // ハンコ枠の高さ
$spacing = 10; // 各ハンコ枠間のスペース

// ページの右上に移動
$pageWidth = $pdf->getPageWidth();
$pdf->SetXY($pageWidth - $stamp_width - 10, 10); // ページ右上に最初のハンコ枠を配置

// ハンコ枠を3つ作成
for ($i = 0; $i < 3; $i++) {
    $pdf->Cell($stamp_width, $stamp_height, '', 1, 0, 'C'); // 枠線あり（1）でセルを作成
    if ($i < 2) {
        $pdf->SetX($pdf->GetX() + $spacing); // 次の枠のためにX座標を移動
    }
}

// ブラウザ出力
$pdf->Output("sample-tcpdf-1.pdf", "I");
?>