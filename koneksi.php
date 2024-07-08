<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
// Informasi koneksi database
$host = "localhost"; // Lokasi database (biasanya localhost)
$username = "root"; // Username database
$password = ""; // Password database (kosongkan jika tidak ada)
$database = "pos_db"; // Nama database

// Membuat koneksi
$koneksi = new mysqli($host, $username, $password, $database);

// Memeriksa koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Jika koneksi berhasil
// echo "Koneksi berhasil terhubung ke database '$database'.";
?>
