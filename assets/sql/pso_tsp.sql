-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Inang: 127.0.0.1
-- Waktu pembuatan: 20 Jun 2015 pada 06.48
-- Versi Server: 5.5.27
-- Versi PHP: 5.4.7

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Basis data: `pso_tsp`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `general_result`
--

CREATE TABLE IF NOT EXISTS `general_result` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `init_param_id` int(11) NOT NULL,
  `v_max` int(11) NOT NULL,
  `max_epoch` int(11) NOT NULL,
  `particle_count` int(11) NOT NULL,
  `latest_route` text NOT NULL,
  `latest_distance` text NOT NULL,
  `epoch_number` int(11) NOT NULL,
  `shortest_route` varchar(255) NOT NULL,
  `shortest_distance` double NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struktur dari tabel `init_param`
--

CREATE TABLE IF NOT EXISTS `init_param` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v_max` int(11) NOT NULL,
  `max_epoch` int(11) NOT NULL,
  `city_count` int(11) NOT NULL,
  `target` float NOT NULL,
  `xlocs` varchar(255) NOT NULL,
  `ylocs` varchar(255) NOT NULL,
  `particle_count` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Dumping data untuk tabel `init_param`
--

INSERT INTO `init_param` (`id`, `v_max`, `max_epoch`, `city_count`, `target`, `xlocs`, `ylocs`, `particle_count`) VALUES
(1, 8, 10000, 8, 86.63, '30,40,40,29,19,9,9,20', '5,10,20,25,25,19,9,5', 6),
(2, 8, 10000, 8, 87.9, '35,41,40,20,19,9,19,20', '5,10,23,25,25,19,9,15', 6);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
