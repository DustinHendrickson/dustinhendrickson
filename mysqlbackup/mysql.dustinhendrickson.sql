-- MySQL dump 10.13  Distrib 5.5.38, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: DustinDB
-- ------------------------------------------------------
-- Server version	5.5.38-0ubuntu0.14.04.1

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
-- Table structure for table `blog`
--

DROP TABLE IF EXISTS `blog`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `UserID` int(11) NOT NULL,
  `Title` varchar(54) NOT NULL,
  `Body` text NOT NULL,
  `Creation_Date` datetime NOT NULL,
  `Active` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`ID`),
  KEY `UserID` (`UserID`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog`
--

LOCK TABLES `blog` WRITE;
/*!40000 ALTER TABLE `blog` DISABLE KEYS */;
INSERT INTO `blog` VALUES (41,1,'Welcome to DustinHendrickson.com','<p>Thank you for checking out my site!</p>\r\n<p>I\'ll be adding new features and content all the time so make sure to check back!</p>','2013-11-21 12:54:08',1),(42,1,'OpenSSL bug cripples internet.','<p>A bug that allows a hacker to get all your secret keys off your webserver surfaced today. If you\'re running your own webserver make sure you update OpenSSL!</p>\r\n<p>The Heartbleed Bug is a serious vulnerability in the popular OpenSSL cryptographic software library. This weakness allows stealing the information protected, under normal conditions, by the SSL/TLS encryption used to secure the Internet. SSL/TLS provides communication security and privacy over the Internet for applications such as web, email, instant messaging (IM) and some virtual private networks (VPNs).</p>\r\n<p>The Heartbleed bug allows anyone on the Internet to read the memory of the systems protected by the vulnerable versions of the OpenSSL software. This compromises the secret keys used to identify the service providers and to encrypt the traffic, the names and passwords of the users and the actual content. This allows attackers to eavesdrop on communications, steal data directly from the services and users and to impersonate services and users.</p>\r\n<p>Make sure to <a href=\"http://heartbleed.com/\" target=\"_blank\">READ</a>&nbsp;more information about this bug!</p>','2014-04-08 17:27:38',1),(43,1,'Blizzard Arcade \"Rock the Cabinet\" Map Making Contest','<p>Blizzard is hosting a Map Making Contest called <strong><a title=\"Rock the Cabnet\" href=\"http://us.battle.net/arcade/en/blog/12709722/blizzard-arcade-rock-the-cabinet-contest-1-30-2014\" target=\"_blank\">Rock the Cabinet</a></strong>. My map&nbsp;<strong><a title=\"Hybrid Assault\" href=\"?view=projects\" target=\"_blank\">Hybrid Assault</a></strong>&nbsp;has been entered into this contest! If my map makes it to the Top 10 Finalists then you\'ll be able to vote for it!</p>\r\n<p>Here is the forum link for my submission.&nbsp;<strong><a title=\"Map Submission Forum Post\" href=\"http://us.battle.net/sc2/en/forum/topic/13021802074\" target=\"_blank\">http://us.battle.net/sc2/en/forum/topic/13021802074</a></strong></p>\r\n<p>Here\'s a video of an old build of my map.</p>\r\n<p><strong><a title=\"Hybrid Assault Video\" href=\"http://www.tubechop.com/watch/2952814\" target=\"_blank\">http://www.tubechop.com/watch/2952814</a></strong></p>\r\n<p>Check out the sumbissions!</p>\r\n<p><img style=\"display: block; margin-left: auto; margin-right: auto;\" src=\"https://bnetcmsus-a.akamaihd.net/cms/gallery/BD8KJYSATQ8S1402325496875.jpg\" alt=\"Entrys\" width=\"1020\" height=\"1874\" /></p>','2014-06-10 21:20:17',1),(44,1,'Unity 3D','<p>So I\'ve decided to jump from Mainly Game modding to full blown game development using Unity 3D.</p>\r\n<p>I\'ll be working through some prototypes just to get a feel for Unity and what it can accomplish for me, the main goal is to use Unity for my Boardgame Project, where the 3D Graphics will be displayed on your smartphone in real time and show hero stats, abilities and combat. I already have the lighted tile code working and i\'m activley creating an API for it to use in my project. I\'ll be updating this post with some videos and rough draft concept ideas over this next week. I\'m hopefully going to be bloging about the process on here frequently as well.</p>\r\n<p><img src=\"uploads/boardgame_dataflow_diagram.png\" alt=\"Early Board Diagram\" width=\"700\" height=\"315\" /></p>','2014-09-04 13:39:46',1);
/*!40000 ALTER TABLE `blog` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `blog_comments`
--

DROP TABLE IF EXISTS `blog_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `blog_comments` (
  `CommentID` int(255) NOT NULL AUTO_INCREMENT,
  `BlogPostID` int(255) NOT NULL,
  `CommentText` text NOT NULL,
  `CommentUserID` varchar(100) NOT NULL,
  `CommentDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`CommentID`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `blog_comments`
--

LOCK TABLES `blog_comments` WRITE;
/*!40000 ALTER TABLE `blog_comments` DISABLE KEYS */;
INSERT INTO `blog_comments` VALUES (12,44,'Good post!!','1','2014-09-06 23:44:25');
/*!40000 ALTER TABLE `blog_comments` ENABLE KEYS */;
UNLOCK TABLES;

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
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Dustin','Dustin','Hendrickson','b1f4f9a523e36fd969f4573e25af4540','dustin.hendrickson@gmail.com','1,2,3,4','2014-09-10 14:04:25','2013-02-28 00:00:00',0,314,'2014-09-10 11:11:49','Dustin|Paladin'),(7,'Mopman','Sam','Pizzey','81DC9BDB52D04DC20036DBD8313ED055','sam@pizzey.me','1,2,3,4','2014-01-02 10:22:16','2013-04-17 19:59:39',0,0,NULL,NULL),(15,'MickeyC','Kyle','McCarley','b497dd1a701a33026f7211533620780d','kyller13@hotmail.com','1,2,3,4','2014-09-06 16:29:25','2013-04-19 12:47:50',0,0,NULL,'MickeyC'),(25,'theerik','Erik','Sanburn','e48e13207341b6bffb7fb1622282247b','theerik@theerik.com','1,2,3,4','2014-08-04 14:36:01','2013-12-10 18:49:30',0,9,'2014-08-04 14:45:08','theerik'),(28,'fenixleader','Derek','Hendrickson','acb212c2ef031c31c4a428f7756b8330','doomfenix.rage@gmail.com','1,2,3,4','2013-12-10 20:40:51','2013-12-10 20:40:51',0,0,NULL,NULL),(29,'Snickersnee','Kevin','Gavin','bc06aee8b67d96e2a1194a4e88c6c8e3','3dsnickersnee@gmail.com','2,3,4','2013-12-12 00:55:26','2013-12-11 19:33:26',0,0,NULL,NULL),(30,'Mopman1337','','','179ad45c6ce2cb97cf1029e212046e81','mopman@gmail.com','4','2014-01-02 10:07:54','2014-01-02 10:07:54',0,0,NULL,NULL),(31,'Nathan','Nathan','Hendrickson','528338ac4c5f76f1805792372fe4c525','Nathan.hendrickson@gmail.com','1,2,3,4','2014-08-04 13:57:24','2014-04-20 17:20:57',0,5,'2014-08-04 14:07:40',NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_settings`
--

DROP TABLE IF EXISTS `users_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_settings` (
  `UserID` int(100) NOT NULL,
  `Items_Per_Page` int(100) NOT NULL DEFAULT '5',
  `Theme` varchar(26) NOT NULL DEFAULT 'Default',
  `Show_Help` tinyint(1) NOT NULL DEFAULT '1',
  `SettingID` int(100) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`SettingID`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_settings`
--

LOCK TABLES `users_settings` WRITE;
/*!40000 ALTER TABLE `users_settings` DISABLE KEYS */;
INSERT INTO `users_settings` VALUES (1,1,'Default',1,1),(18,5,'Default',1,2),(19,5,'Default',1,3),(20,5,'Default',1,4),(21,5,'Default',1,5),(22,5,'Default',1,6),(23,5,'Default',1,7),(24,5,'Default',1,8),(25,5,'Default',1,9),(26,2,'Default',1,10),(28,5,'Default',1,12),(29,5,'Default',1,13),(30,5,'Default',1,14),(31,5,'Orange',1,15);
/*!40000 ALTER TABLE `users_settings` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-09-12 10:42:39
