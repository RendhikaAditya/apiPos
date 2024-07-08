<?php
// Include database connection
include 'koneksi.php';

// Check if POST data is received
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate and sanitize input
    $employee_id = isset($_POST['employee_id']) ? intval($_POST['employee_id']) : null;
    $branch_id = isset($_POST['branch_id']) ? intval($_POST['branch_id']) : null;
    $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : null;

    // Check if required fields are not empty
    if ($employee_id && $branch_id && $payment_method) {
        // Start transaction for atomicity
        mysqli_autocommit($koneksi, false);

        // Insert into transactions table
        $total = 0; // Calculate total from carts
        $date = date('Y-m-d H:i:s'); // Current timestamp

        // Calculate total from carts
        $total_query = "SELECT SUM(price * qty) AS total FROM carts WHERE employe_id = $employee_id";
        $total_result = mysqli_query($koneksi, $total_query);
        if ($total_result) {
            $total_row = mysqli_fetch_assoc($total_result);
            $total = $total_row['total'];
        } else {
            // Handle error
            $error_message = mysqli_error($koneksi);
            mysqli_close($koneksi);
            $response['isSuccess'] = false;
            $response['message'] = "Error calculating total: $error_message";
            echo json_encode($response);
            exit;
        }

        // Insert into transactions table
        $insert_transaction_query = "INSERT INTO transactions (employee_id, branch_id, payment_method, total, date)
                                    VALUES ($employee_id, $branch_id, '$payment_method', $total, '$date')";
        $insert_transaction_result = mysqli_query($koneksi, $insert_transaction_query);
        if ($insert_transaction_result) {
            // Get auto-generated transaction_id
            $transaction_id = mysqli_insert_id($koneksi);

            // Insert into transaction_details table
            $insert_details_query = "INSERT INTO transaction_details (transaction_id, product_id, quantity, price)
                                    SELECT $transaction_id, product_id, qty, price FROM carts WHERE employe_id = $employee_id";
            $insert_details_result = mysqli_query($koneksi, $insert_details_query);

            if ($insert_details_result) {
                // Delete from carts table
                $delete_cart_query = "DELETE FROM carts WHERE employe_id = $employee_id";
                $delete_cart_result = mysqli_query($koneksi, $delete_cart_query);

                if ($delete_cart_result) {
                    // Commit transaction
                    mysqli_commit($koneksi);

                    // Prepare success response
                    $response['isSuccess'] = true;
                    $response['message'] = "Checkout successful";
                    echo json_encode($response);
                } else {
                    // Rollback transaction on failure
                    mysqli_rollback($koneksi);
                    $error_message = mysqli_error($koneksi);
                    $response['isSuccess'] = false;
                    $response['message'] = "Error deleting cart items: $error_message";
                    echo json_encode($response);
                }
            } else {
                // Rollback transaction on failure
                mysqli_rollback($koneksi);
                $error_message = mysqli_error($koneksi);
                $response['isSuccess'] = false;
                $response['message'] = "Error inserting transaction details: $error_message";
                echo json_encode($response);
            }
        } else {
            // Rollback transaction on failure
            mysqli_rollback($koneksi);
            $error_message = mysqli_error($koneksi);
            $response['isSuccess'] = false;
            $response['message'] = "Error inserting transaction : $error_message";
            echo json_encode($response);
        }

        // Close database connection
        mysqli_close($koneksi);
    } else {
        // Invalid request parameters
        $response['isSuccess'] = false;
        $response['message'] = "Invalid input parameters "+$employe_id;
        echo json_encode($response);
    }
} else {
    // Method not allowed
    http_response_code(405);
    echo json_encode(['message' => 'Method Not Allowed']);
}
?>
