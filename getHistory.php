<?php
include 'koneksi.php';

$response = array();
$response['isSuccess'] = false;
$response['message'] = '';
$response['data'] = array();

if (isset($_GET['date1']) && isset($_GET['date2'])) {
    $date1 = $_GET['date1'];
    $date2 = $_GET['date2'];
    $sql = "SELECT * FROM transactions WHERE DATE(date) BETWEEN DATE('$date1') AND DATE('$date2')";
} elseif (isset($_GET['date1'])) {
    $date1 = $_GET['date1'];
    $sql = "SELECT * FROM transactions WHERE DATE(date) = DATE('$date1')";
} else {
    $sql = "SELECT * FROM transactions";
}

$result = $koneksi->query($sql);

if ($result->num_rows > 0) {
    $response['isSuccess'] = true;
    $response['message'] = "Berhasil Menampilkan Data Transaksi";
    while ($row = $result->fetch_assoc()) {
        $response['data'][] = $row;
    }
} else {
    $response['message'] = "Tidak ada data transaksi yang ditemukan";
}

echo json_encode($response);
?>
