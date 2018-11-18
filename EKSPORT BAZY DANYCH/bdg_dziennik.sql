-- phpMyAdmin SQL Dump
-- version 4.6.6deb5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 16, 2018 at 09:09 AM
-- Server version: 5.7.24-0ubuntu0.18.04.1
-- PHP Version: 7.2.10-0ubuntu0.18.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bdg_dziennik`
--
DROP DATABASE IF EXISTS `bdg_dziennik`;
CREATE DATABASE `bdg_dziennik` DEFAULT CHARACTER SET utf8 COLLATE utf8_polish_ci;
USE `bdg_dziennik`;



-- --------------------------------------------------------

--
-- Table structure for table `administrator`
--

CREATE TABLE `administrator` (
  `id_osoba` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `administrator`
--

INSERT INTO `administrator` (`id_osoba`) VALUES
(6);

-- --------------------------------------------------------

--
-- Table structure for table `klasa`
--

CREATE TABLE `klasa` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `opis` varchar(100) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `klasa`
--

INSERT INTO `klasa` (`id`, `nazwa`, `opis`) VALUES
(1, '1 B 1', 'Liceum ogólnokształcące - klasa o profilu biologiczno-chemicznym'),
(2, '1 B 2', 'Liceum ogólnokształcące - klasa o profilu biologiczno-chemicznym'),
(3, '1 JG 1', 'Liceum ogólnokształcące - klasa o profilu językowo-geograficznym'),
(4, '1 JG 2', 'Liceum ogólnokształcące - klasa o profilu językowo-geograficznym'),
(5, '1 M 1', 'Liceum ogólnokształcące - klasa o profilu matematyczno-informatycznym'),
(6, '1 M 2', 'Liceum ogólnokształcące - klasa o profilu matematyczno-informatycznym'),
(7, '2 BCH', 'Liceum ogólnokształcące - klasa o profilu biologiczno-chemicznym'),
(8, '2 BR', NULL),
(9, '2 H', 'Liceum ogólnokształcące - klasa o profilu humanistycznym'),
(10, '2 JG 1', 'Liceum ogólnokształcące - klasa o profilu językowo-geograficznym'),
(11, '2 JG 2', 'Liceum ogólnokształcące - klasa o profilu językowo-geograficznym'),
(12, '2 MF', 'Liceum ogólnokształcące - klasa o profilu matematyczno-fizycznym'),
(13, '2 MI', 'Liceum ogólnokształcące - klasa o profilu matematyczno-informatycznym'),
(14, '3 B 1', 'Liceum ogólnokształcące - klasa o profilu biologiczno-chemicznym'),
(15, '3 B 2', 'Liceum ogólnokształcące - klasa o profilu biologiczno-chemicznym'),
(16, '3 H', 'Liceum ogólnokształcące - klasa o profilu humanistycznym'),
(17, '3 JG', 'Liceum ogólnokształcące - klasa o profilu językowo-geograficznym'),
(18, '3 MI', 'Liceum ogólnokształcące - klasa o profilu matematyczno-informatycznym'),
(19, '3 MG', 'Liceum ogólnokształcące - klasa o profilu matematyczno-geograficznym'),
(20, '1 TI 1', 'Technikum informatyczne'),
(21, '1 TI 2', 'Technikum informatyczne'),
(22, '1 TW', 'Technikum weterynaryjne'),
(23, '1 TUG', 'Technikum usług gastronomicznych'),
(24, '2 TI 1', 'Technikum informatyczne'),
(25, '2 TI 2', 'Technikum informatyczne'),
(26, '2 TUG', 'Technikum usług gastronomicznych'),
(27, '2 TW', 'Technikum weterynaryjne'),
(28, '2 TŻR', 'Technikum żywieniowo-rolnicze'),
(29, '3 TI 1', 'Technikum informatyczne'),
(30, '3 TI 2', 'Technikum informatyczne'),
(31, '3 TUG', 'Technikum usług gastronomicznych'),
(32, '3 TW', 'Technikum weterynaryjne'),
(33, '3 TŻR', 'Technikum żywieniowo-rolnicze'),
(34, '4 TI 1', 'Technikum informatyczne'),
(35, '4 TI 2', 'Technikum informatyczne'),
(36, '4 TUG', 'Technikum usług gastronomicznych'),
(37, '4 TW', 'Technikum weterynaryjne'),
(38, '4 TŻR', 'Technikum żywieniowo-rolnicze'),
(39, 'Testy Z Przydziałem', 'Tak to jest test z przydziałem'),
(40, 'QY3WHJ', 'Q3Ywhj'),
(41, '123', 'q3YWEH');

-- --------------------------------------------------------

--
-- Table structure for table `nauczyciel`
--

CREATE TABLE `nauczyciel` (
  `id_osoba` int(11) NOT NULL,
  `id_sala` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `nauczyciel`
--

INSERT INTO `nauczyciel` (`id_osoba`, `id_sala`) VALUES
(7, 1),
(10, 1);

-- --------------------------------------------------------

--
-- Table structure for table `ocena`
--

CREATE TABLE `ocena` (
  `id` int(11) NOT NULL,
  `id_przydzial` int(11) DEFAULT NULL,
  `id_uczen` int(11) DEFAULT NULL,
  `data` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `wartosc` enum('0','1','1+','2-','2','2+','3-','3','3+','4-','4','4+','5-','5','5+','6-','6') COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `ocena`
--

INSERT INTO `ocena` (`id`, `id_przydzial`, `id_uczen`, `data`, `wartosc`) VALUES
(1, 8, 9, '2018-11-08 12:00:02', '6'),
(7, 9, 9, '2018-11-09 17:06:08', '5-'),
(8, 9, 9, '2018-11-09 17:06:15', '3'),
(13, 9, 11, '2018-11-12 10:29:16', '6'),
(14, 9, 9, '2018-11-09 17:11:30', '5-'),
(15, 9, 9, '2018-11-09 17:11:36', '2+');

-- --------------------------------------------------------

--
-- Table structure for table `osoba`
--

CREATE TABLE `osoba` (
  `id` int(11) NOT NULL,
  `imie` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL,
  `nazwisko` varchar(30) COLLATE utf8_polish_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `uprawnienia` enum('a','n','u') COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `osoba`
--

INSERT INTO `osoba` (`id`, `imie`, `nazwisko`, `email`, `haslo`, `uprawnienia`) VALUES
(6, 'Redzik', 'Polaczy', 'admin@email.com', '$2y$10$g4IWDkDdXRQnzgtlrYtq/up2ENhP.VqfUXZ4xY/6xijyHlfQHoo2y', 'a'),
(7, 'Marek', 'Paweł I', 'nauczyciel@gmail.onet', '$2y$10$c0ymfd6aYfHObRwIyiDMrewYjIB88UnGE4mYTVW.bl2B2jB6f5/bW', 'n'),
(9, 'Szymon', 'Polaczy', 'polaczyszymon@gmail.com', '$2y$10$OCEwLm3BGpE1LmxUPbYSOuhx.wUMljd3mHbjVS6Ob7Zuac3LE/aQ2', 'u'),
(10, 'Szymon', 'Polaxy', '123@email.com', '$2y$10$nfbxCxPI7IT6DSuGhvE3VugBkjVLxRY2aTMejAizNLOt6i6JaVvA2', 'n'),
(11, 'Adam', 'Nowak', 'uczen@email.com', '$2y$10$ZA9NR7QldxTVvg2JOlf1Ruc2GsdeAGJ9/y3eJDGjwZQkCj.Z2Niqe', 'u');

-- --------------------------------------------------------

--
-- Table structure for table `przedmiot`
--

CREATE TABLE `przedmiot` (
  `id` int(10) NOT NULL,
  `nazwa` varchar(50) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `przedmiot`
--

INSERT INTO `przedmiot` (`id`, `nazwa`) VALUES
(1, 'łangielski');

-- --------------------------------------------------------

--
-- Table structure for table `przydzial`
--

CREATE TABLE `przydzial` (
  `id` int(11) NOT NULL,
  `id_nauczyciel` int(11) NOT NULL,
  `id_przedmiot` int(11) NOT NULL,
  `id_klasa` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `przydzial`
--

INSERT INTO `przydzial` (`id`, `id_nauczyciel`, `id_przedmiot`, `id_klasa`) VALUES
(3, 7, 1, 6),
(5, 7, 1, 13),
(4, 7, 1, 16),
(9, 7, 1, 24),
(10, 7, 1, 32),
(6, 10, 1, 10),
(11, 10, 1, 24),
(8, 10, 1, 39);

-- --------------------------------------------------------

--
-- Table structure for table `sala`
--

CREATE TABLE `sala` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(20) COLLATE utf8_polish_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `sala`
--

INSERT INTO `sala` (`id`, `nazwa`) VALUES
(1, 'Sala 112');

-- --------------------------------------------------------

--
-- Table structure for table `uczen`
--

CREATE TABLE `uczen` (
  `id_osoba` int(11) NOT NULL,
  `id_klasa` int(11) DEFAULT NULL,
  `data_urodzenia` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Dumping data for table `uczen`
--

INSERT INTO `uczen` (`id_osoba`, `id_klasa`, `data_urodzenia`) VALUES
(9, 24, '2001-09-15'),
(11, 24, '6797-08-08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administrator`
--
ALTER TABLE `administrator`
  ADD PRIMARY KEY (`id_osoba`);

--
-- Indexes for table `klasa`
--
ALTER TABLE `klasa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- Indexes for table `nauczyciel`
--
ALTER TABLE `nauczyciel`
  ADD PRIMARY KEY (`id_osoba`),
  ADD KEY `id_sala` (`id_sala`);

--
-- Indexes for table `ocena`
--
ALTER TABLE `ocena`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uczen` (`id_uczen`),
  ADD KEY `id_nauczyciel` (`id_przydzial`);

--
-- Indexes for table `osoba`
--
ALTER TABLE `osoba`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `przedmiot`
--
ALTER TABLE `przedmiot`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- Indexes for table `przydzial`
--
ALTER TABLE `przydzial`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_nauczyciel_2` (`id_nauczyciel`,`id_przedmiot`,`id_klasa`),
  ADD KEY `id_przedmiot` (`id_przedmiot`),
  ADD KEY `id_klasa` (`id_klasa`),
  ADD KEY `id_nauczyciel` (`id_nauczyciel`);

--
-- Indexes for table `sala`
--
ALTER TABLE `sala`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nazwa` (`nazwa`);

--
-- Indexes for table `uczen`
--
ALTER TABLE `uczen`
  ADD PRIMARY KEY (`id_osoba`),
  ADD KEY `id_klasa` (`id_klasa`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `klasa`
--
ALTER TABLE `klasa`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;
--
-- AUTO_INCREMENT for table `ocena`
--
ALTER TABLE `ocena`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
--
-- AUTO_INCREMENT for table `osoba`
--
ALTER TABLE `osoba`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `przedmiot`
--
ALTER TABLE `przedmiot`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `przydzial`
--
ALTER TABLE `przydzial`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT for table `sala`
--
ALTER TABLE `sala`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `administrator`
--
ALTER TABLE `administrator`
  ADD CONSTRAINT `administrator_ibfk_1` FOREIGN KEY (`id_osoba`) REFERENCES `osoba` (`id`);

--
-- Constraints for table `nauczyciel`
--
ALTER TABLE `nauczyciel`
  ADD CONSTRAINT `nauczyciel_ibfk_2` FOREIGN KEY (`id_sala`) REFERENCES `sala` (`id`),
  ADD CONSTRAINT `nauczyciel_ibfk_3` FOREIGN KEY (`id_osoba`) REFERENCES `osoba` (`id`);

--
-- Constraints for table `ocena`
--
ALTER TABLE `ocena`
  ADD CONSTRAINT `ocena_ibfk_2` FOREIGN KEY (`id_przydzial`) REFERENCES `przydzial` (`id`),
  ADD CONSTRAINT `ocena_ibfk_3` FOREIGN KEY (`id_uczen`) REFERENCES `uczen` (`id_osoba`);

--
-- Constraints for table `przydzial`
--
ALTER TABLE `przydzial`
  ADD CONSTRAINT `przydzial_ibfk_2` FOREIGN KEY (`id_klasa`) REFERENCES `klasa` (`id`),
  ADD CONSTRAINT `przydzial_ibfk_4` FOREIGN KEY (`id_nauczyciel`) REFERENCES `nauczyciel` (`id_osoba`),
  ADD CONSTRAINT `przydzial_ibfk_5` FOREIGN KEY (`id_przedmiot`) REFERENCES `przedmiot` (`id`);

--
-- Constraints for table `uczen`
--
ALTER TABLE `uczen`
  ADD CONSTRAINT `uczen_ibfk_2` FOREIGN KEY (`id_klasa`) REFERENCES `klasa` (`id`),
  ADD CONSTRAINT `uczen_ibfk_3` FOREIGN KEY (`id_osoba`) REFERENCES `osoba` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
