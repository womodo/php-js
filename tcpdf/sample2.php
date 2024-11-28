<?php
require_once('tcpdf-lib/tcpdf.php');

// Create new PDF document
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Planning Work Ticket');
$pdf->SetSubject('PDF Document');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// Add a page
$pdf->AddPage();

// Set font
// $pdf->SetFont('dejavusans', '', 12);
$pdf->setFont('kozgopromedium', '', 12);

// Add a title
$pdf->Cell(0, 10, '計画工事票', 0, 1, 'C');

// Add some text
$pdf->Ln(10);
$pdf->Cell(0, 10, '発行日: 2024年8月20日', 0, 1);

// Draw a table
$tbl = '
<table cellspacing="0" cellpadding="1" border="1">
    <tr>
        <th>分類</th>
        <th>工事件名</th>
        <th>標準書番号</th>
    </tr>
    <tr>
        <td>第2工場</td>
        <td>第ニ主軸関係 3P92 DN60014 (PN40) 主軸関係</td>
        <td>...</td>
    </tr>
</table>';
$pdf->writeHTML($tbl, true, false, false, false, '');

// Close and output PDF document
$pdf->Output('planning_work_ticket.pdf', 'I'); // 'I' sends the file inline to the browser

?>