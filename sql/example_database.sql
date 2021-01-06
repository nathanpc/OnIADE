/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `accounts` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  UNIQUE KEY `email_UNIQUE` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `accounts` VALUES (1,'nathanpc','9019','hi@nathancampos.me','Nathan Campos',1),(2,'rodrigob','1234',NULL,'Rodrigo Braga',1);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_history` (
  `id` int NOT NULL AUTO_INCREMENT,
  `device_id` int NOT NULL,
  `floor_id` int NOT NULL,
  `ip_addr` varchar(18) NOT NULL,
  `dt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_device_idx` (`device_id`),
  KEY `fk_floor_idx` (`floor_id`),
  CONSTRAINT `fk_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_hist_floor` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=329 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `device_history` VALUES (1,1,2,'192.168.1.57','2020-12-28 17:06:54'),(2,2,3,'192.168.1.58','2020-12-28 17:06:54'),(3,3,2,'192.168.1.71','2020-12-28 17:06:54'),(4,4,2,'192.168.1.43','2020-12-28 17:06:54'),(5,5,2,'192.168.1.15','2020-12-28 17:06:55'),(6,6,1,'192.168.1.51','2020-12-28 17:06:55'),(7,7,1,'192.168.1.53','2020-12-28 17:06:55'),(8,8,1,'192.168.1.54','2020-12-28 17:06:55'),(9,9,1,'192.168.1.1','2020-12-28 17:06:55'),(10,10,2,'192.168.1.55','2020-12-28 17:06:55'),(11,11,1,'192.168.1.2','2020-12-28 17:06:55'),(12,12,2,'192.168.1.251','2020-12-28 17:06:55'),(13,13,2,'192.168.1.252','2020-12-28 17:06:55'),(14,5,1,'192.168.1.255','2020-12-28 17:06:56'),(15,14,2,'192.168.1.250','2020-12-28 17:06:56'),(16,15,2,'192.168.1.244','2020-12-28 17:06:56'),(17,16,1,'192.168.1.254','2020-12-28 17:06:56'),(18,17,1,'192.168.1.246','2020-12-28 17:06:56'),(19,3,2,'192.168.1.71','2020-12-29 11:56:00'),(20,1,2,'192.168.1.57','2020-12-29 11:56:00'),(21,2,3,'192.168.1.58','2020-12-29 11:56:00'),(22,4,2,'192.168.1.43','2020-12-29 11:56:00'),(23,5,2,'192.168.1.15','2020-12-29 11:56:00'),(24,18,1,'192.168.1.7','2020-12-29 11:56:00'),(25,6,1,'192.168.1.51','2020-12-29 11:56:00'),(26,8,1,'192.168.1.54','2020-12-29 11:56:00'),(27,7,1,'192.168.1.53','2020-12-29 11:56:00'),(28,9,1,'192.168.1.1','2020-12-29 11:56:00'),(29,19,1,'192.168.1.2','2020-12-29 11:56:01'),(30,10,2,'192.168.1.55','2020-12-29 11:56:01'),(31,12,2,'192.168.1.251','2020-12-29 11:56:01'),(32,13,2,'192.168.1.252','2020-12-29 11:56:01'),(33,5,1,'192.168.1.255','2020-12-29 11:56:01'),(34,15,2,'192.168.1.244','2020-12-29 11:56:01'),(35,16,1,'192.168.1.254','2020-12-29 11:56:01'),(36,14,2,'192.168.1.250','2020-12-29 11:56:01'),(37,3,2,'192.168.1.71','2020-12-29 14:12:30'),(38,2,3,'192.168.1.58','2020-12-29 14:12:30'),(39,1,2,'192.168.1.57','2020-12-29 14:12:30'),(40,4,2,'192.168.1.43','2020-12-29 14:12:30'),(41,5,2,'192.168.1.15','2020-12-29 14:12:30'),(42,7,1,'192.168.1.53','2020-12-29 14:12:30'),(43,8,1,'192.168.1.54','2020-12-29 14:12:30'),(44,6,1,'192.168.1.51','2020-12-29 14:12:30'),(45,10,2,'192.168.1.55','2020-12-29 14:12:30'),(46,9,1,'192.168.1.1','2020-12-29 14:12:30'),(47,19,1,'192.168.1.2','2020-12-29 14:12:31'),(48,20,1,'192.168.1.4','2020-12-29 14:12:31'),(49,18,1,'192.168.1.7','2020-12-29 14:12:31'),(50,13,2,'192.168.1.252','2020-12-29 14:12:31'),(51,12,2,'192.168.1.251','2020-12-29 14:12:31'),(52,5,1,'192.168.1.255','2020-12-29 14:12:31'),(53,14,2,'192.168.1.250','2020-12-29 14:12:31'),(54,15,2,'192.168.1.244','2020-12-29 14:12:31'),(55,16,1,'192.168.1.254','2020-12-29 14:12:31'),(56,4,2,'192.168.1.43','2020-12-29 16:18:16'),(57,3,2,'192.168.1.71','2020-12-29 16:18:16'),(58,2,3,'192.168.1.58','2020-12-29 16:18:16'),(59,1,2,'192.168.1.57','2020-12-29 16:18:17'),(60,5,2,'192.168.1.15','2020-12-29 16:18:17'),(61,6,1,'192.168.1.51','2020-12-29 16:18:17'),(62,7,1,'192.168.1.53','2020-12-29 16:18:17'),(63,8,1,'192.168.1.54','2020-12-29 16:18:17'),(64,9,1,'192.168.1.1','2020-12-29 16:18:17'),(65,10,2,'192.168.1.55','2020-12-29 16:18:17'),(66,19,1,'192.168.1.2','2020-12-29 16:18:17'),(67,18,1,'192.168.1.7','2020-12-29 16:18:17'),(68,13,2,'192.168.1.252','2020-12-29 16:18:17'),(69,12,2,'192.168.1.251','2020-12-29 16:18:17'),(70,5,1,'192.168.1.255','2020-12-29 16:18:18'),(71,15,2,'192.168.1.244','2020-12-29 16:18:18'),(72,14,2,'192.168.1.250','2020-12-29 16:18:18'),(73,16,1,'192.168.1.254','2020-12-29 16:18:18'),(74,1,2,'192.168.1.57','2020-12-29 17:05:58'),(75,3,2,'192.168.1.71','2020-12-29 17:05:58'),(76,2,3,'192.168.1.58','2020-12-29 17:05:58'),(77,4,2,'192.168.1.43','2020-12-29 17:05:59'),(78,5,2,'192.168.1.15','2020-12-29 17:05:59'),(79,6,1,'192.168.1.51','2020-12-29 17:05:59'),(80,8,1,'192.168.1.54','2020-12-29 17:05:59'),(81,7,1,'192.168.1.53','2020-12-29 17:05:59'),(82,10,2,'192.168.1.55','2020-12-29 17:05:59'),(83,9,1,'192.168.1.1','2020-12-29 17:05:59'),(84,19,1,'192.168.1.2','2020-12-29 17:05:59'),(85,18,1,'192.168.1.7','2020-12-29 17:05:59'),(86,13,2,'192.168.1.252','2020-12-29 17:05:59'),(87,12,2,'192.168.1.251','2020-12-29 17:05:59'),(88,15,2,'192.168.1.244','2020-12-29 17:05:59'),(89,14,2,'192.168.1.250','2020-12-29 17:05:59'),(90,16,1,'192.168.1.254','2020-12-29 17:06:00'),(91,3,2,'192.168.1.71','2020-12-29 19:58:08'),(92,1,2,'192.168.1.57','2020-12-29 19:58:08'),(93,2,3,'192.168.1.58','2020-12-29 19:58:08'),(94,4,2,'192.168.1.43','2020-12-29 19:58:08'),(95,5,2,'192.168.1.15','2020-12-29 19:58:08'),(96,6,1,'192.168.1.51','2020-12-29 19:58:08'),(97,7,1,'192.168.1.53','2020-12-29 19:58:08'),(98,8,1,'192.168.1.54','2020-12-29 19:58:08'),(99,18,1,'192.168.1.7','2020-12-29 19:58:08'),(100,9,1,'192.168.1.1','2020-12-29 19:58:08'),(101,10,2,'192.168.1.55','2020-12-29 19:58:08'),(102,17,1,'192.168.1.3','2020-12-29 19:58:09'),(103,19,1,'192.168.1.4','2020-12-29 19:58:09'),(104,13,2,'192.168.1.252','2020-12-29 19:58:09'),(105,12,2,'192.168.1.251','2020-12-29 19:58:09'),(106,15,2,'192.168.1.244','2020-12-29 19:58:09'),(107,14,2,'192.168.1.250','2020-12-29 19:58:09'),(108,16,1,'192.168.1.254','2020-12-29 19:58:09'),(109,2,3,'192.168.1.58','2020-12-30 12:40:14'),(110,1,2,'192.168.1.57','2020-12-30 12:40:14'),(111,3,2,'192.168.1.71','2020-12-30 12:40:14'),(112,4,2,'192.168.1.43','2020-12-30 12:40:14'),(113,5,2,'192.168.1.15','2020-12-30 12:40:14'),(114,7,1,'192.168.1.53','2020-12-30 12:40:14'),(115,6,1,'192.168.1.51','2020-12-30 12:40:15'),(116,8,1,'192.168.1.54','2020-12-30 12:40:15'),(117,9,1,'192.168.1.1','2020-12-30 12:40:15'),(118,19,1,'192.168.1.3','2020-12-30 12:40:15'),(119,10,2,'192.168.1.55','2020-12-30 12:40:15'),(120,17,1,'192.168.1.2','2020-12-30 12:40:15'),(121,13,2,'192.168.1.252','2020-12-30 12:40:15'),(122,12,2,'192.168.1.251','2020-12-30 12:40:15'),(123,15,2,'192.168.1.244','2020-12-30 12:40:15'),(124,14,2,'192.168.1.250','2020-12-30 12:40:15'),(125,16,1,'192.168.1.254','2020-12-30 12:40:16'),(126,1,2,'192.168.1.57','2020-12-30 19:46:14'),(127,2,3,'192.168.1.58','2020-12-30 19:46:14'),(128,3,2,'192.168.1.71','2020-12-30 19:46:14'),(129,4,2,'192.168.1.43','2020-12-30 19:46:14'),(130,5,2,'192.168.1.15','2020-12-30 19:46:15'),(131,8,1,'192.168.1.54','2020-12-30 19:46:15'),(132,7,1,'192.168.1.53','2020-12-30 19:46:15'),(133,6,1,'192.168.1.51','2020-12-30 19:46:15'),(134,10,2,'192.168.1.55','2020-12-30 19:46:15'),(135,19,1,'192.168.1.3','2020-12-30 19:46:15'),(136,9,1,'192.168.1.1','2020-12-30 19:46:15'),(137,17,1,'192.168.1.2','2020-12-30 19:46:15'),(138,12,2,'192.168.1.251','2020-12-30 19:46:15'),(139,13,2,'192.168.1.252','2020-12-30 19:46:16'),(140,16,1,'192.168.1.254','2020-12-30 19:46:16'),(141,14,2,'192.168.1.250','2020-12-30 19:46:16'),(142,15,2,'192.168.1.244','2020-12-30 19:46:16'),(143,3,2,'192.168.1.71','2020-12-30 19:51:42'),(144,2,3,'192.168.1.58','2020-12-30 19:51:42'),(145,1,2,'192.168.1.57','2020-12-30 19:51:43'),(146,4,2,'192.168.1.43','2020-12-30 19:51:43'),(147,5,2,'192.168.1.15','2020-12-30 19:51:43'),(148,7,1,'192.168.1.53','2020-12-30 19:51:43'),(149,8,1,'192.168.1.54','2020-12-30 19:51:43'),(150,6,1,'192.168.1.51','2020-12-30 19:51:43'),(151,9,1,'192.168.1.1','2020-12-30 19:51:44'),(152,10,2,'192.168.1.55','2020-12-30 19:51:44'),(153,19,1,'192.168.1.3','2020-12-30 19:51:44'),(154,21,1,'192.168.1.4','2020-12-30 19:51:44'),(155,12,2,'192.168.1.251','2020-12-30 19:51:44'),(156,13,2,'192.168.1.252','2020-12-30 19:51:44'),(157,14,2,'192.168.1.250','2020-12-30 19:51:44'),(158,15,2,'192.168.1.244','2020-12-30 19:51:44'),(159,16,1,'192.168.1.254','2020-12-30 19:51:44'),(160,2,3,'192.168.1.58','2021-01-05 13:57:28'),(161,1,2,'192.168.1.57','2021-01-05 13:57:28'),(162,3,2,'192.168.1.71','2021-01-05 13:57:28'),(163,4,2,'192.168.1.43','2021-01-05 13:57:28'),(164,5,2,'192.168.1.15','2021-01-05 13:57:29'),(165,7,1,'192.168.1.53','2021-01-05 13:57:29'),(166,6,1,'192.168.1.51','2021-01-05 13:57:29'),(167,22,3,'192.168.1.59','2021-01-05 13:57:29'),(168,8,1,'192.168.1.54','2021-01-05 13:57:29'),(169,19,1,'192.168.1.6','2021-01-05 13:57:29'),(170,9,1,'192.168.1.1','2021-01-05 13:57:29'),(171,10,2,'192.168.1.55','2021-01-05 13:57:29'),(172,21,1,'192.168.1.4','2021-01-05 13:57:29'),(173,12,2,'192.168.1.251','2021-01-05 13:57:30'),(174,13,2,'192.168.1.252','2021-01-05 13:57:30'),(175,16,1,'192.168.1.254','2021-01-05 13:57:30'),(176,14,2,'192.168.1.250','2021-01-05 13:57:30'),(177,15,2,'192.168.1.244','2021-01-05 13:57:30'),(178,2,3,'192.168.1.58','2021-01-05 15:58:34'),(179,3,2,'192.168.1.71','2021-01-05 15:58:34'),(180,1,2,'192.168.1.57','2021-01-05 15:58:34'),(181,4,2,'192.168.1.43','2021-01-05 15:58:34'),(182,5,2,'192.168.1.15','2021-01-05 15:58:35'),(183,7,1,'192.168.1.53','2021-01-05 15:58:35'),(184,22,3,'192.168.1.59','2021-01-05 15:58:35'),(185,6,1,'192.168.1.51','2021-01-05 15:58:35'),(186,8,1,'192.168.1.54','2021-01-05 15:58:35'),(187,18,1,'192.168.1.7','2021-01-05 15:58:35'),(188,9,1,'192.168.1.1','2021-01-05 15:58:35'),(189,21,1,'192.168.1.4','2021-01-05 15:58:35'),(190,19,1,'192.168.1.6','2021-01-05 15:58:35'),(191,17,1,'192.168.1.2','2021-01-05 15:58:35'),(192,10,2,'192.168.1.55','2021-01-05 15:58:36'),(193,12,2,'192.168.1.251','2021-01-05 15:58:36'),(194,13,2,'192.168.1.252','2021-01-05 15:58:36'),(195,16,1,'192.168.1.254','2021-01-05 15:58:36'),(196,15,2,'192.168.1.244','2021-01-05 15:58:36'),(197,14,2,'192.168.1.250','2021-01-05 15:58:36'),(198,2,3,'192.168.1.58','2021-01-05 18:57:57'),(199,1,2,'192.168.1.57','2021-01-05 18:57:57'),(200,3,2,'192.168.1.71','2021-01-05 18:57:57'),(201,4,2,'192.168.1.43','2021-01-05 18:57:57'),(202,5,2,'192.168.1.15','2021-01-05 18:57:57'),(203,7,1,'192.168.1.53','2021-01-05 18:57:57'),(204,22,3,'192.168.1.59','2021-01-05 18:57:58'),(205,8,1,'192.168.1.54','2021-01-05 18:57:58'),(206,6,1,'192.168.1.51','2021-01-05 18:57:58'),(207,10,2,'192.168.1.55','2021-01-05 18:57:58'),(208,21,1,'192.168.1.4','2021-01-05 18:57:58'),(209,9,1,'192.168.1.1','2021-01-05 18:57:58'),(210,19,1,'192.168.1.6','2021-01-05 18:57:58'),(211,23,1,'192.168.1.5','2021-01-05 18:57:58'),(212,17,1,'192.168.1.2','2021-01-05 18:57:58'),(213,13,2,'192.168.1.252','2021-01-05 18:57:58'),(214,12,2,'192.168.1.251','2021-01-05 18:57:58'),(215,14,2,'192.168.1.250','2021-01-05 18:57:58'),(216,16,1,'192.168.1.254','2021-01-05 18:57:58'),(217,15,2,'192.168.1.244','2021-01-05 18:57:58'),(218,9,2,'192.168.1.1','2021-01-06 13:36:05'),(219,19,1,'192.168.1.3','2021-01-06 13:36:05'),(220,21,2,'192.168.1.4','2021-01-06 13:36:05'),(221,11,2,'192.168.1.6','2021-01-06 13:36:06'),(222,4,4,'192.168.1.43','2021-01-06 13:36:06'),(223,6,4,'192.168.1.51','2021-01-06 13:36:06'),(224,7,3,'192.168.1.53','2021-01-06 13:36:06'),(225,8,4,'192.168.1.54','2021-01-06 13:36:06'),(226,10,2,'192.168.1.55','2021-01-06 13:36:06'),(227,1,4,'192.168.1.57','2021-01-06 13:36:06'),(228,2,2,'192.168.1.58','2021-01-06 13:36:07'),(229,22,2,'192.168.1.59','2021-01-06 13:36:07'),(230,3,2,'192.168.1.71','2021-01-06 13:36:07'),(231,15,2,'192.168.1.244','2021-01-06 13:36:07'),(232,14,4,'192.168.1.250','2021-01-06 13:36:07'),(233,12,2,'192.168.1.251','2021-01-06 13:36:07'),(234,13,4,'192.168.1.252','2021-01-06 13:36:07'),(235,16,2,'192.168.1.254','2021-01-06 13:36:08'),(236,5,4,'192.168.1.15','2021-01-06 13:36:08'),(237,9,3,'192.168.1.1','2021-01-06 13:36:57'),(238,17,1,'192.168.1.2','2021-01-06 13:36:58'),(239,19,2,'192.168.1.3','2021-01-06 13:36:58'),(240,21,1,'192.168.1.4','2021-01-06 13:36:58'),(241,11,2,'192.168.1.6','2021-01-06 13:36:58'),(242,4,2,'192.168.1.43','2021-01-06 13:36:58'),(243,6,3,'192.168.1.51','2021-01-06 13:36:58'),(244,7,2,'192.168.1.53','2021-01-06 13:36:59'),(245,8,3,'192.168.1.54','2021-01-06 13:36:59'),(246,10,2,'192.168.1.55','2021-01-06 13:36:59'),(247,1,1,'192.168.1.57','2021-01-06 13:36:59'),(248,2,2,'192.168.1.58','2021-01-06 13:36:59'),(249,22,1,'192.168.1.59','2021-01-06 13:36:59'),(250,3,3,'192.168.1.71','2021-01-06 13:37:00'),(251,15,1,'192.168.1.244','2021-01-06 13:37:00'),(252,14,4,'192.168.1.250','2021-01-06 13:37:00'),(253,12,1,'192.168.1.251','2021-01-06 13:37:00'),(254,13,1,'192.168.1.252','2021-01-06 13:37:00'),(255,16,1,'192.168.1.254','2021-01-06 13:37:00'),(256,5,1,'192.168.1.15','2021-01-06 13:37:00'),(257,9,4,'192.168.1.1','2021-01-06 14:32:55'),(258,19,1,'192.168.1.3','2021-01-06 14:32:55'),(259,18,3,'192.168.1.7','2021-01-06 14:32:56'),(260,4,4,'192.168.1.43','2021-01-06 14:32:56'),(261,6,3,'192.168.1.51','2021-01-06 14:32:56'),(262,7,4,'192.168.1.53','2021-01-06 14:32:56'),(263,8,3,'192.168.1.54','2021-01-06 14:32:56'),(264,10,2,'192.168.1.55','2021-01-06 14:32:56'),(265,1,1,'192.168.1.57','2021-01-06 14:32:56'),(266,2,1,'192.168.1.58','2021-01-06 14:32:57'),(267,22,1,'192.168.1.59','2021-01-06 14:32:57'),(268,3,1,'192.168.1.71','2021-01-06 14:32:57'),(269,15,2,'192.168.1.244','2021-01-06 14:32:57'),(270,14,2,'192.168.1.250','2021-01-06 14:32:57'),(271,12,3,'192.168.1.251','2021-01-06 14:32:57'),(272,13,3,'192.168.1.252','2021-01-06 14:32:57'),(273,16,2,'192.168.1.254','2021-01-06 14:32:58'),(274,5,1,'192.168.1.15','2021-01-06 14:32:58'),(275,9,4,'192.168.1.1','2021-01-06 14:47:37'),(276,17,4,'192.168.1.2','2021-01-06 14:47:37'),(277,19,2,'192.168.1.3','2021-01-06 14:47:37'),(278,21,4,'192.168.1.4','2021-01-06 14:47:37'),(279,4,3,'192.168.1.43','2021-01-06 14:47:37'),(280,6,1,'192.168.1.51','2021-01-06 14:47:38'),(281,7,4,'192.168.1.53','2021-01-06 14:47:38'),(282,8,2,'192.168.1.54','2021-01-06 14:47:38'),(283,10,3,'192.168.1.55','2021-01-06 14:47:38'),(284,1,2,'192.168.1.57','2021-01-06 14:47:38'),(285,2,4,'192.168.1.58','2021-01-06 14:47:38'),(286,22,2,'192.168.1.59','2021-01-06 14:47:38'),(287,3,3,'192.168.1.71','2021-01-06 14:47:39'),(288,15,1,'192.168.1.244','2021-01-06 14:47:39'),(289,14,1,'192.168.1.250','2021-01-06 14:47:39'),(290,12,1,'192.168.1.251','2021-01-06 14:47:39'),(291,13,4,'192.168.1.252','2021-01-06 14:47:39'),(292,16,4,'192.168.1.254','2021-01-06 14:47:39'),(293,5,2,'192.168.1.15','2021-01-06 14:47:39'),(294,9,4,'192.168.1.1','2021-01-06 15:18:33'),(295,19,4,'192.168.1.3','2021-01-06 15:18:33'),(296,4,4,'192.168.1.43','2021-01-06 15:18:33'),(297,6,3,'192.168.1.51','2021-01-06 15:18:33'),(298,7,2,'192.168.1.53','2021-01-06 15:18:33'),(299,8,3,'192.168.1.54','2021-01-06 15:18:33'),(300,10,3,'192.168.1.55','2021-01-06 15:18:34'),(301,1,3,'192.168.1.57','2021-01-06 15:18:34'),(302,2,2,'192.168.1.58','2021-01-06 15:18:34'),(303,22,2,'192.168.1.59','2021-01-06 15:18:34'),(304,3,2,'192.168.1.71','2021-01-06 15:18:34'),(305,15,3,'192.168.1.244','2021-01-06 15:18:34'),(306,14,4,'192.168.1.250','2021-01-06 15:18:34'),(307,12,1,'192.168.1.251','2021-01-06 15:18:35'),(308,13,2,'192.168.1.252','2021-01-06 15:18:35'),(309,16,1,'192.168.1.254','2021-01-06 15:18:35'),(310,5,3,'192.168.1.15','2021-01-06 15:18:35'),(311,9,3,'192.168.1.1','2021-01-06 15:49:48'),(312,17,4,'192.168.1.2','2021-01-06 15:49:48'),(313,19,2,'192.168.1.3','2021-01-06 15:49:49'),(314,4,2,'192.168.1.43','2021-01-06 15:49:49'),(315,6,4,'192.168.1.51','2021-01-06 15:49:49'),(316,7,1,'192.168.1.53','2021-01-06 15:49:49'),(317,8,4,'192.168.1.54','2021-01-06 15:49:49'),(318,10,2,'192.168.1.55','2021-01-06 15:49:49'),(319,1,1,'192.168.1.57','2021-01-06 15:49:49'),(320,2,3,'192.168.1.58','2021-01-06 15:49:49'),(321,22,4,'192.168.1.59','2021-01-06 15:49:50'),(322,3,2,'192.168.1.71','2021-01-06 15:49:50'),(323,15,2,'192.168.1.244','2021-01-06 15:49:50'),(324,14,1,'192.168.1.250','2021-01-06 15:49:50'),(325,12,4,'192.168.1.251','2021-01-06 15:49:50'),(326,13,1,'192.168.1.252','2021-01-06 15:49:50'),(327,16,2,'192.168.1.254','2021-01-06 15:49:50'),(328,5,3,'192.168.1.15','2021-01-06 15:49:51');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_models` (
  `id` int NOT NULL AUTO_INCREMENT,
  `manufacturer` varchar(100) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  KEY `fk_mod_devtype_idx` (`type_id`),
  CONSTRAINT `fk_mod_devtype` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `device_models` VALUES (1,NULL,NULL,1),(2,NULL,NULL,2),(3,'Samsung','Galaxy Note 9',2),(4,'Apple','iPhone',2),(5,NULL,NULL,3),(6,NULL,NULL,4),(7,'Apple','Macintosh',1),(8,'Samsung',NULL,5);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_types` (
  `id` int NOT NULL AUTO_INCREMENT,
  `identifier` varchar(45) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `subtype` varchar(45) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `icon_color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `key_UNIQUE` (`identifier`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `device_types` VALUES (1,'desktop','Desktop','','fas fa-desktop','#842029'),(2,'mobile','Mobile','smart','fas fa-mobile-alt','#0f5132'),(3,'','Unknown','',NULL,NULL),(4,'bot','Server','','fas fa-robot','#BF104B'),(5,'television','Television','','fas fa-tv','#636464');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `devices` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mac_addr` varchar(17) NOT NULL,
  `hostname` varchar(255) DEFAULT NULL,
  `model_id` int DEFAULT NULL,
  `os_id` int DEFAULT NULL,
  `type_id` int DEFAULT NULL,
  `user_id` int DEFAULT NULL,
  `ignored` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `mac_addr_UNIQUE` (`mac_addr`),
  KEY `fk_os_idx` (`os_id`),
  KEY `fk_user_idx` (`user_id`),
  KEY `fk_type_idx` (`model_id`),
  KEY `fk_dev_type_idx` (`type_id`),
  CONSTRAINT `fk_dev_type` FOREIGN KEY (`type_id`) REFERENCES `device_types` (`id`),
  CONSTRAINT `fk_model` FOREIGN KEY (`model_id`) REFERENCES `device_models` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_os` FOREIGN KEY (`os_id`) REFERENCES `operating_systems` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `devices` VALUES (1,'EA:A6:EE:DE:E2:FF','192.168.1.57',1,5,1,NULL,0),(2,'4E:FA:BB:56:95:30','192.168.1.58',1,1,1,NULL,0),(3,'00:1B:A9:4B:DE:70','192.168.1.71',NULL,NULL,NULL,NULL,1),(4,'F4:A9:97:4D:DF:C4','192.168.1.43',NULL,NULL,NULL,NULL,1),(5,'00:90:F5:E3:99:27','Blueberry.home',1,1,1,NULL,0),(6,'DC:A6:32:37:AF:F1','RASPBERRY',6,NULL,4,NULL,0),(7,'B8:27:EB:C7:C9:DE','BARBERRY',6,NULL,4,NULL,0),(8,'9C:8E:99:DF:5D:EB','ELDERBERRY',NULL,NULL,NULL,NULL,0),(9,'1C:B0:44:92:A1:20','routertecnico.home',NULL,NULL,NULL,NULL,1),(10,'2C:44:FD:33:79:6D','192.168.1.55',6,NULL,4,NULL,0),(11,'74:40:BE:37:D0:86','192.168.1.6',NULL,NULL,NULL,NULL,0),(12,'04:D4:C4:84:93:40','192.168.1.251',NULL,NULL,NULL,NULL,1),(13,'04:D4:C4:87:79:E8','192.168.1.252',NULL,NULL,NULL,NULL,1),(14,'40:9B:CD:43:B9:C8','192.168.1.250',NULL,NULL,NULL,NULL,1),(15,'B8:27:EB:B9:88:B6','raspberrypi.home',6,NULL,4,NULL,0),(16,'DC:F5:05:91:1A:8A','192.168.1.254',NULL,NULL,NULL,NULL,1),(17,'44:91:60:C6:2F:A0','192.168.1.2',2,2,2,NULL,0),(18,'14:C2:13:0F:02:F2','192.168.1.7',7,6,1,NULL,0),(19,'00:C3:F4:08:CB:F0','192.168.1.3',8,8,5,NULL,0),(20,'D6:C3:12:21:66:9C','192.168.1.4',NULL,NULL,NULL,NULL,NULL),(21,'F6:CA:F4:36:71:A3','Luizas-iPhone.home',4,3,2,NULL,0),(22,'00:13:21:05:B8:5B','SALMONBERRY',1,4,1,NULL,0),(23,'08:00:27:6D:76:3F','192.168.1.5',1,7,1,NULL,0);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `floors` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number` int unsigned NOT NULL,
  `name` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `floors` VALUES (1,0,'R/C'),(2,1,'1st Floor'),(3,2,'2nd Floor'),(4,3,'3rd Floor');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operating_systems` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(45) NOT NULL,
  `version` varchar(45) DEFAULT NULL,
  `family` varchar(45) DEFAULT NULL,
  `icon` varchar(45) DEFAULT NULL,
  `icon_color` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `operating_systems` VALUES (1,'Windows','10',NULL,'fab fa-windows','#197AD3'),(2,'Android',NULL,NULL,'fab fa-android','#4ADE89'),(3,'iOS',NULL,NULL,'fab fa-apple','#262626'),(4,'Windows','Server 2003',NULL,'fab fa-windows','#364850'),(5,'Windows','7',NULL,'fab fa-windows','#1562C2'),(6,'OS X',NULL,NULL,'fab fa-apple','#262626'),(7,'Ubuntu',NULL,NULL,'fab fa-ubuntu','#D33129'),(8,'Tizen',NULL,NULL,'fas fa-times','#636464');
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `request_headers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `json` json NOT NULL,
  `device_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_id_UNIQUE` (`device_id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  CONSTRAINT `fk_req_device` FOREIGN KEY (`device_id`) REFERENCES `devices` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `request_headers` VALUES (1,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7\", \"Upgrade-Insecure-Requests\": \"1\"}',5),(2,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\", \"Referer\": \"http://192.168.1.15/\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Android 10; Mobile; rv:85.0) Gecko/85.0 Firefox/85.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US\", \"Upgrade-Insecure-Requests\": \"1\"}',17),(3,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\", \"Referer\": \"http://192.168.1.15/?ts=12\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (iPhone; CPU iPhone OS 14_2 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.0.1 Mobile/15E148 Safari/604.1\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"pt-pt\", \"Upgrade-Insecure-Requests\": \"1\"}',21),(4,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\", \"Referer\": \"http://192.168.1.15/\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (SMART-TV; Linux; Tizen 4.0) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/56.0.2924.0 TV Safari/537.36\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"pt-PT\", \"Upgrade-Insecure-Requests\": \"1\"}',19),(5,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Windows NT 5.2; rv:52.0) Gecko/20100101 Firefox/52.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US,en;q=0.5\", \"Upgrade-Insecure-Requests\": \"1\"}',22),(6,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:78.0) Gecko/20100101 Firefox/78.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US,en;q=0.5\", \"Upgrade-Insecure-Requests\": \"1\"}',1),(7,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\", \"Referer\": \"http://192.168.1.15/\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:83.0) Gecko/20100101 Firefox/83.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US,en;q=0.5\", \"Upgrade-Insecure-Requests\": \"1\"}',2),(9,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\", \"Referer\": \"http://192.168.1.15/debugview.php\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (Macintosh; Intel Mac OS X 11_1_0) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/87.0.4280.88 Safari/537.36\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"pt-BR,pt;q=0.9,en-US;q=0.8,en;q=0.7\", \"Upgrade-Insecure-Requests\": \"1\"}',18),(11,'{\"Host\": \"192.168.1.15\", \"Accept\": \"*/*\", \"User-Agent\": \"curl/7.64.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\"}',6),(12,'{\"Host\": \"192.168.1.15\", \"Accept\": \"text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8\", \"Referer\": \"http://192.168.1.15/\", \"Connection\": \"keep-alive\", \"User-Agent\": \"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:84.0) Gecko/20100101 Firefox/84.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\", \"Accept-Encoding\": \"gzip, deflate\", \"Accept-Language\": \"en-US,en;q=0.5\", \"Upgrade-Insecure-Requests\": \"1\"}',23),(13,'{\"Host\": \"192.168.1.15\", \"Accept\": \"*/*\", \"User-Agent\": \"curl/7.64.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\"}',7),(14,'{\"Host\": \"192.168.1.15\", \"Accept\": \"*/*\", \"User-Agent\": \"curl/7.29.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\"}',10),(15,'{\"Host\": \"192.168.1.15\", \"Accept\": \"*/*\", \"User-Agent\": \"curl/7.64.0\", \"Content-Type\": \"\", \"Content-Length\": \"0\"}',15);
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `rooms` (
  `id` int NOT NULL AUTO_INCREMENT,
  `number` int NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `floor_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id_UNIQUE` (`id`),
  UNIQUE KEY `number_UNIQUE` (`number`),
  KEY `id_idx` (`floor_id`),
  CONSTRAINT `fk_floor` FOREIGN KEY (`floor_id`) REFERENCES `floors` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;
INSERT INTO `rooms` VALUES (1,11,'Fabrica',2),(2,12,NULL,2),(3,13,NULL,2),(4,21,'Game Lab',3),(5,22,'Fab Lab',3),(6,31,NULL,4),(7,32,NULL,4),(8,33,NULL,4),(9,34,NULL,4);