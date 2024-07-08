<?php

header("Access-Control-Allow-Origin: header");
header("Access-Control-Allow-Origin: *");

include 'koneksi.php';

    $id = $_GET['id'];
	$sql = "SELECT * FROM `favorites`
            LEFT JOIN products ON `favorites`.`id_product`=`products`.`id`
            WHERE favorites.id_branch=$id;";
	$result = $koneksi->query($sql);

	if($result->num_rows > 0) {
		$response['isSuccess'] = true;
		$response['message'] = "Berhasil Menampilkan Kategori";
		$response['data'] = array();
		while ($row = $result->fetch_assoc()) {
			$response['data'][] = $row;
		}
	} else {
		$response['isSuccess'] = false;
		$response['message'] = "Gagal menampilkan Kategori";
		$response['data'] = null;
	}
	echo json_encode($response);


?>