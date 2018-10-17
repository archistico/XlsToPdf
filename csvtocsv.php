<?php
require 'vendor/autoload.php';

$inputFileName = 'csv/lista_non_funzionante.csv';
$listapersone = [];

$handle = fopen($inputFileName, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $parti = explode(",", $line);
        if(is_null($parti[32])) {
            
        }
        $persona = [
            'cognome' => $parti[1],
            'nome' => $parti[3],
            'recapito1' => $parti[32],
            'recapito2' => $parti[30]
        ];
    
        $listapersone[] = $persona;
    }

    fclose($handle);
} else {
    echo "Errore lettura file";
} 

$csv = fopen("csv/export_tipo_outlook.csv", "w");
$iniziale = "First Name,Middle Name,Last Name,Title,Suffix,Initials,Web Page,Gender,Birthday,Anniversary,Location,Language,Internet Free Busy,Notes,E-mail Address,E-mail 2 Address,E-mail 3 Address,Primary Phone,Home Phone,Home Phone 2,Mobile Phone,Pager,Home Fax,Home Address,Home Street,Home Street 2,Home Street 3,Home Address PO Box,Home City,Home State,Home Postal Code,Home Country,Spouse,Children,Manager's Name,Assistant's Name,Referred By,Company Main Phone,Business Phone,Business Phone 2,Business Fax,Assistant's Phone,Company,Job Title,Department,Office Location,Organizational ID Number,Profession,Account,Business Address,Business Street,Business Street 2,Business Street 3,Business Address PO Box,Business City,Business State,Business Postal Code,Business Country,Other Phone,Other Fax,Other Address,Other Street,Other Street 2,Other Street 3,Other Address PO Box,Other City,Other State,Other Postal Code,Other Country,Callback,Car Phone,ISDN,Radio Phone,TTY/TDD Phone,Telex,User 1,User 2,User 3,User 4,Keywords,Mileage,Hobby,Billing Information,Directory Server,Sensitivity,Priority,Private,Categories\n";
fwrite($csv, $iniziale);
foreach($listapersone as $p) {
    $str = $p['nome'].",,".$p['cognome'].",,,,,,,,,,,,,,,".str_replace(array("\r", "\n"), '', $p['recapito1']).",,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,".$p['recapito2'].",,,,,,,,,,,,,,,,,,,,,,,,,,,,,myContacts,\n";
    fwrite($csv, $str);
}

fclose($csv);