<?php
header('Content-Type: application/json');
include 'koneksi.php';

$response = array('isSuccess' => false, 'message' => '');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $koneksi->prepare("SELECT * FROM categories WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if ($result) {
                $response['data'] = $result->fetch_assoc();
                $response['isSuccess'] = true;
            } else {
                $response['message'] = 'Gagal mengambil data kategori';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } else {
        $result = $koneksi->query("SELECT * FROM categories");
        if ($result) {
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['isSuccess'] = true;
        } else {
            $response['message'] = 'Gagal mengambil data kategori';
        }
    }
} elseif ($method == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $name_category = $_POST['name_category'];
        $description_category = $_POST['description_category'];

        $query = $koneksi->prepare("INSERT INTO categories (name_category, description_category) VALUES (?, ?)");
        if ($query) {
            $query->bind_param("ss", $name_category, $description_category);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Kategori berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah kategori';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $name_category = $_POST['name_category'];
        $description_category = $_POST['description_category'];

        $query = $koneksi->prepare("UPDATE categories SET name_category = ?, description_category = ? WHERE id = ?");
        if ($query) {
            $query->bind_param("ssi", $name_category, $description_category, $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Kategori berhasil diubah';
            } else {
                $response['message'] = 'Gagal mengubah kategori';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = $koneksi->prepare("DELETE FROM categories WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Kategori berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus kategori';
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
