<?php
header('Content-Type: application/json');
include 'koneksi.php';

$response = array('isSuccess' => false, 'message' => '');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $koneksi->prepare("SELECT * FROM customers WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if ($result) {
                $response['data'] = $result->fetch_assoc();
                $response['isSuccess'] = true;
            } else {
                $response['message'] = 'Gagal mengambil data pelanggan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } else {
        $result = $koneksi->query("SELECT * FROM customers");
        if ($result) {
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['isSuccess'] = true;
        } else {
            $response['message'] = 'Gagal mengambil data pelanggan';
        }
    }
} elseif ($method == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $customers_name = $_POST['customers_name'];
        $phone_number = $_POST['phone_number'];
        $customers_email = $_POST['customers_email'];
        $customers_address = $_POST['customers_address'];

        $query = $koneksi->prepare("INSERT INTO customers (customers_name, phone_number, customers_email, customers_address) VALUES (?, ?, ?, ?)");
        if ($query) {
            $query->bind_param("siss", $customers_name, $phone_number, $customers_email, $customers_address);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Pelanggan berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah pelanggan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $customers_name = $_POST['customers_name'];
        $phone_number = $_POST['phone_number'];
        $customers_email = $_POST['customers_email'];
        $customers_address = $_POST['customers_address'];

        $query = $koneksi->prepare("UPDATE customers SET customers_name = ?, phone_number = ?, customers_email = ?, customers_address = ? WHERE id = ?");
        if ($query) {
            $query->bind_param("sissi", $customers_name, $phone_number, $customers_email, $customers_address, $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Pelanggan berhasil diubah';
            } else {
                $response['message'] = 'Gagal mengubah pelanggan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = $koneksi->prepare("DELETE FROM customers WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Pelanggan berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus pelanggan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } else {
        $response['message'] = 'Aksi tidak dikenal';
    }
} else {
    $response['message'] = 'Metode HTTP tidak diizinkan';
}

echo json_encode($response);
?>
