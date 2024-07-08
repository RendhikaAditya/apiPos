<?php
header('Content-Type: application/json');
include 'koneksi.php';

$response = array('isSuccess' => false, 'message' => '');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $koneksi->prepare("SELECT * FROM branches WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if ($result) {
                $response['data'] = $result->fetch_assoc();
                $response['isSuccess'] = true;
            } else {
                $response['message'] = 'Gagal mengambil data cabang';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } else {
        $result = $koneksi->query("SELECT * FROM branches");
        if ($result) {
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['isSuccess'] = true;
        } else {
            $response['message'] = 'Gagal mengambil data cabang';
        }
    }
} elseif ($method == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $branch_name = $_POST['branch_name'];
        $branch_address = $_POST['branch_address'];

        $query = $koneksi->prepare("INSERT INTO branches (branch_name, branch_address) VALUES (?, ?)");
        if ($query) {
            $query->bind_param("ss", $branch_name, $branch_address);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Cabang berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah cabang';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $branch_name = $_POST['branch_name'];
        $branch_address = $_POST['branch_address'];

        $query = $koneksi->prepare("UPDATE branches SET branch_name = ?, branch_address = ? WHERE id = ?");
        if ($query) {
            $query->bind_param("ssi", $branch_name, $branch_address, $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Cabang berhasil diubah';
            } else {
                $response['message'] = 'Gagal mengubah cabang';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = $koneksi->prepare("DELETE FROM branches WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Cabang berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus cabang';
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
