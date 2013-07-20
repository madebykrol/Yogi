-- MySQL dump 10.13  Distrib 5.1.69, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: smll_cms_1
-- ------------------------------------------------------
-- Server version	5.1.69-0ubuntu0.10.04.1

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
-- Table structure for table `file_reference`
--

DROP TABLE IF EXISTS `file_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `file_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ident` varchar(36) NOT NULL DEFAULT '',
  `filename` varchar(500) DEFAULT NULL,
  `mime` varchar(45) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `filepath` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`,`ident`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=141 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `file_reference`
--

LOCK TABLES `file_reference` WRITE;
/*!40000 ALTER TABLE `file_reference` DISABLE KEYS */;
INSERT INTO `file_reference` VALUES (16,'eac61007-b637-4ffa-bcc7-9a032c57279b','src/assets/files/cover/avatar3.png',NULL,NULL,NULL),(15,'9550e329-e59e-484b-9da3-bbb8c5f02893','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(14,'fb2cca6d-bf1c-4a5d-b8e4-8c8811ee4dc2','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(13,'86ba8309-3156-4267-8e74-c2f350449516','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(12,'960d4579-2282-442e-92aa-6d9d6a3188fd','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(11,'04595eb2-2176-44a3-834b-b60916a18a1b','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(17,'fc66e183-3c25-4b0b-a823-3db036c02c2d','src/assets/files/cover/avatar3.png',NULL,NULL,NULL),(41,'d5315f0a-79d9-45c5-9ecb-9e84b066b6e6','85c55ab3-451b-49db-8010-e7ef0bdb2292',NULL,NULL,NULL),(40,'85c55ab3-451b-49db-8010-e7ef0bdb2292','src/assets/files/cover/avatar.png',NULL,NULL,NULL),(39,'1ffb0bf5-ccd6-43a4-b9d0-6cf7bf53bb11','9400cd06-b5a0-4a1c-b8e7-c197560b0fd2',NULL,NULL,NULL),(38,'9400cd06-b5a0-4a1c-b8e7-c197560b0fd2',NULL,NULL,NULL,NULL),(37,'fba1c2bf-6244-4a43-a1e3-6d9fb6069314',NULL,NULL,NULL,NULL),(34,'f69a450b-e2dc-4d30-88b8-a56cf3c92234','src/assets/files/cover/avatar3.png',NULL,NULL,NULL),(33,'89336b36-be0e-48fa-aad1-7f6664dc6d2a','src/assets/files/cover/avatar2.png',NULL,NULL,NULL),(36,'99c37aa9-f53b-4be5-a7f3-bab304a0e1e2','src/assets/files/cover/aGp0cOU.jpg',NULL,NULL,NULL),(35,'9a40d1e6-f109-48ce-ac14-5c1a96f921a4','src/assets/files/cover/avatar3.png',NULL,NULL,NULL),(42,'3d094ea6-4a11-4452-96f4-353675cbe6db','src/assets/files/cover/aGp0cOU.jpg',NULL,NULL,NULL),(43,'67c05711-c723-4fd9-8d32-a8ed2519254c','src/assets/files/cover/avatar.png',NULL,NULL,NULL),(44,'f23c8a28-915e-40e0-ad7e-570859d1d441','src/assets/files/cover/avatar.png',NULL,NULL,NULL),(45,'4342ec40-903d-4013-b4e2-c89c1c3ca6f7','src/assets/files/cover/avatar.png',NULL,NULL,NULL),(46,'fa5e35b6-5f1a-4af5-9c86-8ab8bb3ee906','src/assets/files/cover/avatar.png',NULL,NULL,NULL),(47,'032d160b-88f1-460f-9fa3-cc5b3b03d531','src/assets/files/cover/Derp.png',NULL,NULL,NULL),(48,'a482d6c9-d8f9-43aa-8dcb-66959ca423a8','src/assets/files/cover/avatar3.png',NULL,NULL,NULL),(49,'b97462ed-c234-4fc3-a685-c0d0e2f37a63','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(50,'d98d1ef7-6946-4ceb-b370-e0841289c2d1','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(51,'f4d66e54-aa22-461d-a7d4-0497aca697da',NULL,NULL,NULL,NULL),(52,'306f6571-e455-4cfe-86ce-bc6dbb0811a1','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(53,'bbdd9b60-2563-41ed-8f33-e82000275671','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(54,'3d286784-3305-4d5c-802f-017a86917e31','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(55,'d6461774-a860-4c92-baad-3df33652676c','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(56,'2993c1ee-8f68-4d00-9881-936d4fb2b867','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(57,'fbf28258-ca8d-43af-aa0d-149e5120b7bb','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(58,'801261c0-8df8-4c66-a6ec-6089c565b2ba','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(59,'643f7e39-a833-441c-8471-09f861e9dc13','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(60,'bf4fefc7-1699-4334-b736-742c6d6ad113','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(61,'60e04aca-0a04-4391-ac96-14ab52ac6036',NULL,NULL,NULL,NULL),(62,'526af4ee-6416-4e2b-a8bb-5aaa86a56d04',NULL,NULL,NULL,NULL),(63,'4fd7f872-00ce-45e7-9222-f95f34b063ca','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(64,'46c23d73-31f5-4ebc-9e31-48ff81f44daf','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(65,'1133bf6e-c94e-4d1f-9c85-2e2b2bb64ae9','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(66,'133f4721-7c1f-473f-aad2-a78e80f78323','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(67,'4947d5ba-2e1d-4f35-a4db-d81839544759','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(68,'280869d9-50d2-4d45-a0db-9cbd4658b168','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(69,'faac3cbb-9649-4ac3-898a-74fd1e1b92c6','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(70,'71a7a9a7-7bd2-4653-b3f9-302723ebf243','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(71,'03e3bce6-1c70-4fba-b4a0-9f1cab6f68f9','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(72,'fbc4dbf1-f1db-403f-a40a-c3daa5a9fc8e','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(73,'7e2281a4-0198-439c-8eb1-178adc66290b','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(74,'5f07406b-e77e-4130-b13c-b6e144e762b1','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(75,'3a9fb78d-9de5-4d7f-8ba1-9272269b0db6','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(76,'1375b2e4-bdeb-4ad3-8d35-c48c30200dbb','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(77,'e5ac51ff-905a-4edf-ba37-95b35ae30b6f','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(78,'9971abac-7535-49ed-bb7d-2e9008f392a2','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(79,'bd970958-48ca-4e0b-a4b8-05a310891ed7','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(80,'fce96a81-98a9-44f4-a0e2-82773229a727','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(81,'a80b804b-c716-49a1-a3c8-fef1f4238ea2','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(82,'9bc03ea1-3255-4a95-83e0-e6c81b09acb4','src/assets/files/cover/images.jpg',NULL,NULL,NULL),(83,'65827c27-cb73-4096-a90e-529cf88b250b','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(84,'d193d391-8c29-4a11-8762-6f8c3d5e4119','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(85,'59bc5f33-3a36-40cf-a362-3fbf529b39ed','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(86,'ea1a84ca-2ac5-4cc1-a2b6-3aaff08ea9fe','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(87,'eda5fcc8-14c8-4ef8-8ff4-e4edf015a5bb','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(88,'847fc998-2f65-4347-bdc0-cbee0f8325d3','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(89,'0e2d71b1-2980-4585-a97c-d43ca13d3dd8','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(90,'52a5f20a-5b3c-4c86-9de8-40a4e68bf852','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(91,'15433ff8-a1c2-45d5-8ded-f4b181f2a8c7','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(92,'81f4df90-c6a4-4667-9efb-2a1fe3c49ddd','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(93,'67c2d694-52f7-40e8-9931-4f3cab9320a4','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(94,'56b9248a-a8c2-4512-9c8d-94e69358b7f4','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(95,'2cb51ff4-afc6-4416-b380-6d39278396c2','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(96,'584eeb00-eab9-4cbe-a6de-af025d9626bc','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(97,'371ebaf3-90dd-4a9e-b69d-3cb32935fd5b','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(98,'5bcf8489-bc6a-48d9-9dde-68e53279c1ba','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(99,'f0d42ac2-aaf9-4d12-84be-4adbaf2cada1','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(100,'a89e2b21-759c-4bd3-8c40-11c1632145ce','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(101,'bf355a6f-802d-4650-80ab-282fb4647224','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(102,'32d67ed2-fcd0-42b5-86f6-478382b19bec','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(103,'c3754a18-0186-4b35-9855-78d303018a29','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(104,'b97b3973-3405-4238-b52f-650148f22f77','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(105,'3a929dac-0a3c-4641-b4a1-35540e185109','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(106,'07ea8324-5d8c-4fc3-b34b-19174b2b1a9d','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(107,'6323e4b1-fcf0-4adf-ae1e-ddfa978e5cd5','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(108,'ea92a079-2b7e-48a4-8870-161d1c53ac69','src/assets/files/cover/images.jpg',NULL,NULL,NULL),(109,'361ce7d6-eade-4b8b-8c1c-b3f5f38e5c92','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(110,'4082ddd9-b457-4a25-af73-14bc49b628b1','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(111,'ac99045c-8e48-4ebe-84c2-b510fb5fca78','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(112,'b7d52c24-b9b1-4db2-8d9d-49d3cbe61fc4','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(113,'5caf6684-f1a8-45bd-8c2f-5332c703b148','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(114,'6d89e651-3615-4c5d-9373-5b02d48f7bdc','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(115,'c3d84bf4-40b5-424e-ace9-cd50b1eea0ec','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(116,'d3e1a497-5f7d-406b-8c04-94b7026971fa','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(117,'9d1aede7-eb95-425f-933b-dd511bf1817c','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(118,'268dc488-2037-4993-ab8a-0c28c0226748','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(119,'c677edff-74c4-4c29-8fdd-81af70bc78c6','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(120,'26b2ac44-e137-4b6b-9e32-6fc8dd1a1303','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(121,'f6045c0f-7af7-4f86-a2f5-aaf8935c7e71','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(122,'6e0206e6-e928-4642-9493-635b6e3fda9a','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(123,'f1a08cd3-cf70-4133-a641-15e9a6d3ee04','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(124,'621bba6f-f16b-42d3-8638-78ee550d36f6','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(125,'65c2d687-bafa-4edd-9ac6-f42c0d9de577','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(126,'25d15f4b-a7e5-45de-b603-c1a65173bec9','src/assets/files/cover/animepaper.net_wallpaper_art_video_games_final_fantasy_ix_princess_of_alexandria_196847_megan_1280x800-0f2812d7.jpg',NULL,NULL,NULL),(127,'6c2acabe-d813-4e7a-948f-af12f9e3f334','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg',NULL,NULL,NULL),(128,'77a0be59-bcb0-40de-9473-ef5691f65fab','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif',NULL,NULL,NULL),(129,'3a4d4716-c9df-438e-9e1d-0695147d0322','src/assets/files/cover/As62PLY.jpg',NULL,NULL,NULL),(130,'f051047a-3a6f-49b2-ac81-f4d91549cd1c','src/assets/files/cover/I swear she\'s trying to break the internet. - Imgur.gif','image/gif',947526,NULL),(131,'2a4a5fba-dc90-4f20-b8c0-8e7b6123ffa7','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg','image/jpeg',73860,NULL),(132,'e3cf435d-1aab-410b-bf05-dbb39b8d9b74','src/assets/files/cover/1013768_10152895458050057_1465708774_n.jpg','image/jpeg',100283,NULL),(133,'6199ee6e-e645-4086-b875-a43a244939ab','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg','image/jpeg',73860,NULL),(134,'a83644a6-eeb6-4a03-91e2-662dabe7863c','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg','image/jpeg',73860,NULL),(135,'7abd2249-ea32-4b3b-8cac-a2910e3e5a8c','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg','image/jpeg',73860,NULL),(136,'5fabeeb4-0f7b-45bb-a378-eefa04939557','src/assets/files/cover/59535__468x_tifa-lockhart-topless-suspenders-by-ueyama-michirou.jpg','image/jpeg',73860,NULL),(137,'818a3cc9-3c15-439b-acb5-2517b0be6c9b','src/assets/files/cover/gears-of-war-cover-thumb.jpg','image/jpeg',30928,NULL),(138,'f18c3956-b2b0-4be6-835c-0631aa40b5b3','src/assets/files/cover/gears-of-war-cover-thumb.jpg','image/jpeg',30928,NULL),(139,'eda52495-e17d-4864-aad0-7af69d41519b','src/assets/files/cover/jaquette-borderlands-2-pc-cover-avant-g-1348066631.jpg','image/jpeg',1170327,NULL),(140,'6aeaeaec-f714-4174-be4c-656d7d2cc8e3','src/assets/files/cover/legend-of-zelda-wind-waker-cover.jpg','image/jpeg',72832,NULL);
/*!40000 ALTER TABLE `file_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `memberships`
--

DROP TABLE IF EXISTS `memberships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `memberships` (
  `ident` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `memberships`
--

LOCK TABLES `memberships` WRITE;
/*!40000 ALTER TABLE `memberships` DISABLE KEYS */;
INSERT INTO `memberships` VALUES ('bcec3843-e411-47aa-b7b0-873b70cc89d1','administrator','$2a$08$D90c/gIACurXCcGxpaofrONhS4Upg8aIyQ/ZRzrwK5MxakI32YHsy');
/*!40000 ALTER TABLE `memberships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page`
--

DROP TABLE IF EXISTS `page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkPageTypeId` int(11) NOT NULL,
  `authorName` varchar(100) NOT NULL,
  `visibleInMenu` int(1) DEFAULT '1',
  `creationDate` datetime DEFAULT NULL,
  `editDate` datetime DEFAULT NULL,
  `published` int(1) DEFAULT '0',
  `ident` varchar(36) DEFAULT NULL,
  `parentId` int(11) NOT NULL DEFAULT '0',
  `externalUrl` varchar(400) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `peerOrderWeight` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `id_ident_UNIQUE` (`id`,`ident`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page`
--

LOCK TABLES `page` WRITE;
/*!40000 ALTER TABLE `page` DISABLE KEYS */;
/*!40000 ALTER TABLE `page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_definition`
--

DROP TABLE IF EXISTS `page_definition`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_definition` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkPageTypeId` int(11) NOT NULL,
  `fkPageDefinitionTypeId` int(11) NOT NULL,
  `fkPageDefinitionTypeRenderer` int(11) NOT NULL,
  `name` varchar(90) DEFAULT NULL,
  `searchable` int(1) DEFAULT NULL,
  `longStringSettings` int(11) DEFAULT NULL,
  `weightOrder` varchar(45) DEFAULT NULL,
  `required` int(1) DEFAULT NULL,
  `displayName` varchar(255) DEFAULT NULL,
  `tab` varchar(50) DEFAULT NULL,
  `enabled` int(1) DEFAULT '1',
  `maxInputValues` int(2) DEFAULT '1',
  `minInputValues` int(2) DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_definition`
--

LOCK TABLES `page_definition` WRITE;
/*!40000 ALTER TABLE `page_definition` DISABLE KEYS */;
INSERT INTO `page_definition` VALUES (1,1,3,0,'mainBody',1,NULL,NULL,1,'Body','Content',1,1,1),(2,1,1,0,'title',1,NULL,'-50',1,'Title','Content',1,1,1),(3,1,2,0,'published',0,NULL,NULL,1,'Publish','Settings',1,1,1),(4,1,5,0,'id',0,NULL,NULL,0,NULL,'Settings',1,1,1),(5,1,1,0,'externalUrl',0,NULL,NULL,1,'External URL','Settings',1,1,1),(6,1,1,0,'authorName',0,NULL,NULL,1,'Author name','Content',1,1,1),(7,1,5,0,'ident',0,NULL,NULL,0,NULL,'Settings',1,1,1),(8,1,2,0,'visibleInMenu',0,NULL,NULL,0,'Visible in menu','Menu',1,1,1),(9,1,1,0,'peerOrderWeight',0,NULL,NULL,0,'Order in menu tree','Menu',1,1,1),(10,1,4,0,'parentId',0,NULL,NULL,0,'Parent','Menu',1,1,1),(11,1,5,0,'creationDate',0,NULL,NULL,1,'','Content',1,1,1);
/*!40000 ALTER TABLE `page_definition` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_definition_renderer`
--

DROP TABLE IF EXISTS `page_definition_renderer`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_definition_renderer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkPageTypeId` int(11) NOT NULL,
  `fkPageDefinitionId` int(11) DEFAULT NULL,
  `renderer` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=13 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_definition_renderer`
--

LOCK TABLES `page_definition_renderer` WRITE;
/*!40000 ALTER TABLE `page_definition_renderer` DISABLE KEYS */;
INSERT INTO `page_definition_renderer` VALUES (11,1,52,'smll\\cms\\framework\\ui\\fields\\CheckboxTaxonomyRenderer'),(12,1,36,'smll\\cms\\framework\\ui\\fields\\CheckboxTaxonomyRenderer');
/*!40000 ALTER TABLE `page_definition_renderer` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_definition_type`
--

DROP TABLE IF EXISTS `page_definition_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_definition_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type_name` varchar(255) DEFAULT NULL,
  `assembler` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_definition_type`
--

LOCK TABLES `page_definition_type` WRITE;
/*!40000 ALTER TABLE `page_definition_type` DISABLE KEYS */;
INSERT INTO `page_definition_type` VALUES (1,'Text','text','smll\\cms\\framework\\content\\fieldtype\\TextField'),(2,'Boolean','checkbox','smll\\cms\\framework\\content\\fieldtype\\BooleanField'),(3,'XmlString','textarea','smll\\cms\\framework\\content\\fieldtype\\XmlStringField'),(4,'PageReference','text','smll\\cms\\framework\\content\\fieldtype\\PageReferenceField'),(5,'Hidden','hidden','smll\\cms\\framework\\content\\fieldtype\\HiddenField'),(6,'DateTime','text','smll\\cms\\framework\\content\\fieldtype\\DateTimeField'),(7,'Image','file','smll\\cms\\framework\\content\\fieldtype\\ImageField'),(8,'Taxonomy','number','smll\\cms\\framework\\content\\fieldtype\\TaxonomyField');
/*!40000 ALTER TABLE `page_definition_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_translation_reference`
--

DROP TABLE IF EXISTS `page_translation_reference`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_translation_reference` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkOriginalPageId` int(11) NOT NULL,
  `fkTranslatedVersion` int(11) NOT NULL,
  `language` varchar(3) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_translation_reference`
--

LOCK TABLES `page_translation_reference` WRITE;
/*!40000 ALTER TABLE `page_translation_reference` DISABLE KEYS */;
/*!40000 ALTER TABLE `page_translation_reference` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_type`
--

DROP TABLE IF EXISTS `page_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_type` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `typeGuid` varchar(36) NOT NULL,
  `controller` varchar(255) NOT NULL,
  `file` varchar(255) NOT NULL,
  `name` varchar(60) NOT NULL,
  `description` mediumtext NOT NULL,
  `displayName` varchar(60) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_type`
--

LOCK TABLES `page_type` WRITE;
/*!40000 ALTER TABLE `page_type` DISABLE KEYS */;
INSERT INTO `page_type` VALUES (1,'6847184b-b515-4144-ac2f-1046945fd6e4','Basic','src/content/pages/BasicPage.php','BasicPage','Create a new basic page','Basic page');
/*!40000 ALTER TABLE `page_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `page_type_permission`
--

DROP TABLE IF EXISTS `page_type_permission`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `page_type_permission` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkPageTypeId` int(11) NOT NULL,
  `role` varchar(150) NOT NULL,
  `event` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=229 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `page_type_permission`
--

LOCK TABLES `page_type_permission` WRITE;
/*!40000 ALTER TABLE `page_type_permission` DISABLE KEYS */;
INSERT INTO `page_type_permission` VALUES (188,2,'Editor','Delete'),(187,2,'Administrator','Edit'),(186,2,'Editor','Edit'),(185,2,'User','View'),(162,3,'Editor','Delete'),(161,3,'Administrator','Edit'),(160,3,'Editor','Edit'),(159,3,'User','View'),(168,4,'Editor','Delete'),(167,4,'Administrator','Edit'),(166,4,'Editor','Edit'),(165,4,'User','View'),(164,4,'Anonymous','View'),(184,2,'Anonymous','View'),(158,3,'Anonymous','View'),(228,1,'Administrator','Delete'),(189,2,'Administrator','Delete'),(163,3,'Administrator','Delete'),(169,4,'Administrator','Delete'),(227,1,'Editor','Delete'),(226,1,'Administrator','Edit'),(225,1,'Editor','Edit'),(224,1,'User','View'),(223,1,'Anonymous','View');
/*!40000 ALTER TABLE `page_type_permission` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `property`
--

DROP TABLE IF EXISTS `property`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `property` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkPageId` int(11) NOT NULL,
  `fkPageDefinitionId` int(11) NOT NULL,
  `index` varchar(45) NOT NULL DEFAULT '0',
  `linkGuid` varchar(45) DEFAULT NULL,
  `longString` longtext,
  `number` int(11) DEFAULT NULL,
  `pageRef` int(11) DEFAULT NULL,
  `date` datetime DEFAULT NULL,
  `string` varchar(255) DEFAULT NULL,
  `boolean` int(1) DEFAULT NULL,
  `peerOrderWeight` int(11) DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `property`
--

LOCK TABLES `property` WRITE;
/*!40000 ALTER TABLE `property` DISABLE KEYS */;
/*!40000 ALTER TABLE `property` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `roles` (
  `application` varchar(100) NOT NULL,
  `role_id` varchar(100) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `description` varchar(100) NOT NULL,
  PRIMARY KEY (`role_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES ('#1','75d8agd1-96fc-430c-b630-deba9b7c051','Administrator','Application administrator'),('#1','125bfdf9-79e8-454c-86f2-06f8395150e5','Editor','Content Editor');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxonomy_term`
--

DROP TABLE IF EXISTS `taxonomy_term`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxonomy_term` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fkTaxonomyVocabularyId` int(11) DEFAULT NULL,
  `term` varchar(150) DEFAULT NULL,
  `description` text,
  `parent` int(11) DEFAULT NULL,
  `short` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxonomy_term`
--

LOCK TABLES `taxonomy_term` WRITE;
/*!40000 ALTER TABLE `taxonomy_term` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxonomy_term` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `taxonomy_vocabulary`
--

DROP TABLE IF EXISTS `taxonomy_vocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `taxonomy_vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) DEFAULT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `taxonomy_vocabulary`
--

LOCK TABLES `taxonomy_vocabulary` WRITE;
/*!40000 ALTER TABLE `taxonomy_vocabulary` DISABLE KEYS */;
/*!40000 ALTER TABLE `taxonomy_vocabulary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `url_route`
--

DROP TABLE IF EXISTS `url_route`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `url_route` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `internal_path` varchar(500) NOT NULL,
  `url` varchar(500) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `url_route`
--

LOCK TABLES `url_route` WRITE;
/*!40000 ALTER TABLE `url_route` DISABLE KEYS */;
/*!40000 ALTER TABLE `url_route` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `ident` varchar(36) NOT NULL,
  `application` varchar(100) NOT NULL,
  `username` varchar(255) NOT NULL,
  `last_active_date` datetime DEFAULT NULL,
  PRIMARY KEY (`ident`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('bcec3843-e411-47aa-b7b0-873b70cc89d1','#1','kw33l','2013-06-14 15:59:08');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_in_roles`
--

DROP TABLE IF EXISTS `users_in_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users_in_roles` (
  `user_ident` varchar(100) NOT NULL,
  `role_id` varchar(100) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_in_roles`
--

LOCK TABLES `users_in_roles` WRITE;
/*!40000 ALTER TABLE `users_in_roles` DISABLE KEYS */;
INSERT INTO `users_in_roles` VALUES ('bcec3843-e411-47aa-b7b0-873b70cc89d1','75d8agd1-96fc-430c-b630-deba9b7c051',1),('bcec3843-e411-47aa-b7b0-873b70cc89d1','125bfdf9-79e8-454c-86f2-06f8395150e5',3);
/*!40000 ALTER TABLE `users_in_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2013-07-20  2:06:06
