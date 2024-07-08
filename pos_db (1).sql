-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Waktu pembuatan: 08 Jul 2024 pada 10.51
-- Versi server: 10.4.28-MariaDB
-- Versi PHP: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pos_db`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `branches`
--

CREATE TABLE `branches` (
  `id` int(11) NOT NULL,
  `branch_name` varchar(100) NOT NULL,
  `branch_address` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `branch_address`) VALUES
(1, 'Cabang Jakarta Pusat', 'Jl. Thamrin No. 10, Jakarta Pusat'),
(2, 'Cabang Bandung Dago', 'Jl. Ir. H. Djuanda No. 15, Bandung'),
(3, 'Cabang Surabaya Tunjungan', 'Jl. Tunjungan No. 20, Surabaya'),
(4, 'Cabang Medan Merdeka', 'Jl. Merdeka No. 5, Medan'),
(5, 'Cabang Makassar Pettarani', 'Jl. Pettarani No. 7, Makassar');

-- --------------------------------------------------------

--
-- Struktur dari tabel `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `employe_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `qty` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktur dari tabel `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name_category` varchar(50) NOT NULL,
  `description_category` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `categories`
--

INSERT INTO `categories` (`id`, `name_category`, `description_category`) VALUES
(1, 'Minuman', 'Berbagai jenis minuman kopi, teh, dan lainnya'),
(2, 'Makanan Ringan', 'Berbagai jenis makanan ringan dan camilan'),
(3, 'Makanan Berat', 'Berbagai jenis makanan utama dan berat'),
(4, 'Kue dan Roti', 'Berbagai jenis kue dan roti'),
(5, 'Paket Menu', 'Paket menu spesial');

-- --------------------------------------------------------

--
-- Struktur dari tabel `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customers_name` varchar(255) NOT NULL,
  `phone_number` int(11) NOT NULL,
  `customers_email` varchar(255) NOT NULL,
  `customers_address` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `customers`
--

INSERT INTO `customers` (`id`, `customers_name`, `phone_number`, `customers_email`, `customers_address`) VALUES
(2, 'Rusdi Agusma', 2147483647, 'rusdi@mail.com', 'Siteba Padang ');

-- --------------------------------------------------------

--
-- Struktur dari tabel `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('owner','employee') NOT NULL DEFAULT 'employee'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `employees`
--

INSERT INTO `employees` (`id`, `branch_id`, `username`, `password`, `role`) VALUES
(1, 1, 'Rendhika', '$2y$10$EETHpNcKz1uGwo/YCqwrxuU8cIaqST6VhXquZTQ395bY/42O3UF5.', 'employee'),
(3, 5, 'aditya', '$2y$10$HXfeKmK.q1Y81qb6xiV16ejDlKsxZ5NY1dLeKpEUWzNYQhdFLSBpC', 'employee'),
(4, 2, 'agusa', '$2y$10$YNOyzHNmS/9NfUG3udL8tu1MMwOqPJtR5lhFVWOkqUZiCV4S3sSOu', 'employee'),
(8, 5, 'Siti Hajar', '$2y$10$ZXwkTaOcXc21q0cCVRI6Ju5DQHaNEOj8ND278eEmUNSYd9LKrG0yi', 'employee'),
(10, 3, 'pulan', '$2y$10$Sk5wYFqQtUWPzdX6OhnT1.Ipv2fsdYRvopVXl5AZ6B/gurK62Ydum', 'employee');

-- --------------------------------------------------------

--
-- Struktur dari tabel `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `id_product` int(11) NOT NULL,
  `id_branch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `favorites`
--

INSERT INTO `favorites` (`id`, `id_product`, `id_branch`) VALUES
(1, 1, 1),
(2, 3, 1),
(3, 6, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `owners`
--

CREATE TABLE `owners` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `owners`
--

INSERT INTO `owners` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$MuxjODVP52sRszRJOjkDMOZuz/GHYniJNWyh3EQp92ouCIIjHTiOG');

-- --------------------------------------------------------

--
-- Struktur dari tabel `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `products`
--

INSERT INTO `products` (`id`, `category_id`, `branch_id`, `name`, `description`, `price`, `stock`, `image`) VALUES
(0, 1, 5, 'Additional', NULL, 0, 0, NULL),
(1, 1, 1, 'Kopi Americano', 'Kopi hitam dengan cita rasa khas', 25000, 100, 'kopi_americano.jpg'),
(2, 1, 2, 'Latte', 'Kopi dengan susu yang creamy', 30000, 80, 'latte.jpg'),
(3, 2, 1, 'Croissant', 'Roti croissant dengan tekstur renyah', 15000, 50, 'croissant.jpg'),
(4, 2, 2, 'Donat Coklat', 'Donat dengan topping coklat', 12000, 60, 'donat_coklat.jpg'),
(5, 3, 2, 'Nasi Goreng Spesial', 'Nasi goreng dengan bumbu spesial', 45000, 40, 'nasi_goreng_spesial.jpeg'),
(6, 3, 1, 'Spaghetti Bolognese', 'Spaghetti dengan saus bolognese', 55000, 30, 'spaghetti_bolognese.jpg'),
(7, 4, 2, 'Cheesecake', 'Kue keju dengan rasa lezat', 30000, 20, 'cheesecake.jpg'),
(8, 4, 1, 'Brownies', 'Kue brownies coklat', 25000, 25, 'brownies.jpg'),
(9, 5, 1, 'Paket Sarapan', 'Paket sarapan dengan kopi dan roti', 40000, 50, 'paket_sarapan.jpg'),
(10, 5, 1, 'Paket Makan Siang', 'Paket makan siang dengan nasi dan minuman', 60000, 40, 'paket_makan_siang.jpg'),
(15, 3, 2, 'adosa dao', 'ad', 1000, 12, '66874e378ef4e.png'),
(16, 3, 3, 'nasi goreng ayam', 'nasi goreng dengan ayam blak paper dan telur', 20000, 25, '668b9dc644530.png');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) DEFAULT NULL,
  `branch_id` int(11) DEFAULT NULL,
  `payment_method` varchar(225) NOT NULL,
  `total` int(11) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transactions`
--

INSERT INTO `transactions` (`id`, `employee_id`, `branch_id`, `payment_method`, `total`, `date`) VALUES
(8, 1, 1, 'Cash', 160000, '2024-07-01 03:44:58'),
(9, 1, 1, 'Cash', 45000, '2024-07-01 04:23:43'),
(10, 1, 1, 'Cash', 25000, '2024-07-01 04:26:35'),
(11, 1, 1, 'Cash', 25000, '2024-07-01 04:32:37'),
(12, 1, 1, 'Cash', 25000, '2024-07-01 04:36:13'),
(13, 1, 1, 'Cash', 25000, '2024-07-01 04:37:13'),
(14, 1, 1, 'Cash', 55000, '2024-07-01 04:38:47'),
(15, 1, 1, 'Cash', 15000, '2024-07-01 04:39:08'),
(16, 1, 1, 'Cash', 40000, '2024-07-01 04:41:51'),
(17, 1, 1, 'Cash', 25000, '2024-07-01 04:52:49'),
(18, 1, 1, 'BRI', 15000, '2024-07-01 04:53:52'),
(19, 1, 1, 'Dana', 25000, '2024-07-01 05:06:11'),
(20, 1, 1, 'Cash', 10000, '2024-07-01 05:07:57'),
(21, 1, 1, 'Cash', 15000, '2024-07-01 05:08:36'),
(22, 1, 1, 'Shopee Pay', 45000, '2024-07-01 09:43:17'),
(23, 1, 1, 'BCA', 100000, '2024-07-08 02:29:48'),
(24, 4, 2, 'BNI', 72000, '2024-07-08 02:37:07'),
(25, 4, 2, 'Cash', 60000, '2024-07-08 02:38:11'),
(26, 4, 2, 'Cash', 47000, '2024-07-08 03:02:22');

-- --------------------------------------------------------

--
-- Struktur dari tabel `transaction_details`
--

CREATE TABLE `transaction_details` (
  `id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data untuk tabel `transaction_details`
--

INSERT INTO `transaction_details` (`id`, `transaction_id`, `product_id`, `quantity`, `price`) VALUES
(8, 8, 1, 1, 25000.00),
(9, 8, 3, 1, 15000.00),
(10, 8, 6, 1, 55000.00),
(11, 8, 10, 1, 60000.00),
(12, 8, 0, 1, 5000.00),
(15, 9, 1, 1, 25000.00),
(16, 9, 3, 1, 15000.00),
(17, 9, 0, 1, 5000.00),
(18, 10, 1, 1, 25000.00),
(19, 11, 1, 1, 25000.00),
(20, 12, 1, 1, 25000.00),
(21, 13, 1, 1, 25000.00),
(22, 14, 6, 1, 55000.00),
(23, 15, 3, 1, 15000.00),
(24, 16, 9, 1, 40000.00),
(25, 17, 1, 1, 25000.00),
(26, 18, 3, 1, 15000.00),
(27, 19, 1, 1, 25000.00),
(28, 20, 0, 1, 10000.00),
(29, 21, 0, 1, 15000.00),
(30, 22, 1, 1, 25000.00),
(31, 22, 3, 1, 15000.00),
(32, 22, 0, 1, 5000.00),
(33, 23, 3, 1, 15000.00),
(34, 23, 6, 1, 55000.00),
(35, 23, 1, 1, 25000.00),
(36, 23, 0, 1, 5000.00),
(40, 24, 2, 1, 30000.00),
(41, 24, 4, 1, 12000.00),
(42, 24, 7, 1, 30000.00),
(43, 25, 7, 1, 30000.00),
(44, 25, 2, 1, 30000.00),
(46, 26, 2, 1, 30000.00),
(47, 26, 4, 1, 12000.00),
(48, 26, 0, 1, 5000.00);

--
-- Indexes for dumped tables
--

--
-- Indeks untuk tabel `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name_category`);

--
-- Indeks untuk tabel `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indeks untuk tabel `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`);

--
-- Indeks untuk tabel `owners`
--
ALTER TABLE `owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indeks untuk tabel `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indeks untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`),
  ADD KEY `branch_id` (`branch_id`);

--
-- Indeks untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `transaction_id` (`transaction_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT untuk tabel yang dibuang
--

--
-- AUTO_INCREMENT untuk tabel `branches`
--
ALTER TABLE `branches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT untuk tabel `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT untuk tabel `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT untuk tabel `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT untuk tabel `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT untuk tabel `owners`
--
ALTER TABLE `owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT untuk tabel `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT untuk tabel `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Ketidakleluasaan untuk tabel `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Ketidakleluasaan untuk tabel `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`),
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`);

--
-- Ketidakleluasaan untuk tabel `transaction_details`
--
ALTER TABLE `transaction_details`
  ADD CONSTRAINT `transaction_details_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`id`),
  ADD CONSTRAINT `transaction_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
