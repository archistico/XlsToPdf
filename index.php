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
$inputFileName = 'origin/lista.ods';
$sheetname = 'lista';

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

for ($row = 2; $row <= $highestRow; ++$row) {
    $persona = [
        'cognome' => $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
        'nome' => $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
        'sesso' => $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
        'datanascita' => $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
        'codicefiscale' => $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
        'eta' => $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
        'recapiti' => trim($worksheet->getCellByColumnAndRow(7, $row)->getValue())
    ];

    
    $pdf = new FPDF('P','mm','A4');
    $pdf->SetMargins(15, 15, 15);
    $pdf->AddFont('Calibri','');
    $pdf->AddFont('Calibri','B');
    $pdf->AddPage();
    $pdf->SetFont('Calibri','B',12);
    $pdf->Cell(0,12, 'INFORMATIVA PER IL TRATTAMENTO DEI DATI SENSIBILI',1,1,'C');
    $pdf->SetFont('Calibri','',9);
    $pdf->Ln(3);

    if($persona['sesso']=='F') {
        $str1 = <<<EOD
Gentile Signora, ai sensi del Regolamento UE 2016/679, il trattamento dei dati e delle informazioni che La riguardano sarà effettuato in conformità ai principi di liceità, correttezza e trasparenza, in maniera compatibile, nonché adeguata, pertinente e limitata a quanto necessario rispetto alle finalità di tale trattamento e sicura. In particolare, i Suoi dati personali di carattere genetico, biometrico e intesi a identificare in modo univoco una persona fisica, nonché quelli relativi alla Sua salute o alla Sua vita sessuale o al Suo orientamento sessuale potranno essere trattati, oltre che negli specifici casi disciplinati dall'art. 9 del Regolamento UE succitato, previa prestazione del consenso esplicito da parte Sua. 
Ai sensi degli artt. 13 e 14 del Regolamento UE 2016/679 Le forniamo, quindi, le seguenti informazioni: 
a) i dati sensibili da Lei forniti verranno trattati per le seguenti finalità: ottemperanza agli obiettivi e alle cure erogate dal SSN; 
b) titolare del trattamento è la Dottoressa CHRISTINE ROLLANDIN (CF: RLLCRS84C65E379H);
c) destinatari dei Suoi dati personali, in ragione della organizzazione del presente studio medico, saranno i seguenti soggetti:
EOD;
    } else {
$str1 = <<<EOD
Gentile Signor, ai sensi del Regolamento UE 2016/679, il trattamento dei dati e delle informazioni che La riguardano sarà effettuato in conformità ai principi di liceità, correttezza e trasparenza, in maniera compatibile, nonché adeguata, pertinente e limitata a quanto necessario rispetto alle finalità di tale trattamento e sicura. In particolare, i Suoi dati personali di carattere genetico, biometrico e intesi a identificare in modo univoco una persona fisica, nonché quelli relativi alla Sua salute o alla Sua vita sessuale o al Suo orientamento sessuale potranno essere trattati, oltre che negli specifici casi disciplinati dall'art. 9 del Regolamento UE succitato, previa prestazione del consenso esplicito da parte Sua. Ai sensi degli artt. 13 e 14 del Regolamento UE 2016/679 Le forniamo, quindi, le seguenti informazioni: 
a) i dati sensibili da Lei forniti verranno trattati per le seguenti finalità: ottemperanza agli obiettivi e alle cure erogate dal SSN; 
b) titolare del trattamento è la Dottoressa CHRISTINE ROLLANDIN (CF: RLLCRS84C65E379H);
c) destinatari dei Suoi dati personali, in ragione della organizzazione del presente studio medico, saranno i seguenti soggetti:
EOD;
    }

    $pdf->MultiCell(0,4, utf8_decode($str1));

    $str = <<<EOD
c.1) per ragioni che attengono alla migliore esecuzione dell'incarico professionale attribuito al medico, potranno avere accesso i collaboratori e/o i segretari presenti nello studio medico, nonché eventuali infermieri:
EOD;

    $pdf->MultiCell(0,4, utf8_decode($str));
    $pdf->Ln(6);

    $str = <<<EOD
c.2) per ragioni di cura della Sua persona potranno avere accesso altri medici sostituti presenti nello studio medico:
EOD;

    $pdf->MultiCell(0,4, utf8_decode($str));
    $pdf->Ln(6);

    $str = <<<EOD
c.3) per ragioni di cura della Sua persona potranno avere accesso altri medici di medicina generale componenti l'associazione:
EOD;

    $pdf->MultiCell(0,4, utf8_decode($str));
    $pdf->Ln(6);

    $str = <<<EOD
c.7) per ragioni che attengono la migliore organizzazione del lavoro prestato dal medico, potranno avere accesso i consulenti fiscali da quest'ultimo nominati, nei limiti in cui ciò si renda utile e necessario per l'adempimento dell'incarico professionale:
EOD;

    $pdf->MultiCell(0,4, utf8_decode($str));
    $pdf->Ln(6);

    $str = <<<EOD
c.8) per ragioni che attengono la migliore organizzazione del lavoro prestato dal medico, potranno avere accesso i consulenti informatici / software house da quest'ultimo nominati, nei limiti in cui ciò si renda utile e necessario per l'adempimento dell'incarico professionale (assistenza, manutenzione e fornitura anche in remoto dei sistemi informatici):
EOD;

    $pdf->MultiCell(0,4, utf8_decode($str));
    $pdf->Ln(6);

    $str3 = <<<EOD
e) è in suo diritto delegare soggetti terzi, di sua fiducia, al ritiro o alla consegna di documentazione sanitaria che la riguarda, soggetti che verranno, anche verbalmente, indicati al medico o ai suoi collaboratori e sostituti, con esonero di ogni responsabilità al riguardo nei confronti del medico; 
f) i dati da Lei forniti potrebbero, in virtù di norme legali e regolamentari anche regionali imposta al medico di medicina generale, tempo per tempo vigenti, essere inoltrati o comunicati ad Enti o soggetti terzi (quali a titolo meramente esemplificativo, ASL, Regione, Ministeri etc.) e che il medico, successivamente alla trasmissione del dato è esente da responsabilità per l'uso, la perdita o la alterazione del dato personale o sensibile da parte di tali soggetti terzi. La informiamo, altresì, che, nel caso in cui Lei fornirà i dati personali di cui sopra: 
- i Suoi dati personali saranno conservati per il seguente periodo: 10 anni; 
- è Suo diritto chiedere al titolare del trattamento l'accesso ai dati personali e la rettifica o la cancellazione degli stessi; 
- è altresì Suo diritto chiedere al titolare del trattamento, anche rispetto a singole categorie di persone che possono essere destinatari dei Suoi dati, la limitazione, del trattamento che La riguarda, ovvero di opporsi al trattamento, o ancora di ottenere la portabilità dei dati in questione; 
- è inoltre Suo diritto revocare il consenso al trattamento dei dati fornito o proporre reclamo a un'autorità di controllo; 
- il trattamento dei dati personali e sensibili di cui sopra discende dall'adempimento di un obbligo legale, per cui dalla mancata comunicazione di tali dati (anche se derivante dal rifiuto di prestare il consenso, ovvero dalla revoca dello stesso) potrà discendere l'impossibilità giuridica di effettuare le prestazioni che costituiscono la base legale del trattamento dei Suoi dati; 
- è Suo diritto accedere ai dati personali trattati e conseguire le informazioni di cui all'art. 15 del Regolamento UE 2016/679, nonché ottenere copia degli stessi, laddove in caso di ulteriori copie il titolare del trattamento Le potrà richiedere il pagamento di un contributo spese ragionevole. 
EOD;

    $pdf->MultiCell(180,4, utf8_decode($str3));

    $pdf->Ln(3);
    $pdf->SetFont('Calibri','B',12);
    $pdf->Cell(0,12, 'CONSENSO PER IL TRATTAMENTO DEI DATI PERSONALI',1,1,'C');
    $pdf->SetFont('Calibri','',9);
    $pdf->Ln(3);

    if($persona['sesso']=='F') {
        $pdf->MultiCell(0,4, utf8_decode("La sottoscritta: ".$persona['cognome']." ".$persona['nome']));
    } else {
        $pdf->MultiCell(0,4, utf8_decode("Il sottoscritto: ".$persona['cognome']." ".$persona['nome']));
    }
    $pdf->MultiCell(0,4, utf8_decode("Data di nascita: ".$persona['datanascita']));
    $pdf->MultiCell(0,4, utf8_decode("Codice fiscale: ".$persona['codicefiscale']));
        
    $pdf->SetFont('Calibri','B',11);
    $pdf->Cell(0,12, 'ACCONSENTE',0,2,'C');
    $pdf->SetFont('Calibri','',9);
        
    $str4 = <<<EOD
al trattamento dei propri dati personali, ai sensi degli arti. 6 e 7 del Regolamento UE 2016/679, secondo quanto indicato nell'informativa allegata, che dichiara di avere ricevuto in maniera chiara ed esplicita e di avere compiutamente compreso. 
EOD;
    
    $pdf->MultiCell(0,4, utf8_decode($str4));

    $str5 = "Data:";
    $str6 = "Firma:";

    $pdf->Ln(5);
    $pdf->Cell(60,4, utf8_decode($str5),0,0);
    $pdf->Cell(100,4, utf8_decode($str6),0,0);

    $x_acc = 145;
    $y = 82;
    $x_nonacc = $x_acc + 25;
    $x_ret_acc = $x_acc - 5;
    $x_ret_nonacc = $x_nonacc - 5;
    $y_ret = $y - 3;

    $pdf->Text($x_acc,$y, 'Acconsento');
    $pdf->Text($x_nonacc,$y, 'Non acconsento');
    $pdf->Rect($x_ret_acc,$y_ret, 3,3);
    $pdf->Rect($x_ret_nonacc,$y_ret, 3,3);

    $x_acc = 145;
    $y = 82+10;
    $x_nonacc = $x_acc + 25;
    $x_ret_acc = $x_acc - 5;
    $x_ret_nonacc = $x_nonacc - 5;
    $y_ret = $y - 3;

    $pdf->Text($x_acc,$y, 'Acconsento');
    $pdf->Text($x_nonacc,$y, 'Non acconsento');
    $pdf->Rect($x_ret_acc,$y_ret, 3,3);
    $pdf->Rect($x_ret_nonacc,$y_ret, 3,3);

    $x_acc = 145;
    $y = 82+10+10;
    $x_nonacc = $x_acc + 25;
    $x_ret_acc = $x_acc - 5;
    $x_ret_nonacc = $x_nonacc - 5;
    $y_ret = $y - 3;

    $pdf->Text($x_acc,$y, 'Acconsento');
    $pdf->Text($x_nonacc,$y, 'Non acconsento');
    $pdf->Rect($x_ret_acc,$y_ret, 3,3);
    $pdf->Rect($x_ret_nonacc,$y_ret, 3,3);

    $x_acc = 145;
    $y = 82+10+10+14;
    $x_nonacc = $x_acc + 25;
    $x_ret_acc = $x_acc - 5;
    $x_ret_nonacc = $x_nonacc - 5;
    $y_ret = $y - 3;

    $pdf->Text($x_acc,$y, 'Acconsento');
    $pdf->Text($x_nonacc,$y, 'Non acconsento');
    $pdf->Rect($x_ret_acc,$y_ret, 3,3);
    $pdf->Rect($x_ret_nonacc,$y_ret, 3,3);

    $x_acc = 145;
    $y = 82+10+10+14+16;
    $x_nonacc = $x_acc + 25;
    $x_ret_acc = $x_acc - 5;
    $x_ret_nonacc = $x_nonacc - 5;
    $y_ret = $y - 3;

    $pdf->Text($x_acc,$y, 'Acconsento');
    $pdf->Text($x_nonacc,$y, 'Non acconsento');
    $pdf->Rect($x_ret_acc,$y_ret, 3,3);
    $pdf->Rect($x_ret_nonacc,$y_ret, 3,3);

    $pdf->SetLineWidth(0.25);
    $pdf->Line(25,275,60,275);
    $pdf->Line(90,275,195,275);

    $pdf->Output('F', 'pdf/'.$persona['cognome'].' '.$persona['nome'].'.pdf');
}