<?php
include 'koneksi.php'; // Pastikan file koneksi.php ada dan berisi koneksi ke database

$response = array();
$response['isSuccess'] = false; // Default value if something goes wrong

// Ambil transaction_id dari parameter GET
if (isset($_GET['transaction_id'])) {
    $transaction_id = $_GET['transaction_id'];

    // Query SQL dengan filter berdasarkan transaction_id
    $query = "SELECT 
        td.id AS detail_id, 
        td.transaction_id, 
        td.product_id, 
        td.quantity, 
        td.price, 
        t.id AS transaction_id, 
        t.employee_id, 
        t.branch_id, 
        t.payment_method, 
        t.total, 
        t.date, 
        p.id AS product_id, 
        p.category_id, 
        p.branch_id, 
        p.name AS product_name, 
        p.stock
    FROM transaction_details td
    JOIN transactions t ON td.transaction_id = t.id
    JOIN products p ON td.product_id = p.id
    WHERE t.id = $transaction_id"; // Filter berdasarkan transaction_id

    $result = $koneksi->query($query);

    if ($result->num_rows > 0) {
        $response['isSuccess'] = true;
        $response['message'] = "Berhasil Menampilkan Data Transaksi dengan ID $transaction_id";
        while ($row = $result->fetch_assoc()) {
            $response['data'][] = $row;
        }
    } else {
        $response['message'] = "Tidak ada data transaksi dengan ID $transaction_id";
    }
} else {
    $response['message'] = "Parameter transaction_id tidak ditemukan";
}

echo json_encode($response);

$koneksi->close();
?>
