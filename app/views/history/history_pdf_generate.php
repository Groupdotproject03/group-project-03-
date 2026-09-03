<?php
/**
 * History PDF Generator template
 */
class HistoryPDF extends FPDF {
    function Header() {}
    function Footer() {
        $this->SetY(-15);
        $this->SetFont('Arial','I',8);
        $this->SetTextColor(150);
        $this->Cell(0,10,'Health First - Confidential Health Report   |   Page '.$this->PageNo(),0,0,'C');
    }
}

$pdf = new HistoryPDF('P','mm','A4');
$pdf->AddPage();
$pdf->SetMargins(15,15,15);
$pdf->SetAutoPageBreak(true, 20);

/* HEADER BAR */
$pdf->SetFillColor(79,172,254);
$pdf->Rect(0,0,210,28,'F');
$pdf->SetTextColor(255,255,255);
$pdf->SetFont('Arial','B',18);
$pdf->SetY(6);
$pdf->Cell(0,10,'Health First - Personal Health Report',0,1,'C');
$pdf->SetFont('Arial','',10);
$pdf->Cell(0,6,'Generated on: '.date('d M Y, h:i A'),0,1,'C');
$pdf->SetTextColor(0,0,0);
$pdf->Ln(8);

/* PATIENT INFO */
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(224,243,255);
$pdf->Cell(0,8,' Patient Information',0,1,'L',true);
$pdf->Ln(2);

$dob = $userInfo['DateOfBirth'] ?? '';
$age = $dob ? (date('Y') - date('Y', strtotime($dob))) : 'N/A';

$info = [
    ['Name',   $userInfo['Name'] ?? ''],
    ['Email',  $userInfo['Email'] ?? ''],
    ['Phone',  $userInfo['PhoneNo'] ?? 'N/A'],
    ['Gender', $userInfo['Gender'] ?? 'N/A'],
    ['Age',    $age.' years'],
    ['Weight', ($userInfo['Weight'] ?? 'N/A').' kg'],
    ['Height', ($userInfo['Height'] ?? 'N/A')],
    ['BMI',    $latestBmi],
];

$half = array_chunk($info, 4);
$left = $half[0];
$right = isset($half[1]) ? $half[1] : [];
$rows = max(count($left), count($right));
for($r = 0; $r < $rows; $r++) {
    $pdf->SetX(15);
    if(isset($left[$r])) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(22,7,$left[$r][0].':',0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(73,7,$left[$r][1],0,0,'L');
    } else {
        $pdf->Cell(95,7,'',0,0,'L');
    }
    if(isset($right[$r])) {
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(22,7,$right[$r][0].':',0,0,'L');
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(58,7,$right[$r][1],0,1,'L');
    } else {
        $pdf->Ln();
    }
}
$pdf->Ln(6);

/* HEALTH HISTORY */
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(224,243,255);
$pdf->Cell(0,8,' Health History - Last 15 Days',0,1,'L',true);
$pdf->Ln(2);

$pdf->SetFont('Arial','B',9);
$pdf->SetFillColor(79,172,254);
$pdf->SetTextColor(255,255,255);
$cols  = ['Date','BMI','Score','Sleep','Water','Steps','Workout','BP','Sugar'];
$widths= [25,   15,   18,    15,    15,    18,    18,    25,    27];
foreach($cols as $i => $c) $pdf->Cell($widths[$i],7,$c,1,0,'C',true);
$pdf->Ln();
$pdf->SetTextColor(0,0,0);

$fill = false;
if(!empty($records)) {
    foreach($records as $row) {
        $pdf->SetFont('Arial','',8);
        $pdf->SetFillColor($fill ? 240 : 255, $fill ? 248 : 255, $fill ? 255 : 255);

        $bp = ($row['BloodPressureSystolic'] > 0)
            ? $row['BloodPressureSystolic'].'/'.$row['BloodPressureDiastolic']
            : 'N/A';
        $sg = ($row['BloodSugar'] > 0)
            ? $row['BloodSugar'].' ('.($row['BloodSugarType']=='postmeal'?'PM':'Fast').')'
            : 'N/A';

        $data = [
            date('d M Y', strtotime($row['LogDate'])),
            round($row['BMI'],1),
            $row['Score'].'/100',
            ($row['SleepHours'] ?? 'N/A').'h',
            ($row['WaterIntake'] ?? 'N/A').'L',
            $row['StepsCount'] ?? 'N/A',
            $row['WorkoutDone'] ? 'Yes' : 'No',
            $bp,
            $sg,
        ];
        foreach($data as $i => $d)
            $pdf->Cell($widths[$i],6,$d,1,0,'C',$fill);
        $pdf->Ln();
        $fill = !$fill;
    }
} else {
    $pdf->SetFont('Arial','I',10);
    $pdf->Cell(0,8,'No health records found.',0,1,'C');
}

/* CURRENT MED STATUS */
$pdf->Ln(6);
$pdf->SetFont('Arial','B',12);
$pdf->SetFillColor(224,243,255);
$pdf->Cell(0,8,' Current Medication Status',0,1,'L',true);
$pdf->Ln(2);
$pdf->SetFont('Arial','',11);
$color = ($medsStatus == 'Taking') ? [40,167,69] : [220,53,69];
$pdf->SetTextColor($color[0],$color[1],$color[2]);
$pdf->Cell(0,7,'  Latest Log - Medicine: '.$medsStatus,0,1,'L');
$pdf->SetTextColor(0,0,0);

/* OUTPUT */
$filename = 'HealthReport_'.date('Y-m-d').'.pdf';
$pdf->Output('D', $filename);
exit();
