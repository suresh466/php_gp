<?php
require 'db_conn.php';
require('fpdf/fpdf.php');
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Create PDF document
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Order Details',0,1,'C');

$db = new DatabaseConnection();

$user_id = $_SESSION['user_id']; 

// Query to fetch user details
$user_query = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = mysqli_prepare($db->get_dbc(), $user_query);
mysqli_stmt_bind_param($user_stmt, 'i', $user_id);
mysqli_stmt_execute($user_stmt);
$user_result = mysqli_stmt_get_result($user_stmt);
$user_row = mysqli_fetch_assoc($user_result);

// Display user details in PDF
$pdf->Cell(0,10,'User Details:',0,1);
$pdf->Cell(0,10,'Name: ' . $user_row['first_name'] . ' ' . $user_row['last_name'],0,1);
$pdf->Cell(0,10,'Email: ' . $user_row['email'],0,1);
$pdf->Cell(0,10,'Phone: ' . $user_row['phone'],0,1);

$pdf->Ln(); // Add a line break

// Query to fetch order details
$order_query = "SELECT * FROM orders WHERE user_id = ?";
$order_stmt = mysqli_prepare($db->get_dbc(), $order_query);
mysqli_stmt_bind_param($order_stmt, 'i', $user_id);
mysqli_stmt_execute($order_stmt);
$order_result = mysqli_stmt_get_result($order_stmt);
$order_row = mysqli_fetch_assoc($order_result);

$order_id = $order_row['order_id'];

// Query to fetch order items and related shoe details
$item_query = "SELECT oi.quantity, s.shoe_name, s.shoe_price, s.shoe_size, s.shoe_color, s.shoe_brand
               FROM order_items oi
               JOIN shoes s ON oi.shoe_id = s.shoe_id
               WHERE oi.order_id = ?";
$item_stmt = mysqli_prepare($db->get_dbc(), $item_query);
mysqli_stmt_bind_param($item_stmt, 'i', $order_id);
mysqli_stmt_execute($item_stmt);
$item_result = mysqli_stmt_get_result($item_stmt);

// Initialize total price variable
$total_price = 0;

// Display order details in PDF table
$pdf->SetFont('Arial','B',12);
$pdf->Cell(45,10,'Shoe Name',1,0,'C');
$pdf->Cell(25,10,'Price',1,0,'C');
$pdf->Cell(20,10,'Size',1,0,'C');
$pdf->Cell(30,10,'Color',1,0,'C');
$pdf->Cell(40,10,'Brand',1,0,'C');
$pdf->Cell(20,10,'Quantity',1,1,'C');

$pdf->SetFont('Arial','',12);
while ($item_row = mysqli_fetch_assoc($item_result)) {
    $pdf->Cell(45,10,$item_row['shoe_name'],1,0);
    $pdf->Cell(25,10,'$' . $item_row['shoe_price'],1,0);
    $pdf->Cell(20,10,$item_row['shoe_size'],1,0);
    $pdf->Cell(30,10,$item_row['shoe_color'],1,0);
    $pdf->Cell(40,10,$item_row['shoe_brand'],1,0);
    $pdf->Cell(20,10,$item_row['quantity'],1,1);
    
    // Calculate total price
    $total_price += $item_row['shoe_price'] * $item_row['quantity'];
}

// Display total price
$pdf->SetFont('Arial','B',12);
$pdf->Cell(165,10,'Total Price',1,0,'R');
$pdf->Cell(20,10,'$' . number_format($total_price, 2),1,1,'C');

// Output PDF as download
$pdf->Output('D', 'order_details.pdf');
?>
