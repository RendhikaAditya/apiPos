<?php
include 'koneksi.php';

$response = array();
$employe_id = isset($_GET['employe_id']) ? intval($_GET['employe_id']) : 0;

if ($employe_id === 0) {
    $response['isSuccess'] = false;
    $response['message'] = "Invalid employe_id";
    $response['total_qty'] = 0;
    $response['total_price'] = 0;
} else {
    $sql = "SELECT 
                SUM(cr.qty) AS total_qty,
                SUM(cr.qty * cr.price) AS total_price
            FROM 
                carts cr
            WHERE 
                cr.employe_id = ?";

    $stmt = $koneksi->prepare($sql);
    $stmt->bind_param("i", $employe_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $response['isSuccess'] = true;
        $response['message'] = "Berhasil Mendapatkan Summary Keranjang";
        $response['total_qty'] = intval($row['total_qty']);
        $response['total_price'] = intval($row['total_price']);
    } else {
        $response['isSuccess'] = false;
        $response['message'] = "Keranjang kosong";
        $response['total_qty'] = 0;
        $response['total_price'] = 0;
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

$koneksi->close();
?>
