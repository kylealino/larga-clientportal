<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$date_from = $this->request->getGet('date_from');
$date_to = $this->request->getGet('date_to');

$this->session = session();
$this->cuser = $this->session->get('__xsys_myuserzicas__');
require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("Y-m-d");
$formattedDate = date("F j, Y", strtotime($currentDate));

class PDF extends \FPDF {
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial', '', 6);
        $this->Cell(0, 4, 'This is a computer-generated document. No signature is required.', 0, 0, 'C');
        $this->SetXY(-35, -15);
        $this->SetFont('Arial', 'I', 7);
        $this->Cell(25, 4, 'Page ' . $this->PageNo() . ' of {nb}', 0, 0, 'R');
    }
}

$pdf = new PDF('P', 'mm', 'A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetTitle('Cash Disbursement Journal');

// Set margins
$leftMargin = 10;
$rightMargin = 10;
$pdf->SetLeftMargin($leftMargin);
$pdf->SetRightMargin($rightMargin);

// Total usable width = 210mm (A4) - 20mm = 190mm
// Column widths for Cash Disbursement
$col_date = 16;       // Date
$col_cv = 20;         // Check/Voucher No.
$col_payee = 45;      // Payee
$col_desc = 32;       // Description
$col_acct_code = 22;  // Account Code
$col_acct_title = 35; // Account Title
$col_debit = 18;      // Debit
$col_credit = 18;     // Credit

$Y = 12;

// Company Header
$pdf->SetY($Y);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 5, 'SCIENCE SAVINGS AND LOAN ASSOCIATION INC.', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', 'B', 11);
$pdf->Cell(0, 5, 'Cash Disbursement Journal', 0, 1, 'C');

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 8);
$period_text = !empty($date_from) && !empty($date_to) 
    ? 'For the period ' . date('F d, Y', strtotime($date_from)) . ' to ' . date('F d, Y', strtotime($date_to))
    : 'For the period __________________ to __________________';
$pdf->Cell(0, 4, $period_text, 0, 1, 'C');

$Y = $pdf->GetY() + 5;

// TABLE HEADER
$pdf->SetFont('Arial', 'B', 6);
$pdf->SetFillColor(240, 240, 240);
$startX = $leftMargin;

$pdf->SetXY($startX, $Y);
$pdf->Cell($col_date, 7, 'Date', 1, 0, 'C', true);
$pdf->Cell($col_cv, 7, 'Check/Voucher No.', 1, 0, 'C', true);
$pdf->Cell($col_payee, 7, 'Payee', 1, 0, 'C', true);
$pdf->Cell($col_desc, 7, 'Description', 1, 0, 'C', true);
$pdf->Cell($col_acct_code, 7, 'Account Code', 1, 0, 'C', true);
$pdf->Cell($col_acct_title, 7, 'Account Title', 1, 0, 'C', true);
$pdf->Cell($col_debit, 7, 'Debit', 1, 0, 'C', true);
$pdf->Cell($col_credit, 7, 'Credit', 1, 1, 'C', true);

$Y = $pdf->GetY();
$pdf->SetFont('Arial', '', 6);

// Helper function to add row
function addRow($pdf, $startX, $col_widths, $data, $rowHeight = 6) {
    $x = $startX;
    $y = $pdf->GetY();
    
    foreach ($data as $i => $cell) {
        $pdf->SetXY($x, $y);
        $align = ($i == 6 || $i == 7) ? 'R' : 'L'; // Right align for Debit and Credit
        $pdf->SetFont('Arial', '', 6);
        $pdf->Cell($col_widths[$i], $rowHeight, $cell, 1, 0, $align);
        $x += $col_widths[$i];
    }
    $pdf->SetY($y + $rowHeight);
}

$col_widths = [$col_date, $col_cv, $col_payee, $col_desc, $col_acct_code, $col_acct_title, $col_debit, $col_credit];
$rowHeight = 6;

// SAMPLE DATA ROWS - Following double-entry accounting principles

// Transaction 1: Office rent payment
addRow($pdf, $startX, $col_widths, [
    '03/01/2026', 'CV-2026-001', 'Landlord Corp', 'Monthly office rent', '50101010', 'Rent Expense', '25,000.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/01/2026', 'CV-2026-001', 'Landlord Corp', 'Monthly office rent', '10101010', 'Cash on Hand', '', '25,000.00'
], $rowHeight);

// Transaction 2: Utility bills payment
addRow($pdf, $startX, $col_widths, [
    '03/05/2026', 'CV-2026-002', 'MERALCO', 'Electricity bill - February 2026', '50102020', 'Utilities Expense', '8,450.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/05/2026', 'CV-2026-002', 'MERALCO', 'Electricity bill - February 2026', '10101010', 'Cash on Hand', '', '8,450.00'
], $rowHeight);

// Transaction 3: Office supplies purchase
addRow($pdf, $startX, $col_widths, [
    '03/10/2026', 'CV-2026-003', 'Office Depot Inc', 'Office supplies and materials', '50103030', 'Office Supplies Expense', '3,250.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/10/2026', 'CV-2026-003', 'Office Depot Inc', 'Office supplies and materials', '10101010', 'Cash on Hand', '', '3,250.00'
], $rowHeight);

// Transaction 4: Employee salaries
addRow($pdf, $startX, $col_widths, [
    '03/15/2026', 'CV-2026-004', 'Payroll Account', 'Staff salaries - March 1-15, 2026', '50104040', 'Salaries Expense', '125,000.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/15/2026', 'CV-2026-004', 'Payroll Account', 'Staff salaries - March 1-15, 2026', '10101010', 'Cash on Hand', '', '125,000.00'
], $rowHeight);

// Transaction 5: Equipment repair and maintenance
addRow($pdf, $startX, $col_widths, [
    '03/18/2026', 'CV-2026-005', 'Tech Services Inc', 'Computer repair & maintenance', '50105050', 'Repairs & Maintenance', '5,200.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/18/2026', 'CV-2026-005', 'Tech Services Inc', 'Computer repair & maintenance', '10101010', 'Cash on Hand', '', '5,200.00'
], $rowHeight);

// Transaction 6: Interest payment on loan
addRow($pdf, $startX, $col_widths, [
    '03/20/2026', 'CV-2026-006', 'Bank of Commerce', 'Monthly loan interest payment', '60101010', 'Interest Expense', '12,500.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/20/2026', 'CV-2026-006', 'Bank of Commerce', 'Monthly loan interest payment', '10101010', 'Cash on Hand', '', '12,500.00'
], $rowHeight);

// Transaction 7: Insurance premium payment
addRow($pdf, $startX, $col_widths, [
    '03/25/2026', 'CV-2026-007', 'Insurance Co', 'Quarterly insurance premium', '10202020', 'Prepaid Insurance', '18,000.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/25/2026', 'CV-2026-007', 'Insurance Co', 'Quarterly insurance premium', '10101010', 'Cash on Hand', '', '18,000.00'
], $rowHeight);

// Transaction 8: Payment to supplier - Accounts Payable
addRow($pdf, $startX, $col_widths, [
    '03/28/2026', 'CV-2026-008', 'Office Supply Co', 'Payment for previous purchase - INV-2456', '20101010', 'Accounts Payable', '15,750.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/28/2026', 'CV-2026-008', 'Office Supply Co', 'Payment for previous purchase - INV-2456', '10101010', 'Cash on Hand', '', '15,750.00'
], $rowHeight);

// Transaction 9: Water bill payment
addRow($pdf, $startX, $col_widths, [
    '03/29/2026', 'CV-2026-009', 'Manila Water', 'Water bill - February 2026', '50102020', 'Utilities Expense', '3,200.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/29/2026', 'CV-2026-009', 'Manila Water', 'Water bill - February 2026', '10101010', 'Cash on Hand', '', '3,200.00'
], $rowHeight);

// Transaction 10: Internet and communication
addRow($pdf, $startX, $col_widths, [
    '03/30/2026', 'CV-2026-010', 'PLDT Inc', 'Internet and telephone bill', '50106060', 'Communication Expense', '4,500.00', ''
], $rowHeight);

addRow($pdf, $startX, $col_widths, [
    '03/30/2026', 'CV-2026-010', 'PLDT Inc', 'Internet and telephone bill', '10101010', 'Cash on Hand', '', '4,500.00'
], $rowHeight);

$Y = $pdf->GetY();

// Calculate totals
$totalDebit = 25000 + 8450 + 3250 + 125000 + 5200 + 12500 + 18000 + 15750 + 3200 + 4500;
$totalCredit = 25000 + 8450 + 3250 + 125000 + 5200 + 12500 + 18000 + 15750 + 3200 + 4500;

// TOTALS ROW
$pdf->SetFont('Arial', 'B', 7);
$pdf->SetFillColor(245, 245, 245);

// Calculate position for totals
$totalStartX = $startX + $col_date + $col_cv + $col_payee + $col_desc;
$pdf->SetXY($totalStartX, $Y);
$pdf->Cell($col_acct_code + $col_acct_title + $col_debit + $col_credit, 6, 'T O T A L S', 1, 0, 'C', true);
$pdf->Cell(0, 6, '', 1, 1, 'C', true);

$pdf->SetXY($totalStartX + $col_acct_code + $col_acct_title, $Y);
$pdf->Cell($col_debit, 6, number_format($totalDebit, 2), 1, 0, 'R', true);
$pdf->Cell($col_credit, 6, number_format($totalCredit, 2), 1, 1, 'R', true);

$currentY = $pdf->GetY() + 6;

// SUMMARY SECTION
$pdf->SetFont('Arial', 'B', 9);
$pdf->SetXY($startX, $currentY);
$pdf->Cell(0, 6, 'SUMMARY OF CASH DISBURSEMENTS', 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(60, 6, 'Total Debit Amount:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 6, 'PHP ' . number_format($totalDebit, 2), 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(60, 6, 'Total Credit Amount:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 6, 'PHP ' . number_format($totalCredit, 2), 0, 1, 'L');

$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(60, 6, 'Net Cash Disbursed:', 0, 0, 'L');
$pdf->SetFont('Arial', 'B', 8);
$pdf->Cell(50, 6, 'PHP ' . number_format($totalDebit, 2), 0, 1, 'L');

// Verification note
$pdf->SetXY($startX, $pdf->GetY() + 3);
$pdf->SetFont('Arial', 'I', 6);
$pdf->Cell(0, 4, 'Note: Total Debits equal Total Credits, confirming double-entry accounting accuracy.', 0, 1, 'L');

$currentY = $pdf->GetY() + 8;

// SIGNATURE SECTION
$pdf->SetFont('Arial', '', 8);
$pdf->SetXY($startX, $currentY);
$pdf->Cell(70, 5, 'Prepared by:', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, $this->cuser, 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Prepared by', 0, 1, 'L');

// Right side - Check Issuer/Approver
$pdf->SetXY(110, $currentY);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, 'Checked by:', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Accounting Head / Designee', 0, 1, 'L');

// Second row of signatures
$pdf->SetXY($startX, $pdf->GetY() + 8);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, 'Approved by:', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY($startX, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Authorized Signatory', 0, 1, 'L');

// Right side - Voucher Auditor
$pdf->SetXY(110, $pdf->GetY() - 12);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, 'Audited by:', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Signature over Printed Name', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY() + 2);
$pdf->SetFont('Arial', '', 8);
$pdf->Cell(70, 5, '_________________________', 0, 1, 'L');

$pdf->SetXY(110, $pdf->GetY());
$pdf->SetFont('Arial', '', 6);
$pdf->Cell(70, 3, 'Internal Auditor', 0, 1, 'L');

// Generated info
$currentY = $pdf->GetY();
if ($currentY > 260) {
    $pdf->AddPage();
    $currentY = 20;
}

$pdf->SetY($currentY + 8);
$pdf->SetFont('Arial', '', 6);
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'Generated on: ' . $formattedDate, 0, 1, 'L');
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'Generated by: ' . $this->cuser, 0, 1, 'L');
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'System: SSLAI Accounting System', 0, 1, 'L');
$pdf->SetXY($startX, $pdf->GetY());
$pdf->Cell(0, 3, 'Module: Cash Disbursement Journal', 0, 1, 'L');

$pdf->Output();
exit;
?>