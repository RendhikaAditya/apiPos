<?php
header('Content-Type: application/json');
include 'koneksi.php';

$response = array('isSuccess' => false, 'message' => '');

$method = $_SERVER['REQUEST_METHOD'];

if ($method == 'GET') {
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $query = $koneksi->prepare("SELECT * FROM employees WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            $query->execute();
            $result = $query->get_result();
            if ($result) {
                $response['data'] = $result->fetch_assoc();
                $response['isSuccess'] = true;
            } else {
                $response['message'] = 'Gagal mengambil data karyawan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } else {
        $result = $koneksi->query("SELECT `employees`.*, `branches`.`branch_name`, `branches`.`branch_address` FROM `employees` LEFT JOIN `branches` ON `branches`.`id`=`employees`.`branch_id`");
        if ($result) {
            $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
            $response['isSuccess'] = true;
        } else {
            $response['message'] = 'Gagal mengambil data karyawan';
        }
    }
} elseif ($method == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $branch_id = $_POST['branch_id'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = "Employee";

        $query = $koneksi->prepare("INSERT INTO employees (branch_id, username, password, role) VALUES (?, ?, ?, ?)");
        if ($query) {
            $query->bind_param("isss", $branch_id, $username, $password, $role);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Karyawan berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah karyawan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $branch_id = $_POST['branch_id'];
        $username = $_POST['username'];
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $role = "Employee";

        $query = $koneksi->prepare("UPDATE employees SET branch_id = ?, username = ?, password = ?, role = ? WHERE id = ?");
        if ($query) {
            $query->bind_param("isssi", $branch_id, $username, $password, $role, $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Karyawan berhasil diubah';
            } else {
                $response['message'] = 'Gagal mengubah karyawan';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = $koneksi->prepare("DELETE FROM employees WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Karyawan berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus karyawan';
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
