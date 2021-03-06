CREATE DATABASE  IF NOT EXISTS `student_helper` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `student_helper`;
-- MySQL dump 10.13  Distrib 8.0.15, for Win64 (x86_64)
--
-- Host: localhost    Database: student_helper
-- ------------------------------------------------------
-- Server version	8.0.15

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 SET NAMES utf8 ;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `classroom`
--

DROP TABLE IF EXISTS `classroom`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `classroom` (
  `classroomId` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `name` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`classroomId`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classroom`
--

LOCK TABLES `classroom` WRITE;
/*!40000 ALTER TABLE `classroom` DISABLE KEYS */;
INSERT INTO `classroom` VALUES (000008,'3/1');
/*!40000 ALTER TABLE `classroom` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sectiondetail`
--

DROP TABLE IF EXISTS `sectiondetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sectiondetail` (
  `sectionId` int(6) unsigned zerofill NOT NULL,
  `studentId` int(5) unsigned zerofill NOT NULL,
  `date` varchar(80) NOT NULL,
  PRIMARY KEY (`sectionId`,`studentId`,`date`),
  KEY `stuId_idx` (`studentId`),
  CONSTRAINT `stuId` FOREIGN KEY (`studentId`) REFERENCES `student` (`studentId`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `subcell` FOREIGN KEY (`sectionId`) REFERENCES `sectiontable` (`sectionId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sectiondetail`
--

LOCK TABLES `sectiondetail` WRITE;
/*!40000 ALTER TABLE `sectiondetail` DISABLE KEYS */;
/*!40000 ALTER TABLE `sectiondetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sectiontable`
--

DROP TABLE IF EXISTS `sectiontable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `sectiontable` (
  `sectionId` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `class` int(6) unsigned zerofill DEFAULT NULL,
  `day` varchar(45) DEFAULT NULL,
  `time` int(2) unsigned zerofill DEFAULT NULL,
  `subject` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`sectionId`),
  KEY `time_idx` (`time`),
  CONSTRAINT `time` FOREIGN KEY (`time`) REFERENCES `timetable` (`timeId`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sectiontable`
--

LOCK TABLES `sectiontable` WRITE;
/*!40000 ALTER TABLE `sectiontable` DISABLE KEYS */;
/*!40000 ALTER TABLE `sectiontable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `student`
--

DROP TABLE IF EXISTS `student`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `student` (
  `studentId` int(5) unsigned zerofill NOT NULL,
  `prefix` varchar(45) DEFAULT NULL,
  `name` varchar(200) DEFAULT NULL,
  `nickname` varchar(150) DEFAULT NULL,
  `class` int(6) unsigned zerofill DEFAULT NULL,
  `grade` varchar(45) DEFAULT '-',
  PRIMARY KEY (`studentId`),
  KEY `classroom_idx` (`class`),
  CONSTRAINT `classroom` FOREIGN KEY (`class`) REFERENCES `classroom` (`classroomId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `student`
--

LOCK TABLES `student` WRITE;
/*!40000 ALTER TABLE `student` DISABLE KEYS */;
/*!40000 ALTER TABLE `student` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable`
--

DROP TABLE IF EXISTS `timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `timetable` (
  `timeId` int(2) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `time` varchar(70) DEFAULT NULL,
  PRIMARY KEY (`timeId`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable`
--

LOCK TABLES `timetable` WRITE;
/*!40000 ALTER TABLE `timetable` DISABLE KEYS */;
INSERT INTO `timetable` VALUES (01,'08:30 - 09:20'),(02,'09:20 - 10:10'),(03,'10:10 - 11:00');
/*!40000 ALTER TABLE `timetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `traveldata`
--

DROP TABLE IF EXISTS `traveldata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `traveldata` (
  `travelId` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `studentId` int(5) unsigned zerofill DEFAULT NULL,
  `date` date DEFAULT NULL,
  `time` time DEFAULT NULL,
  `type` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`travelId`),
  KEY `stu2_idx` (`studentId`),
  CONSTRAINT `stu2` FOREIGN KEY (`studentId`) REFERENCES `student` (`studentId`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `traveldata`
--

LOCK TABLES `traveldata` WRITE;
/*!40000 ALTER TABLE `traveldata` DISABLE KEYS */;
/*!40000 ALTER TABLE `traveldata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userdetail`
--

DROP TABLE IF EXISTS `userdetail`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `userdetail` (
  `userId` int(6) unsigned zerofill NOT NULL,
  `name` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userdetail`
--

LOCK TABLES `userdetail` WRITE;
/*!40000 ALTER TABLE `userdetail` DISABLE KEYS */;
INSERT INTO `userdetail` VALUES (000001,'xdxd'),(000006,'naay');
/*!40000 ALTER TABLE `userdetail` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
 SET character_set_client = utf8mb4 ;
CREATE TABLE `users` (
  `userId` int(6) unsigned zerofill NOT NULL AUTO_INCREMENT,
  `email` varchar(150) DEFAULT NULL,
  `password` varchar(150) DEFAULT NULL,
  `role` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (000001,'panupong.itkmitl@gmail.com','$2y$10$tiHo1Mz.MilOr8f/XYva1ujaU.PhzbuDHWe2B/SCP3JDAaUaW1Pum','admin'),(000006,'naaystudio@gmail.com','$2y$10$D2C7.Tq9X9NJulDXUgze/u9aNe1XbB6IWdl2JuIRmCabOelF9N3oS','admin');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2019-02-23 10:36:27
