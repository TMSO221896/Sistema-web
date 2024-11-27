-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: claus
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `bitacora_pasteles`
--

DROP TABLE IF EXISTS `bitacora_pasteles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora_pasteles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'ID de la tabla bitacora pasteles',
  `nombreP` varchar(30) NOT NULL COMMENT 'Almacena el nombre del pastel',
  `fecCompra` date NOT NULL COMMENT 'Almacena la fecha de compra del pastel',
  `Precio` double NOT NULL COMMENT 'Almacena el el precio del pastel',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_pasteles`
--

LOCK TABLES `bitacora_pasteles` WRITE;
/*!40000 ALTER TABLE `bitacora_pasteles` DISABLE KEYS */;
INSERT INTO `bitacora_pasteles` VALUES (5,'Pastel de Vainilla','2024-11-19',100),(6,'Gelatina','2024-11-19',50),(7,'Gelatina','2024-11-19',50),(8,'Gelatina','2024-11-19',50),(9,'Pastel de Vainilla','2024-11-21',100),(10,'Pastel de Fresa','2024-11-21',130),(11,'Cupcake','2024-11-23',400),(12,'Gelatina','2024-11-24',13),(13,'Pastel de Fresa','2024-11-24',130),(14,'Pastel de Vainilla','2024-11-24',100);
/*!40000 ALTER TABLE `bitacora_pasteles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora_pedidos`
--

DROP TABLE IF EXISTS `bitacora_pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora_pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pedido_id` int(11) NOT NULL COMMENT 'Almacena el id del pedido',
  `accion` varchar(50) NOT NULL COMMENT 'Almacena si el pedido fue entregado o cancelado',
  `total` double NOT NULL COMMENT 'almacena el total del pedido',
  `fecha` date NOT NULL COMMENT 'Almacena la fecha en que fue creado el pedido',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_pedidos`
--

LOCK TABLES `bitacora_pedidos` WRITE;
/*!40000 ALTER TABLE `bitacora_pedidos` DISABLE KEYS */;
INSERT INTO `bitacora_pedidos` VALUES (7,34,'Entregado',150,'2024-11-19'),(8,34,'Entregado',150,'2024-11-19'),(9,35,'Entregado',100,'2024-11-19'),(10,36,'Entregado',100,'2024-11-21'),(11,37,'Cancelado',140,'2024-11-21'),(12,38,'Entregado',130,'2024-11-21'),(13,41,'Entregado',40,'2024-11-23'),(14,47,'Cancelado',50,'2024-11-24'),(15,49,'Entregado',13,'2024-11-24'),(16,50,'Entregado',130,'2024-11-24'),(17,51,'Cancelado',100,'2024-11-24'),(18,53,'Cancelado',130,'2024-11-24'),(19,54,'Entregado',100,'2024-11-24');
/*!40000 ALTER TABLE `bitacora_pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bitacora_usuarios`
--

DROP TABLE IF EXISTS `bitacora_usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bitacora_usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` varchar(30) NOT NULL COMMENT 'Almacena el id del Usuario',
  `accion` varchar(20) NOT NULL COMMENT 'Almacena la accion realizada por el usuario',
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bitacora_usuarios`
--

LOCK TABLES `bitacora_usuarios` WRITE;
/*!40000 ALTER TABLE `bitacora_usuarios` DISABLE KEYS */;
INSERT INTO `bitacora_usuarios` VALUES (1,'Gerente2','Creado','2024-11-02 05:02:37'),(2,'Gerente2','Eliminado','2024-11-02 05:02:51'),(3,'santiT','Creado','2024-11-03 15:29:23'),(4,'santiT','Eliminado','2024-11-06 03:30:06'),(5,'santiT','Creado','2024-11-06 03:33:16'),(6,'santiT','Eliminado','2024-11-06 03:34:10'),(7,'santiT','Creado','2024-11-06 03:39:46'),(8,'santiT','Eliminado','2024-11-06 03:40:44'),(9,'santiT','Creado','2024-11-06 04:15:24'),(10,'santi','Eliminado','2024-11-06 04:15:57'),(11,'Gerente2','Creado','2024-11-06 04:16:15'),(12,'Gerente2','Eliminado','2024-11-06 04:16:56'),(13,'Gerente2','Creado','2024-11-06 04:19:27'),(14,'santiT','Creado','2024-11-06 04:21:59'),(15,'santi','Eliminado','2024-11-06 04:22:38'),(16,'Gerente2','Eliminado','2024-11-06 04:23:20'),(17,'Gerente2','Creado','2024-11-06 04:23:25'),(18,'santiT','Creado','2024-11-06 04:24:47'),(19,'santiT','Eliminado','2024-11-06 23:05:20'),(20,'santiT','Creado','2024-11-06 23:05:49'),(21,'pepitoT','Creado','2024-11-07 20:12:24'),(22,'santiT','Eliminado','2024-11-08 15:19:38'),(23,'pepitoT','Eliminado','2024-11-08 15:22:58'),(24,'santiT','Creado','2024-11-08 18:13:46'),(25,'cinthia123','Creado','2024-11-08 23:53:31'),(26,'Patata','Creado','2024-11-13 04:21:58'),(27,'JoseJose','Creado','2024-11-13 19:11:08'),(28,'Gerente5','Creado','2024-11-13 19:13:23'),(29,'Joselito','Creado','2024-11-13 19:35:40'),(30,'KeniaLizet','Creado','2024-11-16 00:20:48'),(31,'PepeG','Creado','2024-11-22 06:00:00'),(32,'PepeG','Creado','2024-11-22 06:00:00'),(33,'PepeG','Creado','2024-11-22 06:00:00'),(34,'PepeG','Creado','2024-11-22 06:00:00'),(35,'PepeG','Creado','2024-11-22 06:00:00'),(36,'PepeG','Creado','2024-11-22 06:00:00'),(37,'PepeG','Creado','2024-11-22 06:00:00'),(38,'0','Creado','2024-11-22 06:00:00'),(39,'0','Creado','2024-11-22 06:00:00'),(40,'0','Creado','2024-11-22 06:00:00'),(41,'0','Creado','2024-11-23 06:00:00'),(42,'0','Creado','2024-11-23 06:00:00'),(43,'','Eliminado','2024-11-23 06:00:00'),(44,'0','Creado','2024-11-23 06:00:00'),(45,'0','Creado','2024-11-23 06:00:00'),(46,'0','Creado','2024-11-24 06:00:00'),(47,'0','Creado','2024-11-25 06:00:00'),(48,'0','Creado','2024-11-25 06:00:00');
/*!40000 ALTER TABLE `bitacora_usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `comentarios`
--

DROP TABLE IF EXISTS `comentarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `comentarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` varchar(10) NOT NULL COMMENT 'Guarda el numero identificador del usuario que hizo el comentario',
  `comentario` text NOT NULL COMMENT 'Guarda el comentario realizado por el usuario',
  `satisfaccion` int(11) NOT NULL COMMENT 'Guarda la satisfacci?n del usuario con el servicio (1-5)',
  PRIMARY KEY (`id`),
  KEY `idUsuario` (`idUsuario`),
  CONSTRAINT `comentarios_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `comentarios`
--

LOCK TABLES `comentarios` WRITE;
/*!40000 ALTER TABLE `comentarios` DISABLE KEYS */;
INSERT INTO `comentarios` VALUES (1,'santiT','Muy Buena Pagina',5),(40,'santiT','MUY BUENO',2);
/*!40000 ALTER TABLE `comentarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `direcciones`
--

DROP TABLE IF EXISTS `direcciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `direcciones` (
  `id_direccion` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` varchar(10) NOT NULL COMMENT 'Almacena el id del usuario que tiene la direccion',
  `calle` varchar(30) NOT NULL COMMENT 'Guarda nombre de la calle del usuario',
  `colonia` varchar(30) NOT NULL COMMENT 'Guarda nombre de la colonia del usuario',
  `cod_postal` int(5) NOT NULL COMMENT 'Guarda codigo postal del usuario',
  `numero` int(11) NOT NULL COMMENT 'Guarda numero de casa del usuario',
  `estado` varchar(30) NOT NULL COMMENT 'Guarda nombre del estado del usuario',
  `ciudad` varchar(30) NOT NULL COMMENT 'Guarda nombre de la ciudad del usuario',
  PRIMARY KEY (`id_direccion`),
  KEY `id_usuario` (`id_usuario`),
  CONSTRAINT `direcciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `direcciones`
--

LOCK TABLES `direcciones` WRITE;
/*!40000 ALTER TABLE `direcciones` DISABLE KEYS */;
INSERT INTO `direcciones` VALUES (10,'santiT','BUGAMBILIAS','EMILIANO ZAPATA',6274,226,'MORELOS','CUAUTLA'),(11,'Sebastian','Av insurgentes ','Emiliano zapata',7267,43,'Morelos ','Cuautla'),(13,'CG','Av Principal','Centro',12345,101,'MORELOS','CUAUTLA');
/*!40000 ALTER TABLE `direcciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pasteles`
--

DROP TABLE IF EXISTS `pasteles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pasteles` (
  `id_pastel` int(11) NOT NULL COMMENT 'Guarda el numero identificador del pastel',
  `nombreP` varchar(20) NOT NULL COMMENT 'Guarda el nombre del pastel',
  `descripcion` text NOT NULL COMMENT 'Guarda una descripcion de lo que contiene el pastel',
  `precio` double NOT NULL DEFAULT 0 COMMENT 'Guarda el precio del pastel',
  `visualizacion` varchar(15) NOT NULL COMMENT 'Se alamacena visualizar o ocultar dependiendo si se desea visualizar el pastel',
  PRIMARY KEY (`id_pastel`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pasteles`
--

LOCK TABLES `pasteles` WRITE;
/*!40000 ALTER TABLE `pasteles` DISABLE KEYS */;
INSERT INTO `pasteles` VALUES (1,'Cupcake','Esquisito cupcake',40.5,'ocultar'),(2,'Pastel Mediano','Esquisito cupcake personalizado',85,'visualizar'),(3,'Pastel Grande','Esquisito cupcake personalizado con el texto de tu preferecia',150,'visualizar'),(4,'Pastel de Chocolate','Delicioso pastel de chocolate con cobertura suave y relleno cremoso',120,'visualizar'),(5,'Pastel de Fresa','Pastel esponjoso con fresas naturales y crema batida',130,'visualizar'),(7,'Pastel de Zanahoria','Pastel de zanahoria con nueces y glaseado de queso crema',10,'visualizar'),(28,'Gelatina','Gelatina sabor Limon Agrio',10,'visualizar');
/*!40000 ALTER TABLE `pasteles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedido_articulos`
--

DROP TABLE IF EXISTS `pedido_articulos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedido_articulos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Guarda el id de la descripcion del pedido',
  `pedido_id` int(11) NOT NULL COMMENT 'Guarda el numero identificador del pedido',
  `pastel_id` int(11) NOT NULL COMMENT 'Guarda el numero identificador del pastel con el que se relaciona el pedido',
  `fecEntrega` date NOT NULL COMMENT 'Guarda la fecha de entrega del pastel',
  `color` varchar(30) DEFAULT NULL COMMENT 'Guarda el color del pastel',
  `texto` varchar(50) DEFAULT NULL COMMENT 'Guarda el texto que tendra del pastel',
  PRIMARY KEY (`id`),
  KEY `pedido_id` (`pedido_id`),
  KEY `pastel_id` (`pastel_id`),
  CONSTRAINT `pedido_articulos_ibfk_1` FOREIGN KEY (`pedido_id`) REFERENCES `pedidos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `pedido_articulos_ibfk_2` FOREIGN KEY (`pastel_id`) REFERENCES `pasteles` (`id_pastel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedido_articulos`
--

LOCK TABLES `pedido_articulos` WRITE;
/*!40000 ALTER TABLE `pedido_articulos` DISABLE KEYS */;
INSERT INTO `pedido_articulos` VALUES (68,57,28,'2024-11-27',NULL,NULL);
/*!40000 ALTER TABLE `pedido_articulos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pedidos`
--

DROP TABLE IF EXISTS `pedidos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pedidos` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Guarda el numero identificador del pedido',
  `user_id` varchar(10) NOT NULL COMMENT 'Guarda el numero identificador del usuario con el que esta relacionado ek pedido',
  `fecha_actual` date NOT NULL COMMENT 'Guarda la fecha en la que se realiza el pedido',
  `estatus` varchar(30) NOT NULL COMMENT 'Guarda el estatus del pedido',
  `Total` double NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pedidos`
--

LOCK TABLES `pedidos` WRITE;
/*!40000 ALTER TABLE `pedidos` DISABLE KEYS */;
INSERT INTO `pedidos` VALUES (57,'admin','2024-11-25','Pendiente',120);
/*!40000 ALTER TABLE `pedidos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recetas`
--

DROP TABLE IF EXISTS `recetas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'Guarda el id de la receta',
  `nombre` varchar(20) NOT NULL COMMENT 'Guarda el nombre de la receta',
  `receta` text NOT NULL COMMENT 'Guarda la receta del pastel ',
  `usuario_id` varchar(30) DEFAULT NULL COMMENT 'Guarda el usuario',
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`usuario`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=124 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recetas`
--

LOCK TABLES `recetas` WRITE;
/*!40000 ALTER TABLE `recetas` DISABLE KEYS */;
INSERT INTO `recetas` VALUES (2,'CHOCHOC','AA','admin'),(3,'Flan','AAEE','admin'),(28,'Gelatina','Ingredientes a su gusto','admin');
/*!40000 ALTER TABLE `recetas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `resenias`
--

DROP TABLE IF EXISTS `resenias`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `resenias` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idUsuario` varchar(10) DEFAULT NULL COMMENT 'Guarda el numero identificador del usuario que hizo la rese?a',
  `idpastel` int(11) DEFAULT NULL COMMENT 'Guarda el id del pastel',
  `resenias` text DEFAULT NULL COMMENT 'Guarda la rese?a realizada por el usuario',
  PRIMARY KEY (`id`),
  KEY `idUsuario` (`idUsuario`),
  KEY `idpastel` (`idpastel`),
  CONSTRAINT `resenias_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuarios` (`usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `resenias_ibfk_2` FOREIGN KEY (`idpastel`) REFERENCES `pasteles` (`id_pastel`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `resenias`
--

LOCK TABLES `resenias` WRITE;
/*!40000 ALTER TABLE `resenias` DISABLE KEYS */;
INSERT INTO `resenias` VALUES (19,'santiT',3,'hola'),(25,'santiT',5,'bien'),(28,'santiT',4,'hola'),(31,'santiT',2,'Muy rico Pastel');
/*!40000 ALTER TABLE `resenias` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rol`
--

DROP TABLE IF EXISTS `rol`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rol` (
  `id_rol` int(11) NOT NULL COMMENT 'id del rol',
  `tipo` varchar(20) NOT NULL COMMENT 'tipo de rol',
  PRIMARY KEY (`id_rol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rol`
--

LOCK TABLES `rol` WRITE;
/*!40000 ALTER TABLE `rol` DISABLE KEYS */;
INSERT INTO `rol` VALUES (1,'admin'),(2,'gerente '),(3,'cliente');
/*!40000 ALTER TABLE `rol` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `usuario` varchar(10) NOT NULL COMMENT 'Guarda el nombre identificador del usuario',
  `nombre` varchar(20) NOT NULL COMMENT 'Guarda el nombre real del usuario',
  `apellido` varchar(20) NOT NULL COMMENT 'Guarda el apellido del usuario',
  `correo` varchar(30) NOT NULL COMMENT 'Guarda el correo del usuario',
  `contrasena` varchar(30) NOT NULL COMMENT 'Guarda la contrasenia del usuario',
  `rol` int(11) NOT NULL COMMENT 'Tipo de rol del usuario',
  PRIMARY KEY (`usuario`),
  KEY `rol` (`rol`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol`) REFERENCES `rol` (`id_rol`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES ('1111111111','AAAAAAAAAAAA','AAAAAAAAAAA','anonimosoy@gmail.com','$2y$10$RE5QG0FQGVGETSdQEPNA6em',3),('admin','Admin','Administrador','admin@gmail.com','12345',1),('Alex123','jose','Trejo','Alekei@gmaiil.com','$2y$10$QjS5iUMrLNzvan5CBIi./u0',3),('AP','Angelica','Perez','AP@gmail.com','12345',3),('CG','Carlos','Garcia','CG123@gmail.com','NuevaContraseña123',3),('cinthia123','cinthia','martinez','cinthia.estefania08@gmail.com','123qaz',3),('Gerente2','Santiago','Trejo','Albert@gmail.com','12345',2),('Gerente5','jose','Trejo','Albert@gmail.com','12345',2),('Gerente89','Alekei','Trejo','Albert@gmail.com','12345',2),('Gerente891','popi','Trejo','Albert@gmail.com','12345',2),('JoseJose','santiago','Trejo','tmso221896@upemor.edu.mx','12345',3),('Joselito','Jose','rodriguez','stm123xd@hotmail.com','12345',3),('KeniaLizet','Kenia','Hernandez ','htko220159@upemor.edu.mx','kenia',3),('Patata','Sed','Tm','rata@gmail.com','2010',3),('PepeG','Pepe','Grillo','PepitoGrillo@gmail.com','$2y$10$9bnyaUMFi/HfhYBi63aHWuA',3),('santiT','Santi','Trejo','stm2594xd@gmail.com','12345',3),('santiT25','Santiago','Trejo','stm24xd@gmail.com','12345',3),('Sebastian','Sebastian','Trejo','123trejomario@gmail.com','260967',3),('TorresJose','Jose','Torres','TorresJose@outlook.com','12345',3),('Zaz','Cacaa','Popo','sebastiantrejo727@gmail.com','uwu',3);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-11-26 13:28:16
