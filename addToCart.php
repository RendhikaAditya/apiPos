<?php
include 'koneksi.php';

$response = array();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $employe_id = isset($_POST['employe_id']) ? intval($_POST['employe_id']) : 0;
    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : null;
    $qty = isset($_POST['qty']) ? intval($_POST['qty']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0.0;

        if ($product_id !== null) {
            // Check if the product already exists in the cart for the same employee
            $sql = "SELECT qty FROM carts WHERE employe_id = ? AND product_id = ? AND price = ?";
            $stmt = $koneksi->prepare($sql);
            $stmt->bind_param("iii", $employe_id, $product_id, $price);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                // Product exists, update or delete
                $row = $result->fetch_assoc();
                $current_qty = $row['qty'];
                $new_qty = $current_qty + $qty;

                if ($new_qty > 0) {
                    // Update quantity and price
                    $sql_update = "UPDATE carts SET qty = ?, price = ? WHERE employe_id = ? AND product_id = ? AND price = ?";
                    $stmt_update = $koneksi->prepare($sql_update);
                    $stmt_update->bind_param("idiii", $new_qty, $price, $employe_id, $product_id, $price);
                    if ($stmt_update->execute()) {
                        $response['isSuccess'] = true;
                        $response['message'] = "Quantity updated successfully";
                    } else {
                        $response['isSuccess'] = false;
                        $response['message'] = "Failed to update quantity";
                    }
                    $stmt_update->close();
                } else {
                    // Delete product from cart
                    $sql_delete = "DELETE FROM carts WHERE employe_id = ? AND product_id = ?  AND price = ?";
                    $stmt_delete = $koneksi->prepare($sql_delete);
                    $stmt_delete->bind_param("iii", $employe_id, $product_id, $price);
                    if ($stmt_delete->execute()) {
                        $response['isSuccess'] = true;
                        $response['message'] = "Product removed from cart";
                    } else {
                        $response['isSuccess'] = false;
                        $response['message'] = "Failed to remove product from cart";
                    }
                    $stmt_delete->close();
                }
            } else {
                // Product does not exist, insert new
                $sql_insert = "INSERT INTO carts (employe_id, product_id, qty, price) VALUES (?, ?, ?, ?)";
                $stmt_insert = $koneksi->prepare($sql_insert);
                $stmt_insert->bind_param("iiid", $employe_id, $product_id, $qty, $price);
                if ($stmt_insert->execute()) {
                    $response['isSuccess'] = true;
                    $response['message'] = "Product added to cart successfully";
                } else {
                    $response['isSuccess'] = false;
                    $response['message'] = "Failed to add product to cart";
                }
                $stmt_insert->close();
            }

            $stmt->close();
        } else {
            // Insert without product_id
            $sql_insert = "INSERT INTO carts (employe_id, qty, price) VALUES (?, ?, ?)";
            $stmt_insert = $koneksi->prepare($sql_insert);
            $stmt_insert->bind_param("iid", $employe_id, $qty, $price);
            if ($stmt_insert->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = "Product added to cart successfully";
            } else {
                $response['isSuccess'] = false;
                $response['message'] = "Failed to add product to cart";
            }
            $stmt_insert->close();
        }
    
} else {
    $response['isSuccess'] = false;
    $response['message'] = "Invalid request method";
}

header('Content-Type: application/json');
echo json_encode($response);

$koneksi->close();
?>
