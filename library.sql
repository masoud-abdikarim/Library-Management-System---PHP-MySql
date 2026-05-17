-- MySQL dump 10.13  Distrib 8.0.45, for Win64 (x86_64)
--
-- Host: localhost    Database: library
-- ------------------------------------------------------
-- Server version	8.0.45

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `FullName` varchar(100) DEFAULT NULL,
  `AdminEmail` varchar(120) DEFAULT NULL,
  `UserName` varchar(100) NOT NULL,
  `Password` varchar(100) NOT NULL,
  `updationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'Anuj Kumar','phpgurukulofficial@gmail.com','admin','f925916e2754e5e03f75dd58a5733251','2017-07-16 18:11:42'),(3,'muse`','mus1e@gmail.com','admin','25f9e794323b453885f5181f1b624d0b','2017-07-16 15:11:42');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `overdue`
--

DROP TABLE IF EXISTS `overdue`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `overdue` (
  `StudentID` varchar(11) NOT NULL,
  `StudentName` varchar(40) NOT NULL,
  `MobNumber` varchar(11) NOT NULL,
  `Fine` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `overdue`
--

LOCK TABLES `overdue` WRITE;
/*!40000 ALTER TABLE `overdue` DISABLE KEYS */;
/*!40000 ALTER TABLE `overdue` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblauthors`
--

DROP TABLE IF EXISTS `tblauthors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblauthors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `AuthorName` varchar(159) DEFAULT NULL,
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblauthors`
--

LOCK TABLES `tblauthors` WRITE;
/*!40000 ALTER TABLE `tblauthors` DISABLE KEYS */;
INSERT INTO `tblauthors` VALUES (2,'Chetan Bhagatt','2017-07-08 14:30:23','2017-07-08 15:15:09'),(3,'Anita Desai','2017-07-08 14:35:08',NULL),(4,'HC Verma','2017-07-08 14:35:21',NULL),(5,'test','2026-05-14 08:18:57',NULL);
/*!40000 ALTER TABLE `tblauthors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblbooks`
--

DROP TABLE IF EXISTS `tblbooks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblbooks` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookName` varchar(255) DEFAULT NULL,
  `Copies` int NOT NULL,
  `IssuedCopies` int NOT NULL,
  `CatId` int DEFAULT NULL,
  `AuthorId` int DEFAULT NULL,
  `ISBNNumber` int DEFAULT NULL,
  `BookPrice` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblbooks`
--

LOCK TABLES `tblbooks` WRITE;
/*!40000 ALTER TABLE `tblbooks` DISABLE KEYS */;
INSERT INTO `tblbooks` VALUES (4,'physics',5,3,4,5,20,100,'2018-06-06 22:52:21','2018-07-13 08:51:41'),(5,'C Programming',3,2,5,3,111,200,'2018-06-11 17:48:02','2026-05-14 16:06:51'),(7,'book',80,0,4,NULL,NULL,10,'2026-05-14 08:51:02',NULL);
/*!40000 ALTER TABLE `tblbooks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblcategory`
--

DROP TABLE IF EXISTS `tblcategory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblcategory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `CategoryName` varchar(150) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblcategory`
--

LOCK TABLES `tblcategory` WRITE;
/*!40000 ALTER TABLE `tblcategory` DISABLE KEYS */;
INSERT INTO `tblcategory` VALUES (4,'Knowledge',1,'2017-07-04 18:35:25','2018-06-07 18:55:37'),(5,'Technology',1,'2017-07-04 18:35:39','2017-07-08 17:13:03'),(6,'Science',1,'2017-07-04 18:35:55','0000-00-00 00:00:00'),(7,'Management',1,'2017-07-04 18:36:16','2018-06-06 18:46:41'),(8,'physics',1,'2018-06-11 17:31:43','2018-06-11 17:36:56'),(9,'history',1,'2018-06-11 18:24:53','2018-06-13 00:29:15'),(14,'LifeStyle',1,'2018-07-13 05:17:16','0000-00-00 00:00:00'),(15,'test',1,'2026-05-14 08:25:28','0000-00-00 00:00:00');
/*!40000 ALTER TABLE `tblcategory` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblfine`
--

DROP TABLE IF EXISTS `tblfine`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblfine` (
  `fine` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblfine`
--

LOCK TABLES `tblfine` WRITE;
/*!40000 ALTER TABLE `tblfine` DISABLE KEYS */;
INSERT INTO `tblfine` VALUES (10);
/*!40000 ALTER TABLE `tblfine` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblissuedbookdetails`
--

DROP TABLE IF EXISTS `tblissuedbookdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblissuedbookdetails` (
  `id` int NOT NULL AUTO_INCREMENT,
  `BookId` int DEFAULT NULL,
  `StudentID` varchar(150) DEFAULT NULL,
  `IssuesDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `ExpectedReturnDate` timestamp NULL DEFAULT NULL,
  `ReturnRequestDate` timestamp NULL DEFAULT NULL,
  `ReturnDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `ReturnStatus` int NOT NULL,
  `fine` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblissuedbookdetails`
--

LOCK TABLES `tblissuedbookdetails` WRITE;
/*!40000 ALTER TABLE `tblissuedbookdetails` DISABLE KEYS */;
INSERT INTO `tblissuedbookdetails` VALUES (6,4,'SID009','2018-06-12 20:52:10',NULL,NULL,'2018-06-13 20:44:28',1,20),(7,5,'SID009','2018-06-12 20:55:24',NULL,NULL,'2018-06-12 23:46:08',1,200),(8,3,'SID009','2018-06-12 23:27:23',NULL,NULL,NULL,0,NULL),(9,5,'SID009','2018-06-13 21:24:38',NULL,NULL,NULL,0,NULL),(10,5,'SID009','2018-06-13 21:44:50',NULL,NULL,'2026-05-14 10:50:49',1,NULL),(11,10,'SID002','2018-07-11 18:30:00',NULL,NULL,'2018-07-18 07:47:46',1,10),(12,10,'SID005','2018-07-18 07:59:30',NULL,NULL,'2018-07-18 07:59:41',1,NULL),(13,5,'SID005','2018-07-18 08:00:25',NULL,NULL,'2018-07-18 08:00:41',1,NULL),(14,5,'SID009','2018-07-20 09:37:03',NULL,NULL,NULL,0,NULL),(15,5,'SID009','2018-07-20 09:40:40',NULL,NULL,'2026-05-14 10:50:45',1,NULL),(16,5,'3','2026-05-14 15:26:54','2026-05-28 21:00:00',NULL,NULL,0,NULL),(17,5,'4','2026-05-14 16:06:05','2026-05-29 21:00:00','2026-05-14 16:06:38','2026-05-14 16:06:51',1,NULL);
/*!40000 ALTER TABLE `tblissuedbookdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblrequestedbookdetails`
--

DROP TABLE IF EXISTS `tblrequestedbookdetails`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblrequestedbookdetails` (
  `StudentID` varchar(20) NOT NULL,
  `StudName` varchar(40) NOT NULL,
  `BookName` varchar(50) NOT NULL,
  `CategoryName` varchar(20) NOT NULL,
  `AuthorName` varchar(50) NOT NULL,
  `ISBNNumber` int NOT NULL,
  `BookPrice` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblrequestedbookdetails`
--

LOCK TABLES `tblrequestedbookdetails` WRITE;
/*!40000 ALTER TABLE `tblrequestedbookdetails` DISABLE KEYS */;
INSERT INTO `tblrequestedbookdetails` VALUES ('2','ahmed muse','Chemistry','Science','HC Verma',1111,15),('2','ahmed muse','C Programming','Technology','Anita Desai',111,200);
/*!40000 ALTER TABLE `tblrequestedbookdetails` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tblstudents`
--

DROP TABLE IF EXISTS `tblstudents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tblstudents` (
  `id` int NOT NULL AUTO_INCREMENT,
  `StudentId` varchar(100) DEFAULT NULL,
  `FullName` varchar(120) DEFAULT NULL,
  `EmailId` varchar(120) DEFAULT NULL,
  `MobileNumber` char(11) DEFAULT NULL,
  `Password` varchar(120) DEFAULT NULL,
  `Status` int DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `StudentId` (`StudentId`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tblstudents`
--

LOCK TABLES `tblstudents` WRITE;
/*!40000 ALTER TABLE `tblstudents` DISABLE KEYS */;
INSERT INTO `tblstudents` VALUES (1,'SID002','Anuj kumar','anuj.lpu1@gmail.com','9865472555','f925916e2754e5e03f75dd58a5733251',1,'2017-07-11 15:37:05','2018-07-13 08:49:22'),(4,'SID005','sdfsd','csfsd@dfsfks.com','8569710025','92228410fc8b872914e023160cf4ae8f',1,'2017-07-11 15:41:27','2018-06-11 18:26:20'),(8,'SID009','test','pb@gmail.com','8329629259','f925916e2754e5e03f75dd58a5733251',1,'2017-07-11 15:58:28','2018-07-18 05:17:54'),(9,'SID010','Amit','amit@gmail.com','8585856224','f925916e2754e5e03f75dd58a5733251',1,'2017-07-15 13:40:30',NULL),(10,'SID011','Sarita Pandey','sarita@gmail.com','4672423754','f925916e2754e5e03f75dd58a5733251',1,'2017-07-15 18:00:59',NULL),(11,'SID012','sakshi g','sakshi@gmail.com','1234567890','b59c67bf196a4758191e42f76670ceba',1,'2018-06-11 17:55:21','2018-07-18 05:18:49'),(12,'2','ahmed muse','masoud@gmail.com','1234567890','e807f1fcf82d132f9bb018ca6738a19f',1,'2026-05-12 08:05:49',NULL),(14,'3','muse ahmed','muse@gmail.com','1213452','e807f1fcf82d132f9bb018ca6738a19f',1,'2026-05-14 07:47:55',NULL),(15,'4','masoud abdikarim','masoudabdikarim1@gmail.com','1234567','e10adc3949ba59abbe56e057f20f883e',1,'2026-05-14 16:05:02',NULL);
/*!40000 ALTER TABLE `tblstudents` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-17  5:22:34
