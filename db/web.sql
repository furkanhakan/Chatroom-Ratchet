-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 24 May 2020, 16:15:36
-- Sunucu sürümü: 10.4.11-MariaDB
-- PHP Sürümü: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `web`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `chatrooms`
--

CREATE TABLE `chatrooms` (
  `id` int(6) UNSIGNED NOT NULL,
  `userid` int(6) NOT NULL,
  `msg` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `chatrooms`
--

INSERT INTO `chatrooms` (`id`, `userid`, `msg`, `created_on`) VALUES
(127, 1, 'selam\n', '2020-05-24 16:21:26'),
(128, 2, 'selam\n', '2020-05-24 16:21:29'),
(129, 1, 'naber\n', '2020-05-24 16:21:32'),
(130, 3, 'selam\n', '2020-05-24 16:21:36'),
(132, 1, 'selam\n', '2020-05-24 16:45:58'),
(133, 3, 'merhaba\n', '2020-05-24 16:46:03'),
(134, 2, 'merhaba\n', '2020-05-24 16:46:07'),
(135, 1, 'selam\n', '2020-05-24 16:48:32'),
(136, 3, 'merhaba\n', '2020-05-24 16:48:37'),
(137, 2, 'naber\n', '2020-05-24 16:48:40');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `users`
--

CREATE TABLE `users` (
  `id` int(6) UNSIGNED NOT NULL,
  `name` varchar(30) NOT NULL,
  `password` varchar(16) NOT NULL,
  `email` varchar(50) NOT NULL,
  `login_status` tinyint(4) NOT NULL DEFAULT 0,
  `last_login` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Tablo döküm verisi `users`
--

INSERT INTO `users` (`id`, `name`, `password`, `email`, `login_status`, `last_login`) VALUES
(1, 'Furkan', '123', 'furkan_hkn@hotmail.com', 1, '0000-00-00 00:00:00'),
(2, 'Melih', '456', 'melih@gmail.com', 0, '2020-05-24 16:14:03'),
(3, 'Ertan', '789', 'ertan@gmail.com', 1, '0000-00-00 00:00:00');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `chatrooms`
--
ALTER TABLE `chatrooms`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `chatrooms`
--
ALTER TABLE `chatrooms`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=138;

--
-- Tablo için AUTO_INCREMENT değeri `users`
--
ALTER TABLE `users`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
