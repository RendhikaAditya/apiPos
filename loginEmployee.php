<?php

include 'koneksi.php';

$response = [
    'isSuccess' => false,
    'message' => "Tidak Ada Data",
    'data' => null
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Query untuk mendapatkan data user berdasarkan username
        $query = "SELECT * FROM employees WHERE username = ?";
        if ($stmt = $koneksi->prepare($query)) {
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();

                // Verifikasi password
                if (password_verify($password, $row['password'])) {
                    $response['isSuccess'] = true;
                    $response['message'] = "Login Berhasil";
                    $response['data'] = [
                        'id' => $row['id'],
                        'username' => $row['username'],
                        'branchId' => $row['branch_id']
                    ];
                } else {
                    $response['message'] = "Password Salah";
                }
            } else {
                $response['message'] = "Username Tidak Ditemukan";
            }
            $stmt->close();
        } else {
            $response['message'] = "Query Error";
        }
    } else {
        $response['message'] = "Username dan Password harus diisi";
    }
} else {
    $response['message'] = "Metode Permintaan Tidak Valid";
}

header('Content-Type: application/json');
echo json_encode($response);
?>
