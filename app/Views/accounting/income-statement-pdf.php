<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$date_from = $this->request->getGet('date_from');
$date_to = $this->request->getGet('date_to');
$month = $this->request->getGet('month');
$year = $this->request->getGet('year');

$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

// Calculate date range if month/year is provided
if (!empty($month) && !empty($year)) {
    $monthNum = date('m', strtotime($month));
    $date_from = $year . '-' . $monthNum . '-01';
    $date_to = date('Y-m-d', strtotime($date_from . ' +1 month'));
}

// Get cash receipts data
$query = $this->db->query("
    SELECT 
        j.journal_no,
        j.posting_date,
        j.reference_no,
        j.remarks,
        jd.account_code,
        jd.account_name,
        jd.debit_amount,
        jd.credit_amount,
        jd.description,
        jd.cost_center
    FROM tbl_journal j
    INNER JOIN tbl_journal_details jd ON j.journal_id = jd.journal_id
    WHERE j.journal_type = 'Cash Receipt'
    AND j.status = 'Posted'
");

// Add date filters if provided
if (!empty($date_from) && !empty($date_to)) {
    $query = $this->db->query("
        SELECT 
            j.journal_no,
            j.posting_date,
            j.reference_no,
            j.remarks,
            jd.account_code,
            jd.account_name,
            jd.debit_amount,
            jd.credit_amount,
            jd.description,
            jd.cost_center
        FROM tbl_journal j
        INNER JOIN tbl_journal_details jd ON j.journal_id = jd.journal_id
        WHERE j.journal_type = 'Cash Receipt'
        AND j.status = 'Posted'
        AND j.posting_date BETWEEN ? AND ?
        ORDER BY j.posting_date ASC
    ", [$date_from, $date_to]);
}

$results = $query->getResultArray();

// Calculate totals
$totalDebit = 0;
$totalCredit = 0;
foreach ($results as $row) {
    $totalDebit += floatval($row['debit_amount']);
    $totalCredit += floatval($row['credit_amount']);
}

class PDF extends \FPDF {
    function Footer() {
        // Position from bottom
        $this->SetY(-15);
        
        // Centered disclaimer
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 5, 
            'This is a computer-generated document. No signature is required.', 
            0, 0, 'C'
        );
        
        // Page number
        $this->SetXY(-40, -15);
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(30, 5, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF('L', 'mm', 'LEGAL');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Cash Receipts Journal');
$pdf->SetXY(0, 8);

$Y = 4;

// Company Header
$Y = $pdf->GetY() + 4;

$pdf->SetFont('Arial', 'B', 12);
$pdf->SetXY(165, $Y);
$pdf->Cell(30, 4, 'DEPARTMENT OF SCIENCE AND TECHNOLOGY', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetXY(165, $Y);
$pdf->Cell(30, 4, 'FOOD AND NUTRITION RESEARCH INSTITUTE', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 14);
$pdf->SetXY(165, $Y);
$pdf->Cell(30, 6, 'CASH RECEIPTS JOURNAL', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 10);
$pdf->SetXY(165, $Y);
$pdf->Cell(30, 5, 'For the period ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to)), 0, 1, 'C');

$Y = $pdf->GetY() + 8;

// Table Header
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(220, 220, 220);

$pdf->SetXY(15, $Y);
$pdf->Cell(25, 8, 'Journal No', 1, 0, 'C', true);
$pdf->Cell(22, 8, 'Date', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Reference', 1, 0, 'C', true);
$pdf->Cell(25, 8, 'Account Code', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Account Name', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Debit', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Credit', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Description', 1, 1, 'C', true);

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 7);

// Table Content
if (count($results) > 0) {
    foreach ($results as $row) {
        $startY = $pdf->GetY();
        
        // MultiCell for description (might wrap)
        $pdf->SetXY(272, $startY);
        $pdf->MultiCell(60, 5, $row['description'], 0, 'L');
        $endY = $pdf->GetY();
        $rowHeight = $endY - $startY;
        if ($rowHeight < 5) $rowHeight = 5;
        
        // Draw borders for all cells
        $pdf->SetXY(15, $startY); $pdf->Cell(25, $rowHeight, '', 1);
        $pdf->SetXY(40, $startY); $pdf->Cell(22, $rowHeight, '', 1);
        $pdf->SetXY(62, $startY); $pdf->Cell(25, $rowHeight, '', 1);
        $pdf->SetXY(87, $startY); $pdf->Cell(25, $rowHeight, '', 1);
        $pdf->SetXY(112, $startY); $pdf->Cell(45, $rowHeight, '', 1);
        $pdf->SetXY(157, $startY); $pdf->Cell(30, $rowHeight, '', 1);
        $pdf->SetXY(187, $startY); $pdf->Cell(30, $rowHeight, '', 1);
        $pdf->SetXY(217, $startY); $pdf->Cell(55, $rowHeight, '', 1);
        
        // Center content vertically
        $middleY = $startY + ($rowHeight / 2) - 2.5;
        
        $pdf->SetXY(15, $middleY); $pdf->Cell(25, 5, $row['journal_no'], 0, 0, 'C');
        $pdf->SetXY(40, $middleY); $pdf->Cell(22, 5, date('m/d/Y', strtotime($row['posting_date'])), 0, 0, 'C');
        $pdf->SetXY(62, $middleY); $pdf->Cell(25, 5, $row['reference_no'], 0, 0, 'C');
        $pdf->SetXY(87, $middleY); $pdf->Cell(25, 5, $row['account_code'], 0, 0, 'C');
        $pdf->SetXY(112, $middleY); $pdf->Cell(45, 5, substr($row['account_name'], 0, 30), 0, 0, 'L');
        $pdf->SetXY(157, $middleY); $pdf->Cell(30, 5, number_format($row['debit_amount'], 2), 0, 0, 'R');
        $pdf->SetXY(187, $middleY); $pdf->Cell(30, 5, number_format($row['credit_amount'], 2), 0, 0, 'R');
        
        $pdf->SetY($endY);
    }
} else {
    // No data found
    $pdf->SetXY(15, $Y);
    $pdf->Cell(262, 10, 'No transactions found for the selected period.', 1, 1, 'C');
    $Y = $pdf->GetY();
}

$currentY = $pdf->GetY();

// Fill empty rows on first page
if ($pdf->PageNo() == 1 && count($results) > 0) {
    while ($currentY < 180) {
        $pdf->SetXY(15, $currentY); $pdf->Cell(25, 5, '', 1, 0, 'C');
        $pdf->SetXY(40, $currentY); $pdf->Cell(22, 5, '', 1, 0, 'C');
        $pdf->SetXY(62, $currentY); $pdf->Cell(25, 5, '', 1, 0, 'C');
        $pdf->SetXY(87, $currentY); $pdf->Cell(25, 5, '', 1, 0, 'C');
        $pdf->SetXY(112, $currentY); $pdf->Cell(45, 5, '', 1, 0, 'C');
        $pdf->SetXY(157, $currentY); $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetXY(187, $currentY); $pdf->Cell(30, 5, '', 1, 0, 'C');
        $pdf->SetXY(217, $currentY); $pdf->Cell(55, 5, '', 1, 1, 'C');
        $currentY = $pdf->GetY();
    }
}

// Totals row
if (count($results) > 0) {
    $pdf->SetFont('Arial', 'B', 8);
    $pdf->SetXY(112, $currentY);
    $pdf->Cell(45, 7, 'TOTAL', 1, 0, 'R');
    $pdf->SetXY(157, $currentY);
    $pdf->Cell(30, 7, number_format($totalDebit, 2), 1, 0, 'R');
    $pdf->SetXY(187, $currentY);
    $pdf->Cell(30, 7, number_format($totalCredit, 2), 1, 0, 'R');
    $pdf->SetXY(217, $currentY);
    $pdf->Cell(55, 7, '', 1, 1, 'R');
    $currentY = $pdf->GetY();
}

$Y = $currentY + 10;

// Prepared by section
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY(15, $Y);
$pdf->Cell(80, 5, 'Prepared by:', 0, 1, 'L');

$Y = $pdf->GetY() + 3;
$pdf->SetXY(15, $Y);
$pdf->Cell(80, 5, '_________________________', 0, 1, 'L');

$Y = $pdf->GetY();
$pdf->SetXY(15, $Y);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(80, 3, $this->cuser, 0, 1, 'L');

$Y = $pdf->GetY();
$pdf->SetXY(15, $Y);
$pdf->Cell(80, 3, 'Printed Name / Signature', 0, 1, 'L');

$Y = $pdf->GetY() + 3;

// Generated by section
$pdf->SetXY(150, $Y);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(80, 5, 'Generated on:', 0, 1, 'L');

$Y = $pdf->GetY() + 3;
$pdf->SetXY(150, $Y);
$pdf->Cell(80, 5, $formattedDate, 0, 1, 'L');

$Y = $pdf->GetY();
$pdf->SetXY(150, $Y);
$pdf->SetFont('Arial', '', 7);
$pdf->Cell(80, 3, 'System Generated Report', 0, 1, 'L');

$pdf->Output();
exit;
?>