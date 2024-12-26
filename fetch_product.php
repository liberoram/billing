<?php
include 'db.php';



if (isset($_POST['Purpose']) && $_POST['Purpose'] ==="BILL") {
	echo bill();
}
else if (isset($_POST['Purpose']) && $_POST['Purpose'] ==="NEWBILL") {
	echo newbill();
}
else if (isset($_POST['Purpose']) && $_POST['Purpose'] ==="GETPENDINGBILLS") {
	echo getpendingbills();
}
else if (isset($_POST['Purpose']) && $_POST['Purpose'] ==="UPDATEQTY") {
	echo UpdateBill();
}

function UpdateBill()
{
	$BillDetails = base64_decode($_POST['BillDetails']);
	$de_BillDetails = json_decode($BillDetails,true);
	$uniqueId = $de_BillDetails['uniqueId'];
	$price = $de_BillDetails['price'];
	$newQuantity = $_POST['newQuantity'];
	$newtotal = $price * $newQuantity;
	global $conn;  
	$update_query="Update sales SET quantity='$newQuantity',total='$newtotal' WHERE uniqueId='$uniqueId'";
	if ($conn->query($update_query) === TRUE) {
		return json_encode(['success' => 'Records updated successfully']);
	} else {
		echo "Error updating records: " . $conn->error;
	}
}

function bill() {
	$shortcode = $_POST['shortcode'];
	$qty = $_POST['quantity'];
	$Billno = $_POST['CurrentBill'];
	global $conn;  
	$sql = "SELECT * FROM product where scode='$shortcode'";
	$result = $conn->query($sql);
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}
	} 

	if (isset($products[0])) {

     $product = $products[0];  // Get the first product
     $price = isset($product['amt']) ? floatval($product['amt']) : 0; 
     $productName = isset($product['product']) ? $product['product'] : 'Unknown';
      $tamil = isset($product['tamil']) ? $product['tamil'] : 'Unknown'; 
     $status ="PENDING"; 
     $datatime = date('Y-m-d');
     $total = $price * $qty;
     $uniqueId = 'temp_' . time() . '_' . rand(1000, 9999); 
     if($shortcode=="35" || $shortcode=="41")
     {
     	$price = $total = $qty;
     	$qty="1";
     }

     $insert_sql = "INSERT INTO sales (billno,item,amt,quantity,status,bill_date,total,uniqueId,tamil) VALUES ('$Billno', '$productName','$price','$qty','$status','$datatime','$total','$uniqueId','$tamil')";
     if ($conn->query($insert_sql) === false) {
     	return json_encode(['error' => 'Due to some issues']); exit;
     }
     return json_encode([
     	'name' => $product['product'],
     	'tamil' => $tamil,
     	'qty' => $qty,
     	'price' => $price, 
     	'total' => $total,
     	'uniqueId' => $uniqueId
     ]);
 } else {
 	return json_encode(['error' => 'Product not found']);
 }

}


function newbill()
{ 
	global $conn; 

// Step 1: Find the max billno
	$sql = "SELECT MAX(billno) AS max_billno FROM master";
	$result = $conn->query($sql);
	if ($result === false) {
		return json_encode(['error' => 'Error fetching the max bill number']);
	}

	$row = $result->fetch_assoc();
	$maxBillno = $row['max_billno'];
	$newBillno = $maxBillno + 1;   

// Step 3: Insert a new record using the new billno
	$insert_sql = "INSERT INTO master (billno, billdate, Subtotal, total, cgst, sgst, Tax, status) 
	VALUES ('$newBillno', '', '', '', '', '', '', 'PENDING')";

	if ($conn->query($insert_sql) === false) {
		return json_encode(['error' => 'Due to some issues']);
	} else {
		return json_encode(['success' => 'Successfully inserted', 'new_billno' => $newBillno]);
	}

}


function getpendingbills()
{
	global $conn; 
	$billno = $_POST['billno'];
	$selectSql = "SELECT * FROM sales WHERE status = 'PENDING' and billno='$billno'";
	$result = $conn->query($selectSql);
	$endresult['billno']="";
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}
		$endresult['PendingBill'] = $products;
	}  
	return json_encode($endresult);
}
/*End insert bill*/

if (isset($_POST['checkItemes'])) {
	echo checkItemes($_POST['checkItemes']);
}

function checkItemes()
{
	global $conn;  
   $selectSql = "SELECT * FROM waiter WHERE status = 'ACTIVE'";
   $result = $conn->query($selectSql); 

   $selectBillSql = "SELECT MAX(Billno) as billno FROM `master`";
   $billResult = $conn->query($selectBillSql); 
   
   $Billno = 1;
   if ($billResult->num_rows > 0) {
   	$billRow = $billResult->fetch_assoc();
   	$Billno = $billRow['billno'] ? $billRow['billno'] + 1 : 1;
   }

   if($Billno)
   { 
   	$checkbillsql = "SELECT * FROM master WHERE status ='NEW'";
   	$checkbillsqlesult = $conn->query($checkbillsql);
   	if ($checkbillsqlesult->num_rows == 0) {
   		$insert_sql = "INSERT INTO master (billno, billdate, Subtotal, total, cgst, sgst, Tax, status) 
   		VALUES ('$Billno', '', '', '', '', '', '', 'NEW')";
   		if ($conn->query($insert_sql) === false) {
    			//return json_encode(['error' => 'Due to some issues']);
   		} 
   	}
   	else
   	{ 
   		$bill_Row = $checkbillsqlesult->fetch_assoc();
   		$Billno = $bill_Row['billno'];;
   	}

   } 
   $selectBillListSql = "SELECT billno FROM `master` where status='PENDING' || status='NEW'";
   $billlistResult = $conn->query($selectBillListSql); 

   $listofpendingbills = [];
   if ($billlistResult->num_rows > 0) {
   	while($billrow = $billlistResult->fetch_assoc()) {
   		$listofpendingbills[] = $billrow; 
   	}   
   	$endresult['listofpendingbills'] = $listofpendingbills;
   } 


    $waiterslist = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $waiterslist[] = $row; 
        }   
        $endresult['waiterslist'] = $waiterslist;
    }   
    $endresult['Billno'] = $Billno;
    return json_encode($endresult);
}

/*Remove form bill*/
if (isset($_POST['deleteid'])) {
	echo removeItem($_POST['deleteid']);
}


function removeItem($uniqueId)
{
	global $conn;
	$selectSql = "SELECT * FROM sales WHERE uniqueId = '$uniqueId'";
	$result = $conn->query($selectSql);

	if ($result && $result->num_rows > 0) {
		$sale = $result->fetch_assoc();		
		$deleteSql = "DELETE FROM sales WHERE uniqueId = '$uniqueId'";
		if ($conn->query($deleteSql) === TRUE) {
			return 'success'; 
		} else {
			echo "Error deleting sales record: " . $conn->error;
		}
	} else {
		echo "Sales record not found.";
	}
}

/*Remove form bill*/

/*insert master table*/
if (isset($_POST['masterTable'])) {
	echo masterTable($_POST);
}


function masterTable($postval)
{ 
	$Billno = isset($postval['CurrentBill']) ? htmlspecialchars($postval['CurrentBill']) : '';
	$date = date('Y-m-d H:i:s');
	$subtotal = isset($postval['subtotal']) ? htmlspecialchars($postval['subtotal']) : '';
	$gst = isset($postval['gst']) ? htmlspecialchars($postval['gst']) : '';
	$discount = isset($postval['discount']) ? htmlspecialchars($postval['discount']) : '';
	$discountamt = isset($postval['discountamt']) ? htmlspecialchars($postval['discountamt']) : '';
	$tax = isset($postval['tax']) ? htmlspecialchars($postval['tax']) : '';
	$total = isset($postval['total']) ? htmlspecialchars($postval['total']) : '';
	$ac = isset($postval['ac']) ? $postval['ac'] : '';
	$waitername = isset($postval['waitername']) ? $postval['waitername'] : '';
	global $conn; 

	$deleteSql = "DELETE FROM master WHERE billno = '$Billno'";
	if ($conn->query($deleteSql) === TRUE) {
		//echo 'success'; 
	}  

	$insert_sql = "INSERT INTO master (billno, billdate, Subtotal, total,cgst,sgst,Tax,status,accharge,waitername) VALUES ('$Billno', '$date', '$subtotal','$total','2.5','2.5','$tax','COMPLETED','$ac','$waitername')";
	if ($conn->query($insert_sql) === false) {
		return json_encode(['error' => 'Due to some issues']);
	} else {
		$update_query="Update sales SET status='ACTIVE' WHERE status='PENDING' and billno='$Billno'";
		if ($conn->query($update_query) === TRUE) {
			return json_encode(['success' => 'Records updated successfully']);
		} else {
			echo "Error updating records: " . $conn->error;
		} 
	}
}


/*insert master table*/

if (isset($_POST['isProductList'])) {
	echo getAllProductDetails();
}


function getAllProductDetails() {
	global $conn; 
$limit = 20; // Number of records per page

$sdata = isset($_POST['sdata']) ? json_decode($_POST['sdata'], true) : [];
$search_product = isset($sdata['search_product']) ? $sdata['search_product'] : '';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM product WHERE id != ''";

if (!empty($search_product)) {
	$limit = 1000;
	$sql .= " AND brand LIKE '%$search_product%'";
}

$sql .= " ORDER BY CAST(scode AS UNSIGNED) ASC LIMIT $limit OFFSET $offset"; 
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$products[] = $row;
	}
} 
$total_sql = "SELECT COUNT(*) as total FROM product";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$response = [
	'products' => $products,
	'total_pages' => $total_pages,
	'current_page' => $page
];

header('Content-Type: application/json');
echo json_encode($response);
}




if (isset($_POST['addproduct'])) {
	echo addproduct($_POST);
}


if (isset($_POST['iseditProduct'])) {
	echo GetSingleProduct($_POST);
}

function GetSingleProduct()
{
	global $conn; 
	$productID = $_POST['productID'];  
	$result = $conn->query("SELECT  * FROM product WHERE id = '$productID'");
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}
		return json_encode($products);
	}
}

if (isset($_POST['isDeleteProduct'])) {
	echo DeleteProduct($_POST);
}


function DeleteProduct($uniqueId)
{
	global $conn;
	$productID = $_POST['productID'];  
	$deleteSql = "DELETE FROM product WHERE id = '$productID'";
	if ($conn->query($deleteSql) === TRUE) {
		return json_encode(['success' => 'Product Deleted Successfully']);
	} else {
		return json_encode(['error' => 'Product not found']);
	}
}
function addproduct()
{
	global $conn; 
	$name = $_POST['name'];
	$price = $_POST['price'];
	$shortcode = $_POST['scode']; 
	$EditProID = $_POST['EditProID'];
	$tamil = $_POST['tamil'];

	if(isset($EditProID) && $EditProID!="")
	{
		$update_query="Update product SET amt='$price',product='$name',scode='$shortcode',tamil='$tamil' WHERE id='$EditProID'";
		if ($conn->query($update_query) === TRUE) {
			return json_encode(['success' => 'Records updated successfully']);
		} else {
			echo "Error updating records: " . $conn->error;
		}
	}
	else
	{
		$result = $conn->query("SELECT COUNT(*) AS count FROM product WHERE scode = '$shortcode'");
		$row = $result->fetch_assoc();
		if ($row['count'] > 0) { 
			return json_encode(['error' => 'Shortcode already exists']);
		}

		$insert_sql = "INSERT INTO product (product, amt, scode,tamil) VALUES ('$name', '$price', '$shortcode','$tamil')";
		if ($conn->query($insert_sql) === false) {
			return json_encode(['error' => 'Due to some issues']);
		} else {
			return json_encode(['success' => 'Data inserted successfully']);
		}
	}
}

if (isset($_POST['isSalseReport'])) {
	echo getSaleslist();
}


function  getSaleslist()
{
	global $conn;
	$limit = 20000;  
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;

	$sdata = isset($_POST['sdata']) ? json_decode($_POST['sdata'], true) : [];
	$Billno = isset($sdata['bill_search']) ? $sdata['bill_search'] : '';
	$from_date = isset($sdata['from_date']) ? $sdata['from_date'] : date('Y-m-d');
	$to_date = isset($sdata['to_date']) ? $sdata['to_date'] : date('Y-m-d');

	$sql = "SELECT * FROM master WHERE id != '' AND status='COMPLETED' ";

// Search by date range if both dates are provided
	if (!empty($from_date) && !empty($to_date)) {
		$limit = 1000;
		$sql .= " AND billdate BETWEEN '$from_date' AND '$to_date'";
	}

// Search by Billno if provided
	if (!empty($Billno)) { 
		$sql .= " AND Billno = '$Billno'";
	}

	$offset = ($page - 1) * $limit;
	$sql .= " ORDER BY billno DESC LIMIT $limit OFFSET $offset"; 
	$result = $conn->query($sql);

	$products = [];
	if ($result && $result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row;
		}
	}

// Get the total count of records
	$total_sql = "SELECT COUNT(*) as total FROM master WHERE id != ''";

// Apply the same filters to the total count query
	if (!empty($from_date) && !empty($to_date)) {
		$total_sql .= " AND billdate BETWEEN '$from_date' AND '$to_date'";
	}
	if (!empty($Billno)) { 
		$total_sql .= " AND Billno = '$Billno'";
	}

	$total_result = $conn->query($total_sql);
	$total_row = $total_result->fetch_assoc();
	$total_records = $total_row['total'];
	$total_pages = ceil($total_records / $limit);
	$response = [
		'products' => $products,
		'total_pages' => $total_pages,
		'current_page' => $page
	];

	header('Content-Type: application/json');
	echo json_encode($response);
}
if (isset($_POST['viewbill'])) {
	echo viewbill($_POST);
}

function viewbill()
{
	global $conn; 
	$productID = $_POST['productID'];  
	$result = $conn->query("SELECT  * FROM sales WHERE billno = '$productID'");
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}
		return json_encode($products);
	}
}

if (isset($_POST['editbill'])) {
	echo editbill($_POST);
}


function editbill()
{
	$Billno = $_POST['Billno'];
	global $conn;
	$selectSql = "SELECT * FROM sales WHERE billno = '$Billno'";
	$result = $conn->query($selectSql);
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}   
		$endresult['PendingBill'] = $products;
	}  
	$endresult['Billno'] = $Billno; 

	return json_encode($endresult);
}

if (isset($_POST['islogin'])) {
	echo islogin($_POST);
}

function islogin()
{
	$uid = $_POST['uid'];
	$pass = $_POST['pass'];
	global $conn; 
	$selectSql = "SELECT * FROM users WHERE username = '$uid' and password = '$pass'";
	$result = $conn->query($selectSql);
	 $isvalid="Invalid";
	if ($result->num_rows > 0) {
		 $isvalid="valid";
		 $_SESSION['username'] = $uid;
	}  
	$endresult['result'] = $isvalid; 
	return json_encode($endresult);
}



if (isset($_POST['isWaiter'])) {
	echo getAllWaiterDetails();
}

function getAllWaiterDetails() {
	global $conn; 
$limit = 20; // Number of records per page

$sdata = isset($_POST['sdata']) ? json_decode($_POST['sdata'], true) : [];
$search_product = isset($sdata['search_product']) ? $sdata['search_product'] : '';

$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM waiter WHERE id != ''";

if (!empty($search_product)) {
	$limit = 1000;
	$sql .= " AND brand LIKE '%$search_product%'";
}

$sql .= " ORDER BY id ASC LIMIT $limit OFFSET $offset"; 
$result = $conn->query($sql);

$waiterslist = [];
if ($result->num_rows > 0) {
	while($row = $result->fetch_assoc()) {
		$waiterslist[] = $row;
	}
} 
$total_sql = "SELECT COUNT(*) as total FROM waiter";
$total_result = $conn->query($total_sql);
$total_row = $total_result->fetch_assoc();
$total_records = $total_row['total'];
$total_pages = ceil($total_records / $limit);

$response = [
	'products' => $waiterslist,
	'total_pages' => $total_pages,
	'current_page' => $page
];

header('Content-Type: application/json');
echo json_encode($response);
}


if (isset($_POST['addwaiter'])) {
	echo addwaiter();
}

function addwaiter()
{
	global $conn; 
	$name = $_POST['name'];
	$Mobile = $_POST['Mobile'];
	$isacpersion = $_POST['isacpersion']; 
	$EditProID = $_POST['EditProID'];
	$Status = $_POST['Status'];

	if(isset($EditProID) && $EditProID!="")
	{
		$update_query="Update waiter SET Mobile='$Mobile',name='$name',isacpersion='$isacpersion',Status='$Status' WHERE id='$EditProID'";
		if ($conn->query($update_query) === TRUE) {
			return json_encode(['success' => 'Records updated successfully']);
		} else {
			echo "Error updating records: " . $conn->error;
		}
	}
	else
	{
		$result = $conn->query("SELECT COUNT(*) AS count FROM waiter WHERE name = '$name'");
		$row = $result->fetch_assoc();
		if ($row['count'] > 0) { 
			return json_encode(['error' => 'waiter name already exists']);
		}
 		 $insert_sql = "INSERT INTO waiter (name, Mobile, isacpersion,Status) VALUES ('$name', '$Mobile', '$isacpersion','$Status')";
		if ($conn->query($insert_sql) === false) {
			return json_encode(['error' => 'Due to some issues']);
		} else {
			return json_encode(['success' => 'Data inserted successfully']);
		}
	} 
}

if (isset($_POST['deletewaiter'])) {
	echo delete_waiter($_POST);
}

function delete_waiter()
{
	global $conn;
	$productID = $_POST['productID'];  
	 $deleteSql = "DELETE FROM waiter WHERE id = '$productID'";
	if ($conn->query($deleteSql) === TRUE) {
		return json_encode(['success' => 'Witer removed Successfully']);
	} else {
		return json_encode(['error' => 'Product not found']);
	}
}



if (isset($_POST['iseditWaiter'])) {
	echo GetSingleWaiter($_POST);
}

function GetSingleWaiter()
{
	global $conn; 
	$productID = $_POST['productID'];  
	$result = $conn->query("SELECT  * FROM waiter WHERE id = '$productID'");
	$products = [];
	if ($result->num_rows > 0) {
		while($row = $result->fetch_assoc()) {
			$products[] = $row; 
		}
		return json_encode($products);
	}
}

?>