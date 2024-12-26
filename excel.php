<?php include('db.php');
global $conn;  

$from_date =$to_date=$Billno="";
if (isset($_GET['from_date']) && $_GET['from_date']!="") {
   $from_date = $_GET['from_date'];
}
if (isset($_GET['to_date']) && $_GET['to_date']!="") {
   $to_date = $_GET['to_date'];
}

if (isset($_GET['bill_search']) && $_GET['bill_search']!="") {
   $Billno = $_GET['bill_search'];
}

$total_sql = "SELECT billno,billdate,Subtotal,Tax,total FROM master WHERE id != '' AND status='COMPLETED'";

    if (!empty($from_date) && !empty($to_date)) {
        $total_sql .= " AND billdate BETWEEN '$from_date' AND '$to_date'";
    }
    if (!empty($Billno)) { 
        $total_sql.= " AND Billno = '$Billno'";
    }
 
$billlistResult = $conn->query($total_sql); 
$gst=$totalamt=$subtotal=0;
 
$listofpendingbills = [];
if ($billlistResult->num_rows > 0) {
    while($billrow = $billlistResult->fetch_assoc()) {
        $totalamt +=$billrow['total'];
        $gst +=$billrow['Tax'];
         $subtotal +=$billrow['Subtotal'];
        $listofpendingbills[] = $billrow; 
    }
} 
// Include the PhpSpreadsheet autoloader
require 'vendor-excel-create/autoload.php';

// Create a new PhpSpreadsheet object
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$spreadsheet = new Spreadsheet();

// Define the data to be included in the Excel file
$data = $listofpendingbills;

// Set the active worksheet
$worksheet = $spreadsheet->getActiveSheet();


$headers = ['Bill no','Date','Sub total','Tax','Total'];
$row = 1;
$col = 1;

// Loop through the headers and set them in the first row
foreach ($headers as $header) {
    $worksheet->setCellValueByColumnAndRow($col, $row, $header); // Set static header
    $col++; // Move to the next column
}

// Loop through the data and set each cell value in the worksheet
$row = 2;
foreach ($data as $rowData) {
    $col = 1;
    foreach ($rowData as $cellData) {
        $worksheet->setCellValueByColumnAndRow($col, $row, $cellData);
        $col++;
    }
    $row++;
}
 
$worksheet->setCellValueByColumnAndRow(3, $row+1,round($subtotal,2));
$worksheet->setCellValueByColumnAndRow(4, $row+1,round($gst,2));
$worksheet->setCellValueByColumnAndRow(5, $row+1,$totalamt);

// Set password protection on the Excel file
$sheet = $spreadsheet->getActiveSheet();
$sheet->getProtection()->setSheet(true);
$sheet->getProtection()->setPassword('password');


// Create a new Xlsx object and save the file
$writer = new Xlsx($spreadsheet);
$writer->setPreCalculateFormulas(false);
$fname= date("dMYhis");
$writer->save($fname.'.xlsx');

// Set the HTTP headers to force download of the Excel file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data.xlsx"');
header('Cache-Control: max-age=0');

// Write the Excel file to output
$writer->save('php://output');

// Exit the script to prevent any extra output
exit;

?>
