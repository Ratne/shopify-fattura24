-- phpMyAdmin SQL Dump
-- version 4.7.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Creato il: Feb 19, 2018 alle 07:35
-- Versione del server: 5.6.36-cll-lve
-- Versione PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `awdealsw_shopy`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `fattura_shop`
--

CREATE TABLE `fattura_shop` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(250) NOT NULL,
  `access_token` varchar(250) NOT NULL,
  `status` enum('0','1') NOT NULL DEFAULT '1',
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `fattura_shop_order_create`
--

CREATE TABLE `fattura_shop_order_create` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `shop` varchar(255) NOT NULL,
  `detail` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struttura della tabella `fattura_shop_setting`
--

CREATE TABLE `fattura_shop_setting` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `api_key` varchar(255) NOT NULL,
  `send_email` enum('0','1') NOT NULL DEFAULT '0',
  `id_templatea` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `fattura_shop`
--
ALTER TABLE `fattura_shop`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `fattura_shop_order_create`
--
ALTER TABLE `fattura_shop_order_create`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `fattura_shop_setting`
--
ALTER TABLE `fattura_shop_setting`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `fattura_shop`
--
ALTER TABLE `fattura_shop`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT per la tabella `fattura_shop_order_create`
--
ALTER TABLE `fattura_shop_order_create`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT per la tabella `fattura_shop_setting`
--
ALTER TABLE `fattura_shop_setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
