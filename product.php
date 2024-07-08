<?php
include 'koneksi.php';

$response = array();
$employe_id = isset($_GET['employe_id']) ? intval($_GET['employe_id']) : 0;
$branch_id = isset($_GET['branch_id']) ? intval($_GET['branch_id']) : 0;
$nameCategory = isset($_GET['nameCategory']) ? $_GET['nameCategory'] : '';

if ($branch_id === 0) {
    $response['isSuccess'] = false;
    $response['message'] = "Branch ID tidak valid";
    $response['data'] = null;
} else {
    $sql = "SELECT 
                p.id,
                p.name,
                p.description,
                p.price,
                p.stock,
                p.image,
                c.name_category,
                c.description_category,
                COALESCE(cr.qty, 0) as qty
            FROM 
                products p
            LEFT JOIN 
                categories c ON p.category_id = c.id
            LEFT JOIN 
                (SELECT product_id, qty FROM carts WHERE employe_id = ?) cr ON p.id = cr.product_id
            WHERE
                p.branch_id = ?";

    if (!empty($nameCategory)) {
        $sql .= " AND c.name_category = ?";
    }

    $stmt = $koneksi->prepare($sql);
    
    if (!empty($nameCategory)) {
        $stmt->bind_param("iis", $employe_id, $branch_id, $nameCategory);
    } else {
        $stmt->bind_param("ii", $employe_id, $branch_id);
    }

    $stmt->execute();
    $result = $stmt->get_result();

    $products = array();
    $total_qty = 0;
    $total_price = 0;

    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
        $total_qty += $row['qty'];
        $total_price += $row['qty'] * $row['price'];
    }

    if (count($products) > 0) {
        $response['isSuccess'] = true;
        $response['message'] = "Berhasil Menampilkan Produk";
        $response['data'] = $products;
        $response['total_qty'] = $total_qty;
        $response['total_price'] = $total_price;
    } else {
        $response['isSuccess'] = false;
        $response['message'] = "Gagal Menampilkan Produk";
        $response['data'] = null;
        $response['total_qty'] = 0;
        $response['total_price'] = 0;
    }

    $stmt->close();
}

header('Content-Type: application/json');
echo json_encode($response);

$koneksi->close();
?>
