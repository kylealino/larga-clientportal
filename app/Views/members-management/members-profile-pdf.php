<?php
$this->request = \Config\Services::request();
$this->db = \Config\Database::connect();

$member_id = $this->request->getPostGet('member_id');

require APPPATH . 'ThirdParty/fpdf/fpdf.php';

$currentDate = date("F d, Y");

// ==========================
// FETCH DATA
// ==========================
$query = $this->db->query("SELECT * FROM tbl_members WHERE member_id = '$member_id'");
$data = $query->getRowArray();

if(!$data){
    echo "Member not found!";
    exit;
}

$dob = !empty($data['date_of_birth']) ? date('F d, Y', strtotime($data['date_of_birth'])) : '';

// ==========================
// PDF CLASS
// ==========================
class PDF extends FPDF {

    function Header(){
        $this->SetFont('Arial','',10);
        $this->Cell(0,5,'SCIENCE SAVINGS AND LOAN ASSOCIATION, INC. (SSLAI)',0,1,'C');
        $this->Cell(0,5,'DOST Compd., Bicutan, Taguig City',0,1,'C');
        $this->Ln(2);

        $this->SetFont('Arial','B',12);
        $this->Cell(0,6,'SSLAI Membership Profile Update Form',0,1,'C');
        $this->Ln(3);
    }

    function Footer(){
        $this->SetY(-10);
        $this->SetFont('Arial','I',8);
        $this->Cell(0,5,'Page '.$this->PageNo().' of {nb}',0,0,'C');
    }
}

// ==========================
// INIT PDF
// ==========================
$pdf = new PDF('P','mm','A4');
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetMargins(15,10,15);

// ==========================
// I. MEMBER INFO
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'I. Member Information',0,1);

$pdf->SetFont('Arial','',9);

// 1
$pdf->Cell(45,4,'1. Member ID No.',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$data['member_no'],'B',1);

$pdf->Ln(1);

// 2 NAME
$pdf->Cell(45,4,'2. Name',0,0);
$pdf->Cell(2,4,':',0,0);

$pdf->Cell(44,4,$data['last_name'],'B',0);
$pdf->Cell(44,4,$data['first_name'],'B',0);
$pdf->Cell(45,4,$data['middle_name'],'B',1);

$pdf->SetFont('Arial','',8);
$pdf->Cell(47,4,'',0,0);
$pdf->Cell(44,4,'Last Name',0,0,'C');
$pdf->Cell(44,4,'First Name',0,0,'C');
$pdf->Cell(45,4,'Middle Name',0,1,'C');

$pdf->SetFont('Arial','',9);
$pdf->Ln(1);

// 3
$pdf->Cell(45,4,'3. Date of Birth',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$dob,'B',1);

// 4
$pdf->Cell(45,4,'4. Place of Birth',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$data['place_of_birth'],'B',1);

// 5
$pdf->Cell(45,4,'5. Age',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$data['age'],'B',1);

$pdf->Ln(2);

// 6 CIVIL STATUS
$pdf->Cell(45,4,'6. Civil Status',0,0);
$pdf->Cell(2,4,':',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(18,4,'Single',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(20,4,'Married',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(22,4,'Widowed',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(21,4,'Divorced',0,1);

$pdf->Ln(2);

// 7 GENDER
$pdf->Cell(45,4,'7. Gender',0,0);
$pdf->Cell(2,4,':',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(20,4,'Male',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(22,4,'Female',0,0);

$pdf->Cell(5,4,'',1,0);
$pdf->Cell(20,4,'Other',0,1);

$pdf->Ln(2);

// 8
$pdf->Cell(45,4,'8. TIN',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$data['tin'],'B',1);

// 9
$pdf->Cell(45,4,'9. GSIS Number',0,0);
$pdf->Cell(2,4,':',0,0);
$pdf->Cell(133,4,$data['gsis_number'],'B',1);

$pdf->Ln(4);

// ==========================
// II. CONTACT INFO
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'II. Contact Information',0,1);

$pdf->SetFont('Arial','',9);

// Permanent
$pdf->Cell(0,6,'1. Permanent Address',0,1);

$pdf->Cell(30,4,'Street',0,0);
$pdf->Cell(60,4,$data['permanent_street'],'B',0);
$pdf->Cell(25,4,'Barangay',0,0);
$pdf->Cell(50,4,$data['permanent_barangay'],'B',1);

$pdf->Cell(30,4,'City/Municipality',0,0);
$pdf->Cell(60,4,$data['permanent_city'],'B',0);
$pdf->Cell(25,4,'Province',0,0);
$pdf->Cell(50,4,$data['permanent_province'],'B',1);

$pdf->Cell(30,4,'Zip Code',0,0);
$pdf->Cell(40,4,$data['permanent_zip'],'B',1);

$pdf->Ln(2);

// Present
$pdf->Cell(0,6,'2. Present Address',0,1);

$pdf->Cell(30,4,'Street',0,0);
$pdf->Cell(60,4,$data['present_street'],'B',0);
$pdf->Cell(25,4,'Barangay',0,0);
$pdf->Cell(50,4,$data['present_barangay'],'B',1);

$pdf->Cell(30,4,'City/Municipality',0,0);
$pdf->Cell(60,4,$data['present_city'],'B',0);
$pdf->Cell(25,4,'Province',0,0);
$pdf->Cell(50,4,$data['present_province'],'B',1);

$pdf->Cell(30,4,'Zip Code',0,0);
$pdf->Cell(40,4,$data['present_zip'],'B',1);

$pdf->Ln(2);

// Contacts
$pdf->Cell(45,4,'3. Mobile Number',0,0);
$pdf->Cell(50,4,$data['contact_number'],'B',1);

$pdf->Cell(45,4,'4. Home Phone Number',0,0);
$pdf->Cell(50,4,$data['home_phone'],'B',1);

$pdf->Cell(45,4,'5. Office Phone Number',0,0);
$pdf->Cell(50,4,$data['office_phone'],'B',1);

$pdf->Cell(45,4,'6. Email Address',0,0);
$pdf->Cell(90,4,$data['email'],'B',1);

$pdf->Ln(4);

// ==========================
// III. EMPLOYMENT
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'III. Employment Information',0,1);

$pdf->SetFont('Arial','',9);

$pdf->Cell(45,4,'1. Department/Agency',0,0);
$pdf->Cell(70,4,$data['department_agency'],'B',1);

$pdf->Cell(45,4,'2. Position',0,0);
$pdf->Cell(70,4,$data['position'],'B',1);

$pdf->Cell(45,4,'3. Salary Grade',0,0);
$pdf->Cell(30,4,$data['salary_grade'],'B',1);

$pdf->Ln(4);

// ==========================
// IV. BENEFICIARIES
// ==========================
$pdf->SetFont('Arial','B',10);
$pdf->Cell(0,6,'IV. Contact Person(s)/Beneficiaries',0,1);

$pdf->SetFont('Arial','',9);

// B1
$pdf->Cell(0,4,'1. Name of Beneficiary (1): '.$data['beneficiary1_name'],0,1);
$pdf->Cell(30,4,'Address',0,0);
$pdf->Cell(100,4,$data['beneficiary1_address'],'B',1);

$pdf->Cell(45,4,'Contact Number',0,0);
$pdf->Cell(50,4,$data['beneficiary1_contact'],'B',0);
$pdf->Cell(30,4,'Relationship',0,0);
$pdf->Cell(40,4,$data['beneficiary1_relationship'],'B',1);

$pdf->Ln(2);

// B2
$pdf->Cell(0,4,'2. Name of Beneficiary (2): '.$data['beneficiary2_name'],0,1);
$pdf->Cell(30,4,'Address',0,0);
$pdf->Cell(100,4,$data['beneficiary2_address'],'B',1);

$pdf->Cell(45,4,'Contact Number',0,0);
$pdf->Cell(50,4,$data['beneficiary2_contact'],'B',0);
$pdf->Cell(30,4,'Relationship',0,0);
$pdf->Cell(40,4,$data['beneficiary2_relationship'],'B',1);

$pdf->Ln(4);

// ==========================
// DECLARATION
// ==========================
$pdf->MultiCell(0,4,
"Declaration: I hereby certify that the information provided above is true and correct to the best of my knowledge. I authorize SSLAI to use this information for the purpose of updating my records."
);

$pdf->Ln(6);

$pdf->Cell(60,4,'Signature of Member:',0,0);
$pdf->Cell(80,4,'','B',0);

$pdf->Cell(15,4,'Date:',0,0);
$pdf->Cell(35,4,$currentDate,'B',1);

// ==========================
$pdf->Output('I','SSLAI_Profile.pdf');
exit;
?>