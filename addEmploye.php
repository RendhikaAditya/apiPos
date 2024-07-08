<?php
header('Content-Type: application/json');

// Include file koneksi
include 'koneksi.php';

$response = array(
    'isSuccess' => false,
    'message' => 'Gagal menambah karyawan',
    'data' => null
);

if (isset($_POST['branch_id'], $_POST['username'], $_POST['password'])) {
    $branch_id = $koneksi->real_escape_string($_POST['branch_id']);
    $username = $koneksi->real_escape_string($_POST['username']);
    $password = password_hash($koneksi->real_escape_string($_POST['password']), PASSWORD_BCRYPT);
    $role = "Empoyee";

    $sql = "INSERT INTO employees (branch_id, username, password, role) VALUES ('$branch_id', '$username', '$password', '$role')";

    if ($koneksi->query($sql) === TRUE) {
        $response['isSuccess'] = true;
        $response['message'] = 'Karyawan berhasil ditambah';
    } else {
        $response['isSuccess'] = false;
        $response['message'] = 'Eksekusi query gagal: ' . $koneksi->error;
    }
} else {
    $response['isSuccess'] = false;
    $response['message'] = 'Data tidak lengkap';
}

$koneksi->close();

echo json_encode($response);
?>
