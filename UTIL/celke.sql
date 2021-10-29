-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2016 at 08:11 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `celke`
--

-- --------------------------------------------------------

--
-- Table structure for table `mensagens_contatos`
--

CREATE TABLE IF NOT EXISTS `mensagens_contatos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(220) NOT NULL,
  `email` varchar(220) NOT NULL,
  `assunto` varchar(220) NOT NULL,
  `mensagem` text NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Dumping data for table `mensagens_contatos`
--

INSERT INTO `mensagens_contatos` (`id`, `nome`, `email`, `assunto`, `mensagem`, `created`, `modified`) VALUES
(1, 'Cesar', 'celkeadm@gmail.com', 'teste1', 'teste1', '2016-07-24 21:03:00', NULL),
(5, 'Cesar', 'celkeadm@gmail.com', 'teste2', 'teste2', '2016-07-24 21:20:07', NULL),
(6, 'Cesar', 'celkeadm@hotmail.com', 'teste3', 'teste3', '2016-07-24 21:37:52', NULL),
(25, 'Cesar Szpak', 'celkeadm@gmail.com', 'dÃºvida', 'Como fazer o curso de PHP, MySQLi e Bootstrap', '2016-08-07 12:24:27', NULL),
(26, 'Cesar Szpak', 'celkeadm@gmail.com', 'DÃºvida', 'Como fazer o curso de PHP, MySQLi e Bootstrap', '2016-08-07 12:26:35', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
