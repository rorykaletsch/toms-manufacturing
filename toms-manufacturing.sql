-- MySQL dump 10.13  Distrib 8.4.0, for Win64 (x86_64)
--
-- Host: localhost    Database: toms-hardware
-- ------------------------------------------------------
-- Server version	5.5.5-10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `orderitems`
--

DROP TABLE IF EXISTS `orderitems`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orderitems` (
  `order_item_id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`order_item_id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `orderitems_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  CONSTRAINT `orderitems_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orderitems`
--

LOCK TABLES `orderitems` WRITE;
/*!40000 ALTER TABLE `orderitems` DISABLE KEYS */;
INSERT INTO `orderitems` VALUES (1,1,1,1,1455.00),(2,7,9,1,996.89),(3,7,1,1,1455.00),(4,7,2,1,296.24),(5,7,17,1,1998.88),(6,8,2,2,296.24),(7,8,9,2,996.89),(8,8,7,1,2657.28),(9,9,3,2,978.45),(10,9,11,2,4567.89),(11,10,1,1,1455.00),(12,10,6,2,8846.26),(13,10,10,1,13289.99),(14,10,8,15,1288.99),(15,10,5,4,29258.88),(16,10,9,1,996.89),(17,11,2,1,296.24);
/*!40000 ALTER TABLE `orderitems` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_address` text DEFAULT NULL,
  `billing_address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`order_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,1,'2024-06-07 13:26:32','Pending',2673.25,NULL,NULL,'2024-06-07 13:26:32','2024-06-07 13:26:32'),(2,1,'2024-06-07 13:29:22','Pending',1000.00,NULL,NULL,'2024-06-07 13:29:22','2024-06-07 13:29:22'),(3,1,'2024-06-07 13:34:13','Pending',1000.00,NULL,NULL,'2024-06-07 13:34:13','2024-06-07 13:34:13'),(4,1,'2024-06-07 13:38:50','Pending',1000.00,NULL,NULL,'2024-06-07 13:38:50','2024-06-07 13:38:50'),(5,1,'2024-06-07 13:39:30','Pending',1000.00,NULL,NULL,'2024-06-07 13:39:30','2024-06-07 13:39:30'),(6,1,'2024-06-07 13:44:01','Pending',1000.00,NULL,NULL,'2024-06-07 13:44:01','2024-06-07 13:44:01'),(7,1,'2024-06-07 13:50:00','Pending',6459.06,NULL,NULL,'2024-06-07 13:50:00','2024-06-07 13:50:00'),(8,1,'2024-06-07 14:13:15','Pending',7030.07,NULL,NULL,'2024-06-07 14:13:15','2024-06-07 14:13:15'),(9,1,'2024-06-07 14:13:45','Pending',13756.58,NULL,NULL,'2024-06-07 14:13:45','2024-06-07 14:13:45'),(10,4,'2024-06-07 19:26:55','Pending',196275.49,NULL,NULL,'2024-06-07 19:26:55','2024-06-07 19:26:55'),(11,1,'2024-06-07 20:42:25','Pending',1340.68,NULL,NULL,'2024-06-07 20:42:25','2024-06-07 20:42:25');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `products` (
  `product_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `products`
--

LOCK TABLES `products` WRITE;
/*!40000 ALTER TABLE `products` DISABLE KEYS */;
INSERT INTO `products` VALUES (1,'Hydraulic Pump','High-performance hydraulic pump for heavy machinery',1455.00,23,'Hydraulics','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/hydraulic_pump.png'),(2,'Excavator Bucket Teeth','Durable bucket teeth for excavators',296.24,1241,'Excavator Parts','2024-06-01 08:54:15','2024-06-07 20:42:25','/dealer-portal/assets/images/excavator_bucket_teeth.png'),(3,'Engine Filter','Engine air filter for various heavy machinery',978.45,98,'Engine Parts','2024-06-01 08:54:15','2024-06-07 14:13:45','/dealer-portal/assets/images/engine_filter.png'),(4,'Track Roller','Track roller for bulldozers and excavators',21462.26,20,'Undercarriage Parts','2024-06-01 08:54:15','2024-06-02 13:40:28','/dealer-portal/assets/images/track_roller.png'),(5,'Hydraulic Cylinder','Hydraulic cylinder for excavators and loaders',29258.88,12,'Hydraulics','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/hydraulic_cylinder.png'),(6,'Fuel Injector','High-efficiency fuel injector for diesel engines',8846.26,38,'Engine Parts','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/fuel_injector.png'),(7,'Starter Motor','Heavy-duty starter motor for construction machinery',2657.28,24,'Electrical Parts','2024-06-01 08:54:15','2024-06-07 14:13:15','/dealer-portal/assets/images/starter_motor.png'),(8,'Bucket Pin','Heavy-duty bucket pin for excavators and loaders',1288.99,45,'Excavator Parts','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/bucket_pin.png'),(9,'Control Valve','Hydraulic control valve for various heavy equipment',996.89,8,'Hydraulics','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/control_valve.png'),(10,'Turbocharger','Turbocharger for heavy-duty diesel engines',13289.99,7,'Engine Parts','2024-06-01 08:54:15','2024-06-07 19:26:55','/dealer-portal/assets/images/turbocharger.png'),(11,'Transmission Gear','Heavy-duty transmission gear for loaders',4567.89,28,'Transmission Parts','2024-06-06 20:51:03','2024-06-07 14:13:45','/dealer-portal/assets/images/transmission_gear.png'),(12,'Hydraulic Hose','Reinforced hydraulic hose for high-pressure systems',845.99,150,'Hydraulics','2024-06-06 22:32:15','2024-06-06 22:32:15','/dealer-portal/assets/images/hydraulic_hose.png'),(13,'Cooling Radiator','Efficient cooling radiator for heavy machinery',6798.56,20,'Cooling Systems','2024-06-06 22:42:10','2024-06-06 22:42:10','/dealer-portal/assets/images/cooling_radiator.png'),(14,'Brake Disc','Durable brake disc for heavy-duty vehicles',2234.50,40,'Brake Parts','2024-06-06 22:45:02','2024-06-06 22:45:02','/dealer-portal/assets/images/brake_disc.png'),(15,'Exhaust Manifold','High-performance exhaust manifold',3298.45,18,'Engine Parts','2024-06-06 22:46:56','2024-06-06 22:46:56','/dealer-portal/assets/images/engine_manifold.png'),(16,'Drive Shaft','Heavy-duty drive shaft for machinery',5476.99,22,'Transmission Parts','2024-06-06 22:49:20','2024-06-06 22:49:20','/dealer-portal/assets/images/drive_shaft.png'),(17,'Alternator','High-capacity alternator for construction vehicles',1998.88,34,'Electrical Parts','2024-06-06 22:51:11','2024-06-07 13:50:00','/dealer-portal/assets/images/alternator.png'),(19,'Air Compressor','Industrial-grade air compressor',789.26,8,'Pneumatics','2024-06-06 22:57:23','2024-06-06 22:57:23','/dealer-portal/assets/images/air_compressor.png'),(20,'Spark Plug','High-performance spark plug for engines',156.89,500,'Engine Parts','2024-06-06 22:59:12','2024-06-06 22:59:12','/dealer-portal/assets/images/spark_plug.png');
/*!40000 ALTER TABLE `products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_admin` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin','admin@admin.com','$2y$10$YvBqMEUL4JQvb2BIS/TJjO2EZGU4lnQIetQCTQKcHXRSLdpE6jaiy','Admin','Adminer','1123 Admin Place. Adminville, Admination.','0828720035','2024-06-05 19:41:19','2024-06-07 14:23:07',1),(3,'rorykaletsch','rorykaletsch@gmail.com','$2y$10$EwImFgt3/nlUDjeBWi1gvebrESNPj5xS.IkIGi16Gs71NdJnlUcoK','Rory','Kaletsch','25 Buldomba Rd, Klarkle, South Africa','0828720035','2024-06-07 19:21:34','2024-06-07 19:21:34',0),(4,'madipostma@icloud.com','madipostma@icloud.com','$2y$10$pVul8zwmWHcL/Lcg0SVJ3.TGyLyyxNvSZzAd22APRvaXstmf6QE/W','Madi','Postma','11 Stockville Rd, Clifton Park, 3610','0828720035','2024-06-07 19:25:03','2024-06-07 19:25:03',0);
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

-- Dump completed on 2024-06-07 23:03:30
