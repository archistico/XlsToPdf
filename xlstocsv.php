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

$iniziale = "Name,Given Name,Additional Name,Family Name,Yomi Name,Given Name Yomi,Additional Name Yomi,Family Name Yomi,Name Prefix,Name Suffix,Initials,Nickname,Short Name,Maiden Name,Birthday,Gender,Location,Billing Information,Directory Server,Mileage,Occupation,Hobby,Sensitivity,Priority,Subject,Notes,Language,Photo,Group Membership,E-mail 1 - Type,E-mail 1 - Value,Phone 1 - Type,Phone 1 - Value,Phone 2 - Type,Phone 2 - Value";

foreach($listapersone as $p) {
    
}
