<?php
require 'vendor/autoload.php';

class MyReadFilter implements \PhpOffice\PhpSpreadsheet\Reader\IReadFilter {

    public function readCell($column, $row, $worksheetName = '') {
        // Read title row and rows 20 - 30
        if ($row >= 1) {
            return true;
        }
        return false;
    }

}

$inputFileType = 'Ods';
$inputFileName = 'origin/listapazienti.ods';
$sheetname = 'listapazienti';

$reader = new \PhpOffice\PhpSpreadsheet\Reader\Ods();
$reader->setLoadSheetsOnly($sheetname);
$reader->setReadDataOnly(true);
$reader->setReadFilter( new MyReadFilter() );
$spreadsheet = $reader->load($inputFileName);

$worksheet = $spreadsheet->getActiveSheet();

$highestRow = $worksheet->getHighestRow(); 
$highestColumn = $worksheet->getHighestColumn(); 
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); 

define('FPDF_FONTPATH','font');

$listapersone = [];

for ($row = 2; $row <= $highestRow; ++$row) {
    $persona = [
        'cognome' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
        'nome' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
        'sesso' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
        'datanascita' => $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
        'codicefiscale' => $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
        'eta' => $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
        'recapiti' => trim($worksheet->getCellByColumnAndRow(14, $row)->getValue()." ".$worksheet->getCellByColumnAndRow(15, $row)->getValue())
    ];

    $listapersone[] = $persona;
    
}

$pdf = new FPDF('P','mm','A4');
$pdf->SetMargins(15, 15, 15);
$pdf->AddPage();
$pdf->SetFont('Arial','B',12);
$pdf->Cell(0,12, 'CAMPAGNA VACCINAZIONE 2018 - > 65 anni',1,1,'C');
$pdf->SetFont('Arial','',9);
$pdf->Ln(3);

foreach($listapersone as $p) {
    if($p['eta']>=65) {
        $pdf->Cell(62,6, utf8_decode($p['cognome']." ".$p['nome']),1,0);
        $pdf->Cell(8,6, utf8_decode($p['eta']),1,0);
        $pdf->Cell(50,6, utf8_decode($p['recapiti']),1,0);
        $pdf->Cell(25,6, ' ',1,0);
        $pdf->Cell(35,6, ' ',1,1);
    }
}

$pdf->Output('F', 'pdf/2018_CAMPAGNA_VACCINAZIONE.pdf');