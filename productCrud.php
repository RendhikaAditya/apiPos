<?php
header('Content-Type: application/json');
include 'koneksi.php';

$response = array('isSuccess' => false, 'message' => '');

$method = $_SERVER['REQUEST_METHOD'];

function saveImage($base64Image) {
    $imageName = uniqid() . '.png';
    $imagePath = __DIR__ . '/image/' . $imageName;
    $decodedImage = base64_decode($base64Image);
    file_put_contents($imagePath, $decodedImage);
    return $imageName;
}

if ($method == 'GET') {
    $id = $_GET['id'];

    $result = $koneksi->query("SELECT * FROM products WHERE id!=0 AND branch_id=".$id);
    if ($result) {
        $response['data'] = $result->fetch_all(MYSQLI_ASSOC);
        $response['isSuccess'] = true;
    } else {
        $response['message'] = 'Gagal mengambil data produk';
    }
} elseif ($method == 'POST') {
    $action = $_POST['action'];

    if ($action == 'create') {
        $category_id = $_POST['category_id'];
        $branch_id = $_POST['branch_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = saveImage($_POST['image']);

        $query = $koneksi->prepare("INSERT INTO products (category_id, branch_id, name, description, price, stock, image) VALUES (?, ?, ?, ?, ?, ?, ?)");
        if ($query) {
            $query->bind_param("iisssis", $category_id, $branch_id, $name, $description, $price, $stock, $image);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Produk berhasil ditambah';
            } else {
                $response['message'] = 'Gagal menambah produk';
            }
        } else {
            $response['message'] = 'Gagal mempersiapkan query';
        }
    } elseif ($action == 'update') {
        $id = $_POST['id'];
        $category_id = $_POST['category_id'];
        $branch_id = $_POST['branch_id'];
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $stock = $_POST['stock'];
        $image = isset($_POST['image']) ? saveImage($_POST['image']) : "null";
    
        if ($image !== "null") {
            $query = $koneksi->prepare("UPDATE products SET category_id = ?, branch_id = ?, name = ?, description = ?, price = ?, stock = ?, image = ? WHERE id = ?");
            if ($query) {
                $query->bind_param("iisssisi", $category_id, $branch_id, $name, $description, $price, $stock, $image, $id);
            }
        } else {
            $query = $koneksi->prepare("UPDATE products SET category_id = ?, branch_id = ?, name = ?, description = ?, price = ?, stock = ? WHERE id = ?");
            if ($query) {
                $query->bind_param("iisssii", $category_id, $branch_id, $name, $description, $price, $stock, $id);
            }
        }
    
        if ($query && $query->execute()) {
            $response['isSuccess'] = true;
            $response['message'] = 'Produk berhasil diubah';
        } else {
            $response['message'] = 'Gagal mengubah produk';
        }
    } elseif ($action == 'delete') {
        $id = $_POST['id'];
        $query = $koneksi->prepare("DELETE FROM products WHERE id = ?");
        if ($query) {
            $query->bind_param("i", $id);
            if ($query->execute()) {
                $response['isSuccess'] = true;
                $response['message'] = 'Produk berhasil dihapus';
            } else {
                $response['message'] = 'Gagal menghapus produk';
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
