-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 25, 2026 at 12:19 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `wineshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Vang đỏ'),
(2, 'Vang trắng'),
(3, 'Whisky'),
(4, 'Vodka'),
(5, 'Rượu Sake');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `status` enum('pending','confirmed','shipping','completed','cancelled') DEFAULT 'pending',
  `shipping_address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `status`, `shipping_address`, `created_at`) VALUES
(1, 5, 380000.00, 'completed', 'Cần Thơ', '2026-06-23 04:07:55'),
(2, 5, 590000.00, 'completed', 'TP HCM', '2026-06-23 04:12:13'),
(3, 6, 2360000.00, 'completed', 'Cần Thơ', '2026-06-25 09:35:07'),
(4, 6, 1770000.00, 'pending', 'Cần Thơ', '2026-06-25 09:49:47'),
(5, 6, 1100000.00, 'pending', 'Cần Thơ', '2026-06-25 09:50:34');

-- --------------------------------------------------------

--
-- Table structure for table `order_details`
--

CREATE TABLE `order_details` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_details`
--

INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 9, 1, 380000.00),
(2, 2, 10, 1, 590000.00),
(3, 3, 10, 4, 590000.00),
(4, 4, 10, 3, 590000.00),
(5, 5, 4, 1, 320000.00),
(6, 5, 5, 1, 780000.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(12,2) NOT NULL,
  `alcohol_percent` decimal(4,1) DEFAULT NULL,
  `volume_ml` int(11) DEFAULT NULL,
  `origin` varchar(100) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `description`, `price`, `alcohol_percent`, `volume_ml`, `origin`, `stock`, `image`, `created_at`) VALUES
(1, 1, 'Vang đỏ Đà Lạt Classic', 'Vị chát nhẹ, hương nho chín, phù hợp dùng trong bữa ăn gia đình.', 185000.00, 12.5, 750, 'Việt Nam', 40, 'vang-dalat.png', '2026-06-19 03:20:21'),
(2, 1, 'Chateau Bordeaux Reserve', 'Vang đỏ nhập khẩu từ vùng Bordeaux, hậu vị kéo dài.', 950000.00, 13.5, 750, 'Pháp', 15, 'bordeaux.png', '2026-06-19 03:20:21'),
(3, 2, 'Vang trắng Chardonnay', 'Hương vị trái cây tươi mát, thích hợp khai vị.', 420000.00, 11.5, 750, 'Pháp', 25, 'chardonnay.png', '2026-06-19 03:20:21'),
(4, 2, 'Vang trắng Moscato', 'Vị ngọt nhẹ, thơm hương hoa, dễ uống cho người mới.', 320000.00, 7.5, 750, 'Ý', 29, 'moscato.png', '2026-06-19 03:20:21'),
(5, 3, 'Chivas Regal 12 năm', 'Whisky pha trộn từ Scotland, ủ 12 năm trong thùng gỗ sồi.', 780000.00, 40.0, 700, 'Scotland', 19, 'chivas12.png', '2026-06-19 03:20:21'),
(6, 3, 'Jack Daniel\'s Old No.7', 'Whisky Tennessee đặc trưng, hương khói nhẹ.', 650000.00, 40.0, 700, 'Mỹ', 18, 'jackdaniels.png', '2026-06-19 03:20:21'),
(7, 4, 'Vodka Hà Nội', 'Vodka truyền thống Việt Nam, vị thanh, dễ pha cocktail.', 95000.00, 39.5, 500, 'Việt Nam', 50, 'vodka-hanoi.png', '2026-06-19 03:20:21'),
(8, 4, 'Absolut Vodka', 'Vodka Thụy Điển, lọc qua hệ thống chưng cất liên tục.', 480000.00, 40.0, 750, 'Thụy Điển', 22, 'absolut.png', '2026-06-19 03:20:21'),
(9, 5, 'Sake Gekkeikan', 'Sake truyền thống Nhật Bản, vị nhẹ, thơm gạo.', 380000.00, 15.0, 720, 'Nhật Bản', 11, 'gekkeikan.png', '2026-06-19 03:20:21'),
(10, 5, 'Sake Junmai Premium', 'Sake nguyên chất cao cấp, không pha cồn.', 590000.00, 16.0, 720, 'Nhật Bản', 2, 'junmai.png', '2026-06-19 03:20:21');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`) VALUES
(1, 1, 5, 5, 'Chivas 12 uống rất đầm, hương vị khói nhẹ nồng nàn. Giao hàng cực nhanh, đóng gói rất cẩn thận!', '2026-06-22 12:58:29'),
(2, 1, 9, 4, 'Rượu Sake Gekkeikan vị thanh mát, uống ngon hơn khi hâm nóng nhẹ. Xứng đáng tiền.', '2026-06-22 12:58:29'),
(3, 2, 1, 5, 'Vang đỏ Đà Lạt Classic vị chát nhẹ hợp với bữa tối bò bít tết của gia đình mình.', '2026-06-22 12:58:29'),
(4, 2, 7, 4, 'Vodka Hà Nội giá rẻ mà chất lượng rất ổn định, pha cocktail uống bao phê.', '2026-06-22 12:58:29'),
(5, 3, 3, 5, 'Vang trắng Chardonnay thơm hương trái cây tươi, uống khai vị rất kích thích vị giác.', '2026-06-22 12:58:29'),
(6, 5, 9, 5, 'ngon', '2026-06-22 15:35:21'),
(7, 5, 1, 5, 'rượu mời không uống, muốn uống rượu DWINE', '2026-06-22 15:47:23'),
(8, 6, 4, 5, 'tuyệt vời', '2026-06-25 09:35:39');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `status` enum('active','disabled') NOT NULL DEFAULT 'active',
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `password`, `role`, `status`, `phone`, `address`, `created_at`) VALUES
(1, 'Nguyễn Minh Anh', 'minhanh', 'minhanh.review@example.com', '$2y$10$M7g2e9Z8H2R.yH6Nf8C7e.bF1gH9kL2mN3oP4qR5sT6uV7wX8yZ1.', 'user', 'active', '0909123456', 'TP.HCM', '2026-06-22 12:58:01'),
(2, 'Lê Thị Hương', 'huongle', 'huong.review@example.com', '$2y$10$M7g2e9Z8H2R.yH6Nf8C7e.bF1gH9kL2mN3oP4qR5sT6uV7wX8yZ1.', 'user', 'active', '0912345678', 'Hà Nội', '2026-06-22 12:58:01'),
(3, 'Trần Quốc Đạt', 'quocdat', 'datdaynebro@example.com', '$2y$10$fTBjiwn9TyaXcK8aZq16XOOIIyxfrj/OtpIO..n0JtwHuQIyXvySe', 'admin', 'active', '0987654321', 'Cần Thơ', '2026-06-22 12:58:01'),
(4, 'TranQuocDat', 'DatTran', 'dat2@gmail.com', '$2y$10$WmE3CatAqIZ1QJce9XZWXO/uCC7sckZOpMxIrnPLjIItBb7dhhdy6', 'user', 'active', NULL, NULL, '2026-06-22 12:59:59'),
(5, 'Trần Đạt', 'Dat', 'tranquocdat.210606@gmail.com', '$2y$10$u7An7AIzkJwN3s3mt1tY3OfxxYI.i1ZIVn9GYtXtoQtZ99IS3P2UG', 'user', 'active', NULL, NULL, '2026-06-22 13:12:28'),
(6, 'Thành Đồng', 'thanhdong', 'thanhdong@gmail.com', '$2y$10$AkfPbNluQ4atNhlpbnQaZ.TbQYh2F5vFG.ti3ZhViqSF.T0cjaILK', 'admin', 'active', NULL, NULL, '2026-06-25 06:07:16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `order_details`
--
ALTER TABLE `order_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
