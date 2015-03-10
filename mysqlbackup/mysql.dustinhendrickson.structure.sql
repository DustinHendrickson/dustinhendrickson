-- MySQL dump 10.13  Distrib 5.5.41, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: DustinDB
-- ------------------------------------------------------
-- Server version	5.5.41-0ubuntu0.14.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `achievements`
--

DROP TABLE IF EXISTS `achievements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `achievements` (
  `ID` int(10) NOT NULL AUTO_INCREMENT,
  `Name` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
  `Image` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Description` text COLLATE utf8_unicode_ci NOT NULL,
  `Points` int(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `base_daily_quests`
--

DROP TABLE IF EXISTS `base_daily_quests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `base_daily_quests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Name` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Description` varchar(50) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `Points` int(11) NOT NULL DEFAULT '0',
  `NeededObjective` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `Title` varchar(54) NOT NULL,
  `Body` mediumtext NOT NULL,
  `Creation_Date` datetime NOT NULL,
  `Active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_comments` (
  `CommentID` int(255) NOT NULL AUTO_INCREMENT,
  `BlogPostID` int(255) NOT NULL,
  `CommentText` mediumtext NOT NULL,
  `CommentUserID` varchar(100) NOT NULL,
  `CommentDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `daily_quests`
--

DROP TABLE IF EXISTS `daily_quests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `daily_quests` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `QuestID` int(11) DEFAULT NULL,
  `UserID` int(11) NOT NULL DEFAULT '0',
  `CurrentObjective` int(11) NOT NULL DEFAULT '0',
  `NeededObjective` int(11) NOT NULL DEFAULT '0',
  `Points` int(100) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `inventory`
--

DROP TABLE IF EXISTS `inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `inventory` (
  `User_ID` int(255) NOT NULL,
  `Item_ID` int(255) NOT NULL AUTO_INCREMENT,
  `Item_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Item_Image` text COLLATE utf8_unicode_ci NOT NULL,
  `Item_Description` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`Item_ID`),
  UNIQUE KEY `Item_ID` (`Item_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=192 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pet_abilitys`
--

DROP TABLE IF EXISTS `pet_abilitys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pet_abilitys` (
  `Ability_ID` int(11) NOT NULL AUTO_INCREMENT,
  `Ability_Name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Ability_Damage` int(11) NOT NULL,
  `Ability_Damage_Type` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Ability_Effect` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `Ability_Cooldown` int(11) NOT NULL,
  PRIMARY KEY (`Ability_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=129 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `pets`
--

DROP TABLE IF EXISTS `pets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pets` (
  `User_ID` int(100) NOT NULL,
  `Pet_ID` int(255) NOT NULL AUTO_INCREMENT,
  `Pet_Image` varchar(400) COLLATE utf8_unicode_ci NOT NULL,
  `Pet_Offense` int(11) NOT NULL,
  `Pet_Defense` int(11) NOT NULL,
  `Pet_Current_Health` int(11) NOT NULL,
  `Pet_Max_Health` int(11) NOT NULL,
  `Pet_Current_AP` int(11) NOT NULL,
  `Pet_Max_AP` int(11) NOT NULL,
  `Pet_Skill_1` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pet_Skill_2` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pet_Skill_3` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `Pet_Bonus_Offense` int(11) NOT NULL DEFAULT '0',
  `Pet_Bonus_Defense` int(11) NOT NULL DEFAULT '0',
  `Pet_Bonus_Health` int(11) NOT NULL DEFAULT '0',
  `Pet_Bonus_EXP` int(11) NOT NULL DEFAULT '0',
  `Pet_Exp` int(11) NOT NULL DEFAULT '0',
  `Pet_Level` int(11) NOT NULL DEFAULT '1',
  `Pet_Name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Pet_Type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Pet_Status` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'Alive',
  `Pet_Active` tinyint(1) NOT NULL DEFAULT '0',
  `Pet_Tier` int(11) NOT NULL,
  PRIMARY KEY (`Pet_ID`)
) ENGINE=InnoDB AUTO_INCREMENT=423 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(26) NOT NULL,
  `First_Name` varchar(26) NOT NULL,
  `Last_Name` varchar(26) NOT NULL,
  `Password` varchar(90) NOT NULL,
  `EMail` varchar(50) NOT NULL,
  `Permissions` varchar(20) DEFAULT NULL,
  `Account_Last_Login` datetime DEFAULT NULL,
  `Account_Created` datetime DEFAULT NULL,
  `Account_Locked` tinyint(1) NOT NULL,
  `Points` int(100) NOT NULL DEFAULT '0',
  `Points_Last_Recieved` datetime DEFAULT NULL,
  `FightBot_Name` varchar(50) DEFAULT NULL,
  `Achievements_Unlocked` varchar(255) NOT NULL,
  `Pet_Battles_Won` int(100) NOT NULL,
  `Pet_Battles_Lost` int(100) NOT NULL,
  `Pets_Caught` int(100) NOT NULL,
  `Last_Daily_Quest_Recieved` datetime DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=42 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users_settings`
--

DROP TABLE IF EXISTS `users_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_settings` (
  `SettingID` int(100) NOT NULL AUTO_INCREMENT,
  `UserID` int(100) NOT NULL,
  `Items_Per_Page` int(100) NOT NULL DEFAULT '5',
  `Theme` varchar(26) NOT NULL DEFAULT 'Default',
  `Show_Help` tinyint(1) NOT NULL DEFAULT '1',
  `Show_Toasts` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`SettingID`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-10  7:47:25
