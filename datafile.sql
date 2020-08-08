-- MySQL dump 10.13  Distrib 8.0.20, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: projects
-- ------------------------------------------------------
-- Server version	8.0.18



--
-- Table structure for table `actions`
--

DROP TABLE IF EXISTS `actions`;

CREATE TABLE `actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `projectname` varchar(45) COLLATE utf8mb4_lithuanian_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


--
-- Dumping data for table `actions`
--

LOCK TABLES `actions` WRITE;

INSERT INTO `actions` VALUES (1,'Karnavalas'),(2,'Šokiai pokiai'),(3,'Protų mūšis'),(4,'Diskoteka'),(6,'Naktinis žygis'),(7,'Gegužinė'),(8,'Vakaronė'),(9,'Geras laikas');

UNLOCK TABLES;

--
-- Table structure for table `employees`
--

DROP TABLE IF EXISTS `employees`;

CREATE TABLE `employees` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(45) COLLATE utf8mb4_lithuanian_ci DEFAULT NULL,
  `project_id` varchar(200) COLLATE utf8mb4_lithuanian_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_lithuanian_ci;


--
-- Dumping data for table `employees`
--

LOCK TABLES `employees` WRITE;

INSERT INTO `employees` VALUES (1,'Jaunius Povilauskas','2'),(2,'Petras Kazukauskas','1'),(3,'Geroji Samarietė','3'),(4,'Darius Naujokas','4'),(5,'Vardenė Pavardenė','4'),(6,'Tuštutis Kalbusis',NULL),(7,'Labas Rytas','1'),(10,'Vakaris Naujasis','2'),(13,'Tomas Keturratis','4'),(14,'Naujas Veikėjas','8');

UNLOCK TABLES;


-- Dump completed on 2020-08-08 15:44:59
