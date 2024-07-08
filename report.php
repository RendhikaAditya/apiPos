<?php
include 'koneksi.php';

// Query untuk mendapatkan list nama produk yang terjual beserta omset dan persentase dari seluruh penjualan
$query_products = "
    SELECT
        p.name AS product_name,
        SUM(td.quantity * td.price) AS total_sales,
        COUNT(td.id) AS total_quantity,
        SUM(td.quantity * td.price) / (SELECT SUM(td2.quantity * td2.price) FROM transaction_details td2) * 100 AS sales_percentage
    FROM transaction_details td
    JOIN products p ON td.product_id = p.id
    GROUP BY td.product_id
    ORDER BY total_sales DESC
";

$result_products = $koneksi->query($query_products);

$products = [];
while ($row = $result_products->fetch_assoc()) {
    $products[] = $row;
}

// Query untuk mendapatkan list payment_method beserta omset dan persentase dari seluruh transaksi
$query_payment_methods = "
    SELECT
        t.payment_method,
        COUNT(t.id) AS total_transactions,
        COUNT(t.id) / (SELECT COUNT(t2.id) FROM transactions t2) * 100 AS transactions_percentage,
        SUM(t.total) AS total_sales
    FROM transactions t
    GROUP BY t.payment_method
    ORDER BY total_transactions DESC
";

$result_payment_methods = $koneksi->query($query_payment_methods);

$payment_methods = [];
while ($row = $result_payment_methods->fetch_assoc()) {
    $payment_methods[] = $row;
}

$response = [
    'stats' => true,
    'products' => $products,
    'payment_methods' => $payment_methods
];

header('Content-Type: application/json');
echo json_encode($response);

$koneksi->close();
?>
