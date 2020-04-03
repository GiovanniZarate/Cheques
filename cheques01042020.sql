-- MySQL dump 10.13  Distrib 8.0.19, for Win64 (x86_64)
--
-- Host: localhost    Database: cheques
-- ------------------------------------------------------
-- Server version	8.0.19

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
-- Table structure for table `agentes`
--

DROP TABLE IF EXISTS `agentes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `agentes` (
  `apellidos` varchar(200) COLLATE utf8_bin DEFAULT NULL,
  `ciudad` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `codagente` varchar(10) COLLATE utf8_bin NOT NULL,
  `coddepartamento` varchar(6) COLLATE utf8_bin DEFAULT NULL,
  `codpais` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `codpostal` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `dnicif` varchar(15) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `fax` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `idprovincia` int DEFAULT NULL,
  `idusuario` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `irpf` double DEFAULT NULL,
  `nombre` varchar(50) COLLATE utf8_bin NOT NULL,
  `nombreap` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  `porcomision` double DEFAULT NULL,
  `provincia` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `telefono` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `seg_social` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `cargo` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `banco` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `f_alta` date DEFAULT NULL,
  `f_baja` date DEFAULT NULL,
  `f_nacimiento` date DEFAULT NULL,
  PRIMARY KEY (`codagente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `agentes`
--

LOCK TABLES `agentes` WRITE;
/*!40000 ALTER TABLE `agentes` DISABLE KEYS */;
INSERT INTO `agentes` VALUES ('Pepe',NULL,'1',NULL,NULL,NULL,NULL,'00000014Z',NULL,NULL,NULL,NULL,NULL,'Paco',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `agentes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `almacenes`
--

DROP TABLE IF EXISTS `almacenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `almacenes` (
  `apartado` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `codalmacen` varchar(4) COLLATE utf8_bin NOT NULL,
  `codpais` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `codpostal` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `contacto` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `fax` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `idprovincia` int DEFAULT NULL,
  `nombre` varchar(100) COLLATE utf8_bin NOT NULL,
  `observaciones` text COLLATE utf8_bin,
  `poblacion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `porpvp` double DEFAULT NULL,
  `provincia` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `telefono` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `tipovaloracion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`codalmacen`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `almacenes`
--

LOCK TABLES `almacenes` WRITE;
/*!40000 ALTER TABLE `almacenes` DISABLE KEYS */;
INSERT INTO `almacenes` VALUES (NULL,'ALG',NULL,'','','','',NULL,'ALMACEN GENERAL',NULL,'',NULL,NULL,'',NULL);
/*!40000 ALTER TABLE `almacenes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bancos`
--

DROP TABLE IF EXISTS `bancos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `bancos` (
  `idbanco` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `telefono` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`idbanco`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bancos`
--

LOCK TABLES `bancos` WRITE;
/*!40000 ALTER TABLE `bancos` DISABLE KEYS */;
INSERT INTO `bancos` VALUES (1,'BANCO ITAU','AVDA PERU','02165656'),(3,'BANCO VISION','CERRO CORA','0125656');
/*!40000 ALTER TABLE `bancos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheque_operacion`
--

DROP TABLE IF EXISTS `cheque_operacion`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cheque_operacion` (
  `idcheque_operacion` int NOT NULL AUTO_INCREMENT,
  `idoperaciones` int NOT NULL,
  `cheques_nrocheque` int NOT NULL,
  `cheques_codbanco` int NOT NULL,
  PRIMARY KEY (`idcheque_operacion`),
  UNIQUE KEY `unico_cheque_oper` (`cheques_nrocheque`,`idoperaciones`),
  KEY `fk_cheque_operacion_operaciones_idx` (`idoperaciones`),
  KEY `fk_cheque_operacion_cheques1_idx` (`cheques_nrocheque`,`cheques_codbanco`) /*!80000 INVISIBLE */,
  CONSTRAINT `fk_cheque_operacion_cheques1` FOREIGN KEY (`cheques_nrocheque`) REFERENCES `cheques` (`nrocheque`),
  CONSTRAINT `fk_cheque_operacion_operaciones` FOREIGN KEY (`idoperaciones`) REFERENCES `operaciones` (`idoperaciones`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheque_operacion`
--

LOCK TABLES `cheque_operacion` WRITE;
/*!40000 ALTER TABLE `cheque_operacion` DISABLE KEYS */;
INSERT INTO `cheque_operacion` VALUES (8,9,1233,1),(9,9,123456,3),(15,11,123456,3),(16,12,1233,1),(17,12,123456,3);
/*!40000 ALTER TABLE `cheque_operacion` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cheques`
--

DROP TABLE IF EXISTS `cheques`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cheques` (
  `nrocheque` int NOT NULL,
  `idbanco` int NOT NULL,
  `nrocuenta` varchar(30) DEFAULT NULL,
  `fec_emision` date NOT NULL,
  `fec_pago` date DEFAULT NULL,
  `importe` double NOT NULL DEFAULT '0',
  `aordende` varchar(150) DEFAULT NULL,
  `idcliente` int NOT NULL,
  `tipo` char(1) NOT NULL DEFAULT 'C',
  PRIMARY KEY (`nrocheque`,`idbanco`),
  KEY `fk_cheques_banco_idx` (`idbanco`),
  KEY `fk_cheques_cliente_idx` (`idcliente`),
  CONSTRAINT `fk_cheques_banco` FOREIGN KEY (`idbanco`) REFERENCES `bancos` (`idbanco`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_cheques_cliente` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliente`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cheques`
--

LOCK TABLES `cheques` WRITE;
/*!40000 ALTER TABLE `cheques` DISABLE KEYS */;
INSERT INTO `cheques` VALUES (1233,1,'123456789','2020-03-25','2020-03-26',500000,'IGNACIO ZARATE',1,'E'),(123456,3,'123456','2020-03-25','2020-03-23',1500000,'GIOVANNI',1,'C');
/*!40000 ALTER TABLE `cheques` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ciudad`
--

DROP TABLE IF EXISTS `ciudad`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ciudad` (
  `id_ciudad` int NOT NULL AUTO_INCREMENT,
  `id_departamento` int NOT NULL,
  `nombre_corto` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `nombre_largo` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id_ciudad`,`id_departamento`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ciudad`
--

LOCK TABLES `ciudad` WRITE;
/*!40000 ALTER TABLE `ciudad` DISABLE KEYS */;
/*!40000 ALTER TABLE `ciudad` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clientes`
--

DROP TABLE IF EXISTS `clientes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `clientes` (
  `idcliente` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(150) NOT NULL,
  `direccion` varchar(150) DEFAULT NULL,
  `ruc` varchar(20) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  PRIMARY KEY (`idcliente`),
  UNIQUE KEY `uniq_ruc_clientes` (`ruc`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clientes`
--

LOCK TABLES `clientes` WRITE;
/*!40000 ALTER TABLE `clientes` DISABLE KEYS */;
INSERT INTO `clientes` VALUES (1,'GIOVANNI','EEUU','4203593','06916464664'),(3,'JUANA FARIÑA','ISLA','5331231-7','064664');
/*!40000 ALTER TABLE `clientes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cuentasbanco`
--

DROP TABLE IF EXISTS `cuentasbanco`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cuentasbanco` (
  `codsubcuenta` varchar(15) COLLATE utf8_bin DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `iban` varchar(34) COLLATE utf8_bin DEFAULT NULL,
  `codcuenta` varchar(6) COLLATE utf8_bin NOT NULL,
  `swift` varchar(11) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`codcuenta`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cuentasbanco`
--

LOCK TABLES `cuentasbanco` WRITE;
/*!40000 ALTER TABLE `cuentasbanco` DISABLE KEYS */;
/*!40000 ALTER TABLE `cuentasbanco` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `divisas`
--

DROP TABLE IF EXISTS `divisas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `divisas` (
  `bandera` text COLLATE utf8_bin,
  `coddivisa` varchar(3) COLLATE utf8_bin NOT NULL,
  `codiso` varchar(3) COLLATE utf8_bin DEFAULT NULL,
  `descripcion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `simbolo` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `tasaconv` double NOT NULL,
  `tasaconv_compra` double DEFAULT NULL,
  PRIMARY KEY (`coddivisa`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `divisas`
--

LOCK TABLES `divisas` WRITE;
/*!40000 ALTER TABLE `divisas` DISABLE KEYS */;
INSERT INTO `divisas` VALUES (NULL,'ARS','32','PESOS (ARG)',NULL,'AR$',16.684,16.684),(NULL,'CLP','152','PESOS (CLP)',NULL,'CLP$',704.0227,704.0227),(NULL,'COP','170','PESOS (COP)',NULL,'CO$',3140.6803,3140.6803),(NULL,'DOP','214','PESOS DOMINICANOS',NULL,'RD$',49.7618,49.7618),(NULL,'EUR','978','EUROS',NULL,'€',1,1),(NULL,'GBP','826','LIBRAS ESTERLINAS',NULL,'£',0.865,0.865),(NULL,'HTG','322','GOURDES',NULL,'G',72.0869,72.0869),(NULL,'MXN','484','PESOS (MXN)',NULL,'MX$',23.3678,23.3678),(NULL,'PAB','590','BALBOAS',NULL,'B',1.128,1.128),(NULL,'PEN','604','NUEVOS SOLES',NULL,'S/.',3.736,3.736),(NULL,'USD','840','DÓLARES EE.UU.',NULL,'$',1.129,1.129),(NULL,'VEF','937','BOLÍVARES',NULL,'Bs',10.6492,10.6492);
/*!40000 ALTER TABLE `divisas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ejercicios`
--

DROP TABLE IF EXISTS `ejercicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ejercicios` (
  `idasientocierre` int DEFAULT NULL,
  `idasientopyg` int DEFAULT NULL,
  `idasientoapertura` int DEFAULT NULL,
  `plancontable` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `longsubcuenta` int DEFAULT NULL,
  `estado` varchar(15) COLLATE utf8_bin NOT NULL,
  `fechafin` date NOT NULL,
  `fechainicio` date NOT NULL,
  `nombre` varchar(100) COLLATE utf8_bin NOT NULL,
  `codejercicio` varchar(4) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`codejercicio`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ejercicios`
--

LOCK TABLES `ejercicios` WRITE;
/*!40000 ALTER TABLE `ejercicios` DISABLE KEYS */;
INSERT INTO `ejercicios` VALUES (NULL,NULL,NULL,'08',10,'ABIERTO','2020-12-31','2020-01-01','2020','2020');
/*!40000 ALTER TABLE `ejercicios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `empresa`
--

DROP TABLE IF EXISTS `empresa`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `empresa` (
  `administrador` varchar(100) COLLATE utf8_bin NOT NULL,
  `apartado` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `cifnif` varchar(30) COLLATE utf8_bin NOT NULL,
  `ciudad` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `codalmacen` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `codcuentarem` varchar(6) COLLATE utf8_bin DEFAULT NULL,
  `coddivisa` varchar(3) COLLATE utf8_bin DEFAULT NULL,
  `codedi` varchar(17) COLLATE utf8_bin DEFAULT NULL,
  `codejercicio` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `codpago` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `codpais` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `codpostal` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `codserie` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `contintegrada` tinyint(1) DEFAULT NULL,
  `direccion` varchar(100) COLLATE utf8_bin NOT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `fax` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `horario` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `id` int NOT NULL AUTO_INCREMENT,
  `idprovincia` int DEFAULT NULL,
  `xid` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `lema` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `logo` text COLLATE utf8_bin,
  `nombre` varchar(100) COLLATE utf8_bin NOT NULL,
  `nombrecorto` varchar(32) COLLATE utf8_bin DEFAULT NULL,
  `pie_factura` text COLLATE utf8_bin,
  `provincia` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `recequivalencia` tinyint(1) DEFAULT NULL,
  `stockpedidos` tinyint(1) DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `web` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `inicioact` date DEFAULT NULL,
  `regimeniva` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `empresa`
--

LOCK TABLES `empresa` WRITE;
/*!40000 ALTER TABLE `empresa` DISABLE KEYS */;
INSERT INTO `empresa` VALUES ('','','00000014Z','','ALG',NULL,'EUR',NULL,'0001','CONT','ESP','','A',0,'C/ Falsa, 123','','','',1,NULL,'ypOYdWQ1hK5BUscewxnXlRzLJFbI0f','',NULL,'Cheques','CH','','',0,0,'','https://www.ch.com','1970-01-01',NULL);
/*!40000 ALTER TABLE `empresa` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `estado`
--

DROP TABLE IF EXISTS `estado`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `estado` (
  `idestado` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  PRIMARY KEY (`idestado`),
  UNIQUE KEY `uniq_nombre_estado` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `estado`
--

LOCK TABLES `estado` WRITE;
/*!40000 ALTER TABLE `estado` DISABLE KEYS */;
INSERT INTO `estado` VALUES (2,'COMPLETADO'),(1,'PENDIENTE');
/*!40000 ALTER TABLE `estado` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `formaspago`
--

DROP TABLE IF EXISTS `formaspago`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `formaspago` (
  `codpago` varchar(10) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `genrecibos` varchar(10) COLLATE utf8_bin NOT NULL,
  `codcuenta` varchar(6) COLLATE utf8_bin DEFAULT NULL,
  `domiciliado` tinyint(1) DEFAULT NULL,
  `imprimir` tinyint(1) DEFAULT '1',
  `vencimiento` varchar(20) COLLATE utf8_bin DEFAULT '+1month',
  PRIMARY KEY (`codpago`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `formaspago`
--

LOCK TABLES `formaspago` WRITE;
/*!40000 ALTER TABLE `formaspago` DISABLE KEYS */;
INSERT INTO `formaspago` VALUES ('CONT','Al contado','Pagados',NULL,0,1,'+0day'),('PAYPAL','PayPal','Pagados',NULL,0,1,'+0day'),('TARJETA','Tarjeta de crédito','Pagados',NULL,0,1,'+0day'),('TRANS','Transferencia bancaria','Emitidos',NULL,0,1,'+1month');
/*!40000 ALTER TABLE `formaspago` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_extensions2`
--

DROP TABLE IF EXISTS `fs_extensions2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_extensions2` (
  `name` varchar(50) COLLATE utf8_bin NOT NULL,
  `page_from` varchar(30) COLLATE utf8_bin NOT NULL,
  `page_to` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_bin DEFAULT NULL,
  `text` text COLLATE utf8_bin,
  `params` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`name`,`page_from`),
  KEY `ca_fs_extensions2_fs_pages` (`page_from`),
  CONSTRAINT `ca_fs_extensions2_fs_pages` FOREIGN KEY (`page_from`) REFERENCES `fs_pages` (`name`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_extensions2`
--

LOCK TABLES `fs_extensions2` WRITE;
/*!40000 ALTER TABLE `fs_extensions2` DISABLE KEYS */;
INSERT INTO `fs_extensions2` VALUES ('albaranes_agente','operaciones','admin_agente','button','<span class=\"glyphicon glyphicon-list\" aria-hidden=\"true\"></span> &nbsp; Albaranes de cliente',''),('albaranes_articulo','operaciones','ventas_articulo','tab_button','<span class=\"glyphicon glyphicon-list\" aria-hidden=\"true\"></span> &nbsp; Albaranes de cliente',''),('albaranes_cliente','operaciones','ventas_cliente','button','<span class=\"glyphicon glyphicon-list\" aria-hidden=\"true\"></span> &nbsp; Albaranes',''),('cosmo','admin_user','admin_user','css','view/css/bootstrap-cosmo.min.css',''),('darkly','admin_user','admin_user','css','view/css/bootstrap-darkly.min.css',''),('flatly','admin_user','admin_user','css','view/css/bootstrap-flatly.min.css',''),('lumen','admin_user','admin_user','css','view/css/bootstrap-lumen.min.css',''),('paper','admin_user','admin_user','css','view/css/bootstrap-paper.min.css',''),('sandstone','admin_user','admin_user','css','view/css/bootstrap-sandstone.min.css',''),('simplex','admin_user','admin_user','css','view/css/bootstrap-simplex.min.css',''),('spacelab','admin_user','admin_user','css','view/css/bootstrap-spacelab.min.css',''),('united','admin_user','admin_user','css','view/css/bootstrap-united.min.css',''),('yeti','admin_user','admin_user','css','view/css/bootstrap-yeti.min.css','');
/*!40000 ALTER TABLE `fs_extensions2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_logs`
--

DROP TABLE IF EXISTS `fs_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_logs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `tipo` varchar(50) COLLATE utf8_bin NOT NULL,
  `detalle` text COLLATE utf8_bin NOT NULL,
  `fecha` timestamp NOT NULL DEFAULT '2020-04-01 03:00:00',
  `usuario` varchar(12) COLLATE utf8_bin DEFAULT NULL,
  `ip` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `alerta` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_logs`
--

LOCK TABLES `fs_logs` WRITE;
/*!40000 ALTER TABLE `fs_logs` DISABLE KEYS */;
INSERT INTO `fs_logs` VALUES (1,'error','Error al descargar la lista de plugins.','2020-03-23 22:48:50','admin','::1',0),(2,'error','Error al descargar la lista de plugins.','2020-03-23 22:49:06','admin','::1',0),(3,'error','Error al descargar la lista de plugins.','2020-03-23 22:49:21','admin','::1',0),(4,'error','Error al descargar la lista de plugins.','2020-03-23 22:50:02','admin','::1',0),(5,'error','Error al descargar la lista de plugins.','2020-03-23 22:50:11','admin','::1',0),(6,'error','Error al descargar la lista de plugins.','2020-03-23 22:50:19','admin','::1',0),(7,'error','Error al descargar la lista de plugins.','2020-03-23 22:50:31','admin','::1',0),(8,'error','Error al descargar la lista de plugins.','2020-03-23 22:51:25','admin','::1',0),(9,'login','El usuario ha cerrado la sesión.','2020-03-23 22:54:46',NULL,NULL,0),(10,'error','Error al descargar la lista de plugins.','2020-03-23 22:54:51','admin','::1',0),(11,'error','Error al descargar la lista de plugins.','2020-03-23 22:54:53','admin','::1',0),(12,'error','Archivo model/table/plan.xml no encontrado.','2020-03-23 22:54:53',NULL,NULL,0),(13,'error','Error con el xml.','2020-03-23 22:54:53',NULL,NULL,0),(14,'error','Archivo model/table/ciudad.xml no encontrado.','2020-03-23 22:54:59',NULL,NULL,0),(15,'error','Error con el xml.','2020-03-23 22:54:59',NULL,NULL,0),(16,'error','Archivo model/table/barrio.xml no encontrado.','2020-03-23 22:54:59',NULL,NULL,0),(17,'error','Error con el xml.','2020-03-23 22:54:59',NULL,NULL,0),(18,'error','Archivo model/table/ciudad.xml no encontrado.','2020-03-23 22:55:01',NULL,NULL,0),(19,'error','Error con el xml.','2020-03-23 22:55:01',NULL,NULL,0),(20,'error','Archivo model/table/departamento.xml no encontrado.','2020-03-23 22:55:02',NULL,NULL,0),(21,'error','Error con el xml.','2020-03-23 22:55:02',NULL,NULL,0),(22,'error','Archivo model/table/plan.xml no encontrado.','2020-03-23 22:55:05',NULL,NULL,0),(23,'error','Error con el xml.','2020-03-23 22:55:05',NULL,NULL,0),(24,'error','Archivo model/table/ciudad.xml no encontrado.','2020-03-23 22:56:41',NULL,NULL,0),(25,'error','Error con el xml.','2020-03-23 22:56:41',NULL,NULL,0),(26,'error','Archivo model/table/barrio.xml no encontrado.','2020-03-23 22:56:41',NULL,NULL,0),(27,'error','Error con el xml.','2020-03-23 22:56:42',NULL,NULL,0),(28,'error','Archivo model/table/departamento.xml no encontrado.','2020-03-23 22:56:47',NULL,NULL,0),(29,'error','Error con el xml.','2020-03-23 22:56:47',NULL,NULL,0),(30,'error','Error al descargar la lista de plugins.','2020-03-23 23:13:27','admin','::1',0),(31,'error','Archivo model/table/plan.xml no encontrado.','2020-03-23 23:13:28',NULL,NULL,0),(32,'error','Error con el xml.','2020-03-23 23:13:28',NULL,NULL,0),(33,'error','Error al descargar la lista de plugins.','2020-03-23 23:13:37','admin','::1',0),(34,'error','Error al descargar la lista de plugins.','2020-03-23 23:13:52','admin','::1',0),(35,'error','Imposible guardar!','2020-03-23 23:14:39','admin','::1',0),(36,'login','El usuario ha cerrado la sesión.','2020-03-23 23:29:21',NULL,NULL,0),(37,'error','Error al descargar la lista de plugins.','2020-03-24 16:30:23','admin','::1',0),(38,'error','Error al descargar la lista de plugins.','2020-03-24 16:30:23','admin','::1',0),(39,'error','Error al descargar la lista de plugins.','2020-03-24 16:30:31','admin','::1',0),(40,'error','Error al descargar la lista de plugins.','2020-03-24 16:30:37','admin','::1',0),(41,'error','Error al descargar la lista de plugins.','2020-03-24 16:31:19','admin','::1',0),(42,'error','Error al descargar la lista de plugins.','2020-03-24 16:31:28','admin','::1',0),(43,'error','Error al descargar la lista de plugins.','2020-03-24 16:31:37','admin','::1',0),(44,'error','Error al descargar la lista de plugins.','2020-03-24 16:35:19','admin','::1',0),(45,'error','Error al descargar la lista de plugins.','2020-03-24 18:37:51','admin','::1',0),(46,'error','Error al descargar la lista de plugins.','2020-03-24 18:38:02','admin','::1',0),(47,'error','Imposible guardar!','2020-03-24 18:42:25','admin','::1',0),(48,'error','Error al descargar la lista de plugins.','2020-03-24 20:38:18','admin','::1',0),(49,'error','Archivo model/table/plan.xml no encontrado.','2020-03-24 20:38:19',NULL,NULL,0),(50,'error','Error con el xml.','2020-03-24 20:38:19',NULL,NULL,0),(51,'error','Error al descargar la lista de plugins.','2020-03-24 20:38:27','admin','::1',0),(52,'error','Error al descargar la lista de plugins.','2020-03-25 16:19:21','admin','::1',0),(53,'error','Error al descargar la lista de plugins.','2020-03-25 16:19:30','admin','::1',0),(54,'error','Error al descargar la lista de plugins.','2020-03-25 16:19:35','admin','::1',0),(55,'error','Error al descargar la lista de plugins.','2020-03-25 16:20:29','admin','::1',0),(56,'error','Archivo model/table/cheque.xml no encontrado.','2020-03-25 16:20:34',NULL,NULL,0),(57,'error','Error con el xml.','2020-03-25 16:20:34',NULL,NULL,0),(58,'error','Error al descargar la lista de plugins.','2020-03-25 16:22:06','admin','::1',0),(59,'error','Error al descargar la lista de plugins.','2020-03-25 16:24:23','admin','::1',0),(60,'error','Error al descargar la lista de plugins.','2020-03-25 16:24:36','admin','::1',0),(61,'error','Error al renombrar el plugin.','2020-03-25 16:24:36','admin','::1',0),(62,'error','Error al descargar la lista de plugins.','2020-03-25 16:24:43','admin','::1',0),(63,'error','Archivo model/table/cheque.xml no encontrado.','2020-03-25 16:25:01',NULL,NULL,0),(64,'error','Error con el xml.','2020-03-25 16:25:01',NULL,NULL,0),(65,'error','Error al comprobar la tabla cheques','2020-03-25 16:26:17',NULL,NULL,0),(66,'error','Error al comprobar la tabla cheques','2020-03-25 16:27:13',NULL,NULL,0),(67,'error','Error al comprobar la tabla cheques','2020-03-25 16:28:07',NULL,NULL,0),(68,'error','Error al descargar la lista de plugins.','2020-03-25 16:28:12','admin','::1',0),(69,'error','Error al descargar la lista de plugins.','2020-03-25 16:28:16','admin','::1',0),(70,'error','Error al descargar la lista de plugins.','2020-03-25 16:28:23','admin','::1',0),(71,'error','Error al comprobar la tabla cheques','2020-03-25 15:28:25',NULL,NULL,0),(72,'error','Error al comprobar la tabla cheques','2020-03-25 15:28:25',NULL,NULL,0),(73,'error','Error al descargar la lista de plugins.','2020-03-25 16:30:55','admin','::1',0),(74,'error','Error al descargar la lista de plugins.','2020-03-25 16:31:03','admin','::1',0),(75,'error','Error al descargar la lista de plugins.','2020-03-25 16:31:12','admin','::1',0),(76,'error','Error al descargar la lista de plugins.','2020-03-25 16:31:20','admin','::1',0),(77,'error','Error al comprobar la tabla cheques','2020-03-25 15:31:21',NULL,NULL,0),(78,'error','Error al comprobar la tabla cheques','2020-03-25 15:31:21',NULL,NULL,0),(79,'error','Error al descargar la lista de plugins.','2020-03-25 16:32:01','admin','::1',0),(80,'error','Archivo model/table/cheques.xml no encontrado.','2020-03-25 16:32:04',NULL,NULL,0),(81,'error','Error con el xml.','2020-03-25 16:32:04',NULL,NULL,0),(82,'error','¡Imposible guardar los datos!','2020-03-25 17:45:14','admin','::1',0),(83,'error','Error al descargar la lista de plugins.','2020-03-26 21:36:32','admin','::1',0),(84,'error','Error al descargar la lista de plugins.','2020-03-26 21:36:39','admin','::1',0),(85,'error','Error al descargar la lista de plugins.','2020-03-26 21:36:44','admin','::1',0),(86,'error','Error al descargar la lista de plugins.','2020-03-26 21:36:49','admin','::1',0),(87,'error','Error al descargar la lista de plugins.','2020-03-26 21:37:42','admin','::1',0),(88,'error','Error al descargar la lista de plugins.','2020-03-26 22:32:09','admin','::1',0),(89,'error','Error al descargar la lista de plugins.','2020-03-26 22:41:31','admin','::1',0),(90,'error','Error al descargar la lista de plugins.','2020-03-26 22:42:45','admin','::1',0),(91,'error','Error al descargar la lista de plugins.','2020-03-26 22:43:50','admin','::1',0),(92,'error','Error al descargar la lista de plugins.','2020-03-26 22:46:04','admin','::1',0),(93,'error','Error al descargar la lista de plugins.','2020-03-26 22:46:11','admin','::1',0),(94,'error','Error al descargar la lista de plugins.','2020-03-26 22:46:42','admin','::1',0),(95,'error','Error al descargar la lista de plugins.','2020-03-26 22:46:53','admin','::1',0),(96,'error','Error al descargar la lista de plugins.','2020-03-27 00:21:44','admin','::1',0),(97,'error','Error al descargar la lista de plugins.','2020-03-27 00:21:50','admin','::1',0),(98,'error','Error al descargar la lista de plugins.','2020-03-27 18:29:17','admin','::1',0),(99,'error','Error al descargar la lista de plugins.','2020-03-27 18:29:55','admin','::1',0),(100,'error','Error al descargar la lista de plugins.','2020-03-27 18:30:00','admin','::1',0),(101,'error','Error al descargar la lista de plugins.','2020-03-27 18:38:48','admin','::1',0),(102,'error','¡Imposible guardar la dirección del cliente!','2020-03-27 21:48:51','admin','::1',0),(103,'error','Error al descargar la lista de plugins.','2020-03-27 21:49:27','admin','::1',0),(104,'error','Error al descargar la lista de plugins.','2020-03-27 21:50:03','admin','::1',0),(105,'error','Error al descargar la lista de plugins.','2020-03-28 17:03:28','admin','::1',0),(106,'error','Error al descargar la lista de plugins.','2020-03-28 17:03:45','admin','::1',0),(107,'error','Error al descargar la lista de plugins.','2020-03-28 17:03:51','admin','::1',0),(108,'error','Error al descargar la lista de plugins.','2020-03-28 18:44:27','admin','::1',0),(109,'error','Error al descargar la lista de plugins.','2020-03-28 18:44:33','admin','::1',0),(110,'login','El usuario ha cerrado la sesión.','2020-03-28 19:21:22',NULL,NULL,0),(111,'error','Error al descargar la lista de plugins.','2020-03-28 19:21:26','admin','::1',0),(112,'login','El usuario ha cerrado la sesión.','2020-03-28 21:59:31',NULL,NULL,0),(113,'error','Error al descargar la lista de plugins.','2020-03-30 16:16:05','admin','::1',0),(114,'error','Error al descargar la lista de plugins.','2020-03-30 16:17:02','admin','::1',0),(115,'error','Error al descargar la lista de plugins.','2020-03-30 16:17:06','admin','::1',0),(116,'error','Error al descargar la lista de plugins.','2020-03-30 17:47:44','admin','::1',0),(117,'error','Error al descargar la lista de plugins.','2020-03-30 17:47:48','admin','::1',0),(118,'error','¡Imposible guardar la Operacion!','2020-03-30 19:25:00','admin','::1',0),(119,'error','¡Imposible guardar la Operacion!','2020-03-30 20:41:56','admin','::1',0),(120,'error','¡Imposible guardar la Operacion!','2020-03-30 20:44:26','admin','::1',0),(121,'error','¡Imposible guardar la Operacion!','2020-03-30 20:48:06','admin','::1',0),(122,'error','¡Imposible guardar la Operacion!','2020-03-30 20:51:53','admin','::1',0),(123,'error','¡Imposible guardar la Operacion!','2020-03-30 20:54:01','admin','::1',0),(124,'error','¡Imposible guardar la Operacion!','2020-03-30 20:58:28','admin','::1',0),(125,'error','ANTES GRABAR','2020-03-30 20:59:33','admin','::1',0),(126,'error','¡Imposible guardar la Operacion!','2020-03-30 20:59:33','admin','::1',0),(127,'error','ANTES GRABAR','2020-03-30 21:03:07','admin','::1',0),(128,'error','¡Imposible guardar la Operacion!','2020-03-30 21:03:08','admin','::1',0),(129,'error','ANTES GRABAR','2020-03-30 21:04:50','admin','::1',0),(130,'error','¡Imposible guardar la Operacion!','2020-03-30 21:04:50','admin','::1',0),(131,'error','ANTES GRABAR','2020-03-30 21:06:21','admin','::1',0),(132,'error','¡Imposible guardar la Operacion!','2020-03-30 21:06:21','admin','::1',0),(133,'error','ANTES GRABAR','2020-03-30 21:07:50','admin','::1',0),(134,'error','¡Imposible guardar la Operacion!!!','2020-03-30 21:07:50','admin','::1',0),(135,'error','ANTES GRABAR','2020-03-30 21:11:24','admin','::1',0),(136,'error','LUEGO GRABAR','2020-03-30 21:11:24','admin','::1',0),(137,'error','Petición duplicada. Has hecho doble clic sobre el botón guardar\n               y se han enviado dos peticiones. Mira en <a href=\"index.php?page=operaciones_edit&id=0\"> OPERACIONES </a>\n               para ver si el Operaciones se ha guardado correctamente.','2020-03-30 21:37:58','admin','::1',0),(138,'error','¡Imposible actualizar el <a href=\'index.php?page=operaciones_edit&id=5\'>Operacion</a>!','2020-03-30 21:38:53','admin','::1',0),(139,'error','¡Imposible guardar la linea con Nro. Cheque: 123456','2020-03-30 21:41:03','admin','::1',0),(140,'error','¡Imposible eliminar el <a href=\'index.php?page=operaciones_edit&id=6\'>Operacion</a>!','2020-03-30 21:41:03','admin','::1',0),(141,'error','¡Imposible guardar la linea con Nro. Cheque: 123456','2020-03-30 21:43:28','admin','::1',0),(142,'error','¡Imposible eliminar el <a href=\'index.php?page=operaciones_edit&id=7\'>Operacion</a>!','2020-03-30 21:43:28','admin','::1',0),(143,'error','¡Imposible actualizar el <a href=\'index.php?page=operaciones_edit&id=8\'>Operacion</a>!','2020-03-30 21:45:19','admin','::1',0),(144,'error','Error al descargar la lista de plugins.','2020-03-30 21:57:53','admin','::1',0),(145,'error','Error al descargar la lista de plugins.','2020-03-30 23:11:44','admin','::1',0),(146,'error','Error al descargar la lista de plugins.','2020-03-30 23:11:52','admin','::1',0),(147,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:09:27',NULL,NULL,0),(148,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:09:42',NULL,NULL,0),(149,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:15:40',NULL,NULL,0),(150,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:21:20',NULL,NULL,0),(151,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:22:14',NULL,NULL,0),(152,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:22:14',NULL,NULL,0),(153,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:22:14',NULL,NULL,0),(154,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:33:06',NULL,NULL,0),(155,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:33:06',NULL,NULL,0),(156,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:33:06',NULL,NULL,0),(157,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:08',NULL,NULL,0),(158,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:08',NULL,NULL,0),(159,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:08',NULL,NULL,0),(160,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:27',NULL,NULL,0),(161,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:27',NULL,NULL,0),(162,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:40:27',NULL,NULL,0),(163,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:03',NULL,NULL,0),(164,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:03',NULL,NULL,0),(165,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:04',NULL,NULL,0),(166,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:56',NULL,NULL,0),(167,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:57',NULL,NULL,0),(168,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:41:57',NULL,NULL,0),(169,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:31',NULL,NULL,0),(170,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:31',NULL,NULL,0),(171,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:31',NULL,NULL,0),(172,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:59',NULL,NULL,0),(173,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:59',NULL,NULL,0),(174,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:44:59',NULL,NULL,0),(175,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:45:15',NULL,NULL,0),(176,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:45:15',NULL,NULL,0),(177,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:45:16',NULL,NULL,0),(178,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:46:46',NULL,NULL,0),(179,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:46:46',NULL,NULL,0),(180,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:46:46',NULL,NULL,0),(181,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:23',NULL,NULL,0),(182,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:23',NULL,NULL,0),(183,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:23',NULL,NULL,0),(184,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:49',NULL,NULL,0),(185,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:49',NULL,NULL,0),(186,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 00:47:49',NULL,NULL,0),(187,'login','El usuario ha cerrado la sesión.','2020-03-31 00:48:14',NULL,NULL,0),(188,'error','Error al descargar la lista de plugins.','2020-03-31 16:19:12','admin','::1',0),(189,'error','Error al descargar la lista de plugins.','2020-03-31 16:19:19','admin','::1',0),(190,'error','Error al descargar la lista de plugins.','2020-03-31 16:19:25','admin','::1',0),(191,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:34',NULL,NULL,0),(192,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:34',NULL,NULL,0),(193,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:35',NULL,NULL,0),(194,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:41',NULL,NULL,0),(195,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:41',NULL,NULL,0),(196,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:19:42',NULL,NULL,0),(197,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:21:49',NULL,NULL,0),(198,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:21:49',NULL,NULL,0),(199,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:21:49',NULL,NULL,0),(200,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:09',NULL,NULL,0),(201,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:10',NULL,NULL,0),(202,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:10',NULL,NULL,0),(203,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:43',NULL,NULL,0),(204,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:43',NULL,NULL,0),(205,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:43',NULL,NULL,0),(206,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:58',NULL,NULL,0),(207,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:58',NULL,NULL,0),(208,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:22:58',NULL,NULL,0),(209,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:13',NULL,NULL,0),(210,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:13',NULL,NULL,0),(211,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:13',NULL,NULL,0),(212,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:53',NULL,NULL,0),(213,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:53',NULL,NULL,0),(214,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:23:54',NULL,NULL,0),(215,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:25:49',NULL,NULL,0),(216,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:25:49',NULL,NULL,0),(217,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:25:49',NULL,NULL,0),(218,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:26:26',NULL,NULL,0),(219,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:26:27',NULL,NULL,0),(220,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:26:27',NULL,NULL,0),(221,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:28:43',NULL,NULL,0),(222,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:28:43',NULL,NULL,0),(223,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:28:44',NULL,NULL,0),(224,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:29:40',NULL,NULL,0),(225,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:29:41',NULL,NULL,0),(226,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:29:41',NULL,NULL,0),(227,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:30:02',NULL,NULL,0),(228,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:30:02',NULL,NULL,0),(229,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:30:02',NULL,NULL,0),(230,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:06',NULL,NULL,0),(231,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:06',NULL,NULL,0),(232,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:06',NULL,NULL,0),(233,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:26',NULL,NULL,0),(234,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:27',NULL,NULL,0),(235,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:32:27',NULL,NULL,0),(236,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:27',NULL,NULL,0),(237,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:27',NULL,NULL,0),(238,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:27',NULL,NULL,0),(239,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:29',NULL,NULL,0),(240,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:30',NULL,NULL,0),(241,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:30',NULL,NULL,0),(242,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:34:41','admin','::1',0),(243,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:34:41','admin','::1',0),(244,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:42',NULL,NULL,0),(245,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:42',NULL,NULL,0),(246,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:43',NULL,NULL,0),(247,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:34:43',NULL,NULL,0),(248,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:36:42',NULL,NULL,0),(249,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:36:52',NULL,NULL,0),(250,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:36:52',NULL,NULL,0),(251,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:36:52',NULL,NULL,0),(252,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:36:53',NULL,NULL,0),(253,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:08',NULL,NULL,0),(254,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:08',NULL,NULL,0),(255,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:37:17','admin','::1',0),(256,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:20',NULL,NULL,0),(257,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:20',NULL,NULL,0),(258,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:20',NULL,NULL,0),(259,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:20',NULL,NULL,0),(260,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:20',NULL,NULL,0),(261,'error','Error al comprobar la tabla cheque_operacion','2020-03-31 16:37:21',NULL,NULL,0),(262,'error','Error al descargar la lista de plugins.','2020-03-31 16:43:53','admin','::1',0),(263,'error','Error al descargar la lista de plugins.','2020-03-31 16:43:57','admin','::1',0),(264,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:45:03','admin','::1',0),(265,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:45:03','admin','::1',0),(266,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:46:48','admin','::1',0),(267,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:46:49','admin','::1',0),(268,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:48:19','admin','::1',0),(269,'error','¡Imposible modificar la línea de la operacion !','2020-03-31 16:48:19','admin','::1',0),(270,'error','¡Imposible modificar la línea de la operacion 123456!','2020-03-31 16:48:54','admin','::1',0),(271,'error','¡Imposible modificar la línea de la operacion 1233!','2020-03-31 16:48:54','admin','::1',0),(272,'error','¡Imposible modificar la línea de la operacion 123456!','2020-03-31 16:49:24','admin','::1',0),(273,'error','¡Imposible modificar la línea de la operacion 1233!','2020-03-31 16:49:24','admin','::1',0),(274,'error','¡Imposible modificar la línea de la operacion 123456!','2020-03-31 16:50:28','admin','::1',0),(275,'error','¡Imposible modificar la línea de la operacion 1233!','2020-03-31 16:50:28','admin','::1',0),(276,'error','¡Imposible modificar la línea de la operacion 123456!','2020-03-31 16:51:17','admin','::1',0),(277,'error','¡Imposible modificar la línea de la operacion 1233!','2020-03-31 16:51:17','admin','::1',0),(278,'error','Error al descargar la lista de plugins.','2020-03-31 20:01:07','admin','::1',0),(279,'error','¡Imposible eliminar la operacion!','2020-03-31 20:16:55','admin','::1',0),(280,'error','Error al descargar la lista de plugins.','2020-03-31 20:20:35','admin','::1',0),(281,'error','Error al descargar la lista de plugins.','2020-03-31 20:20:36','admin','::1',0),(282,'error','Error al descargar la lista de plugins.','2020-03-31 21:34:33','admin','::1',0),(283,'error','Error al descargar la lista de plugins.','2020-03-31 21:34:35','admin','::1',0),(284,'error','Error al descargar la lista de plugins.','2020-03-31 21:48:58','admin','::1',0),(285,'error','Error al descargar la lista de plugins.','2020-03-31 22:40:44','admin','::1',0),(286,'error','ELIMINA RESPALDO.','2020-04-01 00:42:28','admin','::1',0),(287,'error','Error al descargar la lista de plugins.','2020-04-01 17:04:18','admin','::1',0),(288,'error','Error al descargar la lista de plugins.','2020-04-01 17:04:25','admin','::1',0),(289,'error','Error al descargar la lista de plugins.','2020-04-01 17:04:30','admin','::1',0),(290,'error','Error al descargar la lista de plugins.','2020-04-01 17:05:50','admin','::1',0),(291,'error','Error al descargar la lista de plugins.','2020-04-01 17:24:12','admin','::1',0),(292,'error','ELIMINA RESPALDO.','2020-04-01 17:24:32','admin','::1',0),(293,'error','Error al descargar la lista de plugins.','2020-04-01 18:14:57','admin','::1',0),(294,'error','Error al descargar la lista de plugins.','2020-04-01 19:53:41','admin','::1',0),(295,'error','Error al descargar la lista de plugins.','2020-04-01 21:02:09','admin','::1',0),(296,'error','Error al descargar la lista de plugins.','2020-04-01 21:02:13','admin','::1',0),(297,'error','Error al descargar la lista de plugins.','2020-04-01 21:08:31','admin','::1',0);
/*!40000 ALTER TABLE `fs_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_pages`
--

DROP TABLE IF EXISTS `fs_pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_pages` (
  `name` varchar(30) COLLATE utf8_bin NOT NULL,
  `title` varchar(40) COLLATE utf8_bin NOT NULL,
  `folder` varchar(15) COLLATE utf8_bin NOT NULL,
  `version` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `show_on_menu` tinyint(1) NOT NULL DEFAULT '1',
  `important` tinyint(1) NOT NULL DEFAULT '0',
  `orden` int NOT NULL DEFAULT '100',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_pages`
--

LOCK TABLES `fs_pages` WRITE;
/*!40000 ALTER TABLE `fs_pages` DISABLE KEYS */;
INSERT INTO `fs_pages` VALUES ('admin_agente','Empleado','admin','2017.031',0,0,100),('admin_agentes','Empleados','admin','2017.031',1,0,100),('admin_empresa','Empresa / web','admin','2017.031',1,0,100),('admin_home','Panel de control','admin',NULL,1,0,100),('admin_info','Información del sistema','admin','2017.031',1,0,100),('admin_orden_menu','Ordenar menú','admin','2017.031',1,0,100),('admin_rol','Editar rol','admin','2017.031',0,0,100),('admin_user','Usuario','admin','2017.031',0,0,100),('admin_users','Usuarios','admin','2017.031',1,0,100),('banco','Bancos','Definiciones','2017.031',1,0,100),('banco_edit','Editar Banco','admin','2017.031',0,0,100),('cheque','Cheques','Definiciones','2017.031',1,0,100),('cheque_edit','Editar Cheque','Definiciones','2017.031',0,0,100),('cliente','Clientes','Definiciones','2017.031',1,0,100),('cliente_edit','Editar Cliente','admin','2017.031',0,0,100),('estado','Estado','Definiciones','2017.031',1,0,100),('estado_edit','Editar Estado','','2017.031',1,0,100),('nueva_operacion','Nueva operación...','Movimientos','2017.031',0,1,100),('operaciones','Operaciones','Movimientos','2017.031',1,0,100),('operaciones_edit','Operaciones Edit','Movimientos','2017.031',0,0,100),('xml_import_export','Importar/exportar XML','admin','2017.031',1,0,100);
/*!40000 ALTER TABLE `fs_pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_roles`
--

DROP TABLE IF EXISTS `fs_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_roles` (
  `codrol` varchar(20) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(200) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`codrol`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_roles`
--

LOCK TABLES `fs_roles` WRITE;
/*!40000 ALTER TABLE `fs_roles` DISABLE KEYS */;
/*!40000 ALTER TABLE `fs_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_users`
--

DROP TABLE IF EXISTS `fs_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_users` (
  `nick` varchar(12) COLLATE utf8_bin NOT NULL,
  `password` varchar(100) COLLATE utf8_bin NOT NULL,
  `log_key` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `codagente` varchar(10) COLLATE utf8_bin DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `last_login_time` time DEFAULT NULL,
  `last_ip` varchar(40) COLLATE utf8_bin DEFAULT NULL,
  `last_browser` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `fs_page` varchar(30) COLLATE utf8_bin DEFAULT NULL,
  `css` varchar(255) COLLATE utf8_bin DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`nick`),
  KEY `ca_fs_users_pages` (`fs_page`),
  CONSTRAINT `ca_fs_users_pages` FOREIGN KEY (`fs_page`) REFERENCES `fs_pages` (`name`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_users`
--

LOCK TABLES `fs_users` WRITE;
/*!40000 ALTER TABLE `fs_users` DISABLE KEYS */;
INSERT INTO `fs_users` VALUES ('admin','d033e22ae348aeb5660fc2140aec35850c4da997','f85d7227f215921a54e38fce02771a3d73607058',1,1,'1','2020-04-01','18:19:44','::1','Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/80.0.3987.149 Safari/537.36','operaciones','view/css/bootstrap-yeti.min.css',NULL);
/*!40000 ALTER TABLE `fs_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fs_vars`
--

DROP TABLE IF EXISTS `fs_vars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `fs_vars` (
  `name` varchar(35) COLLATE utf8_bin NOT NULL,
  `varchar` text COLLATE utf8_bin,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fs_vars`
--

LOCK TABLES `fs_vars` WRITE;
/*!40000 ALTER TABLE `fs_vars` DISABLE KEYS */;
INSERT INTO `fs_vars` VALUES ('install_step','2'),('mail_bcc',''),('mail_enc','ssl'),('mail_firma','---\r\nEnviado con FacturaScripts'),('mail_host','smtp.gmail.com'),('mail_mailer','smtp'),('mail_password',''),('mail_port','465'),('mail_user',''),('updates','true');
/*!40000 ALTER TABLE `fs_vars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `operaciones`
--

DROP TABLE IF EXISTS `operaciones`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `operaciones` (
  `idoperaciones` int NOT NULL AUTO_INCREMENT,
  `fec_operacion` date NOT NULL,
  `idestado` int NOT NULL,
  `idcliente` int NOT NULL,
  `observacion` varchar(250) DEFAULT NULL,
  `ptefactura` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`idoperaciones`),
  KEY `fk_operaciones_estado_idx` (`idestado`),
  KEY `fk_operaciones_cliente_idx` (`idcliente`),
  CONSTRAINT `fk_operaciones_cliente` FOREIGN KEY (`idcliente`) REFERENCES `clientes` (`idcliente`) ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT `fk_operaciones_estado` FOREIGN KEY (`idestado`) REFERENCES `estado` (`idestado`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `operaciones`
--

LOCK TABLES `operaciones` WRITE;
/*!40000 ALTER TABLE `operaciones` DISABLE KEYS */;
INSERT INTO `operaciones` VALUES (9,'2020-03-30',1,3,'aaaaaa',0),(11,'2020-03-31',1,1,'nada',0),(12,'2020-03-31',2,1,'cccc',1);
/*!40000 ALTER TABLE `operaciones` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `paises`
--

DROP TABLE IF EXISTS `paises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paises` (
  `validarprov` tinyint(1) DEFAULT NULL,
  `codiso` varchar(2) COLLATE utf8_bin DEFAULT NULL,
  `bandera` text COLLATE utf8_bin,
  `nombre` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `codpais` varchar(20) COLLATE utf8_bin NOT NULL,
  PRIMARY KEY (`codpais`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paises`
--

LOCK TABLES `paises` WRITE;
/*!40000 ALTER TABLE `paises` DISABLE KEYS */;
INSERT INTO `paises` VALUES (NULL,'AW',NULL,'Aruba','ABW'),(NULL,'AF',NULL,'Afganistán','AFG'),(NULL,'AO',NULL,'Angola','AGO'),(NULL,'AI',NULL,'Anguila','AIA'),(NULL,'AX',NULL,'Islas Gland','ALA'),(NULL,'AL',NULL,'Albania','ALB'),(NULL,'AD',NULL,'Andorra','AND'),(NULL,'AN',NULL,'Antillas Holandesas','ANT'),(NULL,'AE',NULL,'Emiratos Árabes Unidos','ARE'),(NULL,'AR',NULL,'Argentina','ARG'),(NULL,'AM',NULL,'Armenia','ARM'),(NULL,'AS',NULL,'Samoa Americana','ASM'),(NULL,'AQ',NULL,'Antártida','ATA'),(NULL,'TF',NULL,'Territorios Australes Franceses','ATF'),(NULL,'AG',NULL,'Antigua y Barbuda','ATG'),(NULL,'AU',NULL,'Australia','AUS'),(NULL,'AT',NULL,'Austria','AUT'),(NULL,'AZ',NULL,'Azerbaiyán','AZE'),(NULL,'BI',NULL,'Burundi','BDI'),(NULL,'BE',NULL,'Bélgica','BEL'),(NULL,'BJ',NULL,'Benín','BEN'),(NULL,'BF',NULL,'Burkina Faso','BFA'),(NULL,'BD',NULL,'Bangladesh','BGD'),(NULL,'BG',NULL,'Bulgaria','BGR'),(NULL,'BH',NULL,'Bahréin','BHR'),(NULL,'BS',NULL,'Bahamas','BHS'),(NULL,'BA',NULL,'Bosnia y Herzegovina','BIH'),(NULL,'BY',NULL,'Bielorrusia','BLR'),(NULL,'BZ',NULL,'Belice','BLZ'),(NULL,'BM',NULL,'Bermudas','BMU'),(NULL,'BO',NULL,'Bolivia','BOL'),(NULL,'BR',NULL,'Brasil','BRA'),(NULL,'BB',NULL,'Barbados','BRB'),(NULL,'BN',NULL,'Brunéi','BRN'),(NULL,'BT',NULL,'Bhután','BTN'),(NULL,'BV',NULL,'Isla Bouvet','BVT'),(NULL,'BW',NULL,'Botsuana','BWA'),(NULL,'CF',NULL,'República Centroafricana','CAF'),(NULL,'CA',NULL,'Canadá','CAN'),(NULL,'CC',NULL,'Islas Cocos','CCK'),(NULL,'CH',NULL,'Suiza','CHE'),(NULL,'CL',NULL,'Chile','CHL'),(NULL,'CN',NULL,'China','CHN'),(NULL,'CI',NULL,'Costa de Marfil','CIV'),(NULL,'CM',NULL,'Camerún','CMR'),(NULL,'CD',NULL,'República Democrática del Congo','COD'),(NULL,'CG',NULL,'Congo','COG'),(NULL,'CK',NULL,'Islas Cook','COK'),(NULL,'CO',NULL,'Colombia','COL'),(NULL,'KM',NULL,'Comoras','COM'),(NULL,'CV',NULL,'Cabo Verde','CPV'),(NULL,'CR',NULL,'Costa Rica','CRI'),(NULL,'CU',NULL,'Cuba','CUB'),(NULL,'CX',NULL,'Isla de Navidad','CXR'),(NULL,'KY',NULL,'Islas Caimán','CYM'),(NULL,'CY',NULL,'Chipre','CYP'),(NULL,'CZ',NULL,'República Checa','CZE'),(NULL,'DE',NULL,'Alemania','DEU'),(NULL,'DJ',NULL,'Yibuti','DJI'),(NULL,'DM',NULL,'Dominica','DMA'),(NULL,'DK',NULL,'Dinamarca','DNK'),(NULL,'DO',NULL,'República Dominicana','DOM'),(NULL,'DZ',NULL,'Argelia','DZA'),(NULL,'EC',NULL,'Ecuador','ECU'),(NULL,'EG',NULL,'Egipto','EGY'),(NULL,'ER',NULL,'Eritrea','ERI'),(NULL,'EH',NULL,'Sahara Occidental','ESH'),(NULL,'ES',NULL,'España','ESP'),(NULL,'EE',NULL,'Estonia','EST'),(NULL,'ET',NULL,'Etiopía','ETH'),(NULL,'FI',NULL,'Finlandia','FIN'),(NULL,'FJ',NULL,'Fiyi','FJI'),(NULL,'FK',NULL,'Islas Malvinas','FLK'),(NULL,'FR',NULL,'Francia','FRA'),(NULL,'FO',NULL,'Islas Feroe','FRO'),(NULL,'FM',NULL,'Micronesia','FSM'),(NULL,'GA',NULL,'Gabón','GAB'),(NULL,'GB',NULL,'Reino Unido','GBR'),(NULL,'GE',NULL,'Georgia','GEO'),(NULL,'GH',NULL,'Ghana','GHA'),(NULL,'GI',NULL,'Gibraltar','GIB'),(NULL,'GN',NULL,'Guinea','GIN'),(NULL,'GP',NULL,'Guadalupe','GLP'),(NULL,'GM',NULL,'Gambia','GMB'),(NULL,'GW',NULL,'Guinea-Bissau','GNB'),(NULL,'GQ',NULL,'Guinea Ecuatorial','GNQ'),(NULL,'GR',NULL,'Grecia','GRC'),(NULL,'GD',NULL,'Granada','GRD'),(NULL,'GL',NULL,'Groenlandia','GRL'),(NULL,'GT',NULL,'Guatemala','GTM'),(NULL,'GF',NULL,'Guayana Francesa','GUF'),(NULL,'GU',NULL,'Guam','GUM'),(NULL,'GY',NULL,'Guyana','GUY'),(NULL,'HK',NULL,'Hong Kong','HKG'),(NULL,'HM',NULL,'Islas Heard y McDonald','HMD'),(NULL,'HN',NULL,'Honduras','HND'),(NULL,'HR',NULL,'Croacia','HRV'),(NULL,'HT',NULL,'Haití','HTI'),(NULL,'HU',NULL,'Hungría','HUN'),(NULL,'ID',NULL,'Indonesia','IDN'),(NULL,'IN',NULL,'India','IND'),(NULL,'IO',NULL,'Territorio Británico del Océano Índico','IOT'),(NULL,'IE',NULL,'Irlanda','IRL'),(NULL,'IR',NULL,'Irán','IRN'),(NULL,'IQ',NULL,'Iraq','IRQ'),(NULL,'IS',NULL,'Islandia','ISL'),(NULL,'IL',NULL,'Israel','ISR'),(NULL,'IT',NULL,'Italia','ITA'),(NULL,'JM',NULL,'Jamaica','JAM'),(NULL,'JO',NULL,'Jordania','JOR'),(NULL,'JP',NULL,'Japón','JPN'),(NULL,'KZ',NULL,'Kazajstán','KAZ'),(NULL,'KE',NULL,'Kenia','KEN'),(NULL,'KG',NULL,'Kirguistán','KGZ'),(NULL,'KH',NULL,'Camboya','KHM'),(NULL,'KI',NULL,'Kiribati','KIR'),(NULL,'KN',NULL,'San Cristóbal y Nieves','KNA'),(NULL,'KR',NULL,'Corea del Sur','KOR'),(NULL,'KW',NULL,'Kuwait','KWT'),(NULL,'LA',NULL,'Laos','LAO'),(NULL,'LB',NULL,'Líbano','LBN'),(NULL,'LR',NULL,'Liberia','LBR'),(NULL,'LY',NULL,'Libia','LBY'),(NULL,'LC',NULL,'Santa Lucía','LCA'),(NULL,'LI',NULL,'Liechtenstein','LIE'),(NULL,'LK',NULL,'Sri Lanka','LKA'),(NULL,'LS',NULL,'Lesotho','LSO'),(NULL,'LT',NULL,'Lituania','LTU'),(NULL,'LU',NULL,'Luxemburgo','LUX'),(NULL,'LV',NULL,'Letonia','LVA'),(NULL,'MO',NULL,'Macao','MAC'),(NULL,'MA',NULL,'Marruecos','MAR'),(NULL,'MC',NULL,'Mónaco','MCO'),(NULL,'MD',NULL,'Moldavia','MDA'),(NULL,'MG',NULL,'Madagascar','MDG'),(NULL,'MV',NULL,'Maldivas','MDV'),(NULL,'MX',NULL,'México','MEX'),(NULL,'MH',NULL,'Islas Marshall','MHL'),(NULL,'MK',NULL,'Macedonia','MKD'),(NULL,'ML',NULL,'Malí','MLI'),(NULL,'MT',NULL,'Malta','MLT'),(NULL,'MM',NULL,'Myanmar','MMR'),(NULL,'ME',NULL,'Montenegro','MNE'),(NULL,'MN',NULL,'Mongolia','MNG'),(NULL,'MP',NULL,'Islas Marianas del Norte','MNP'),(NULL,'MZ',NULL,'Mozambique','MOZ'),(NULL,'MR',NULL,'Mauritania','MRT'),(NULL,'MS',NULL,'Montserrat','MSR'),(NULL,'MQ',NULL,'Martinica','MTQ'),(NULL,'MU',NULL,'Mauricio','MUS'),(NULL,'MW',NULL,'Malaui','MWI'),(NULL,'MY',NULL,'Malasia','MYS'),(NULL,'YT',NULL,'Mayotte','MYT'),(NULL,'NA',NULL,'Namibia','NAM'),(NULL,'NC',NULL,'Nueva Caledonia','NCL'),(NULL,'NE',NULL,'Níger','NER'),(NULL,'NF',NULL,'Isla Norfolk','NFK'),(NULL,'NG',NULL,'Nigeria','NGA'),(NULL,'NI',NULL,'Nicaragua','NIC'),(NULL,'NU',NULL,'Niue','NIU'),(NULL,'NL',NULL,'Países Bajos','NLD'),(NULL,'NO',NULL,'Noruega','NOR'),(NULL,'NP',NULL,'Nepal','NPL'),(NULL,'NR',NULL,'Nauru','NRU'),(NULL,'NZ',NULL,'Nueva Zelanda','NZL'),(NULL,'OM',NULL,'Omán','OMN'),(NULL,'PK',NULL,'Pakistán','PAK'),(NULL,'PA',NULL,'Panamá','PAN'),(NULL,'PN',NULL,'Islas Pitcairn','PCN'),(NULL,'PE',NULL,'Perú','PER'),(NULL,'PH',NULL,'Filipinas','PHL'),(NULL,'PW',NULL,'Palaos','PLW'),(NULL,'PG',NULL,'Papúa Nueva Guinea','PNG'),(NULL,'PL',NULL,'Polonia','POL'),(NULL,'PR',NULL,'Puerto Rico','PRI'),(NULL,'KP',NULL,'Corea del Norte','PRK'),(NULL,'PT',NULL,'Portugal','PRT'),(NULL,'PY',NULL,'Paraguay','PRY'),(NULL,'PS',NULL,'Palestina','PSE'),(NULL,'PF',NULL,'Polinesia Francesa','PYF'),(NULL,'QA',NULL,'Qatar','QAT'),(NULL,'RE',NULL,'Reunión','REU'),(NULL,'RO',NULL,'Rumania','ROU'),(NULL,'RU',NULL,'Rusia','RUS'),(NULL,'RW',NULL,'Ruanda','RWA'),(NULL,'SA',NULL,'Arabia Saudí','SAU'),(NULL,'SD',NULL,'Sudán','SDN'),(NULL,'SN',NULL,'Senegal','SEN'),(NULL,'SG',NULL,'Singapur','SGP'),(NULL,'GS',NULL,'Islas Georgias del Sur y Sandwich del Sur','SGS'),(NULL,'SH',NULL,'Santa Helena','SHN'),(NULL,'SJ',NULL,'Svalbard y Jan Mayen','SJM'),(NULL,'SB',NULL,'Islas Salomón','SLB'),(NULL,'SL',NULL,'Sierra Leona','SLE'),(NULL,'SV',NULL,'El Salvador','SLV'),(NULL,'SM',NULL,'San Marino','SMR'),(NULL,'SO',NULL,'Somalia','SOM'),(NULL,'PM',NULL,'San Pedro y Miquelón','SPM'),(NULL,'RS',NULL,'Serbia','SRB'),(NULL,'ST',NULL,'Santo Tomé y Príncipe','STP'),(NULL,'SR',NULL,'Surinam','SUR'),(NULL,'SK',NULL,'Eslovaquia','SVK'),(NULL,'SI',NULL,'Eslovenia','SVN'),(NULL,'SE',NULL,'Suecia','SWE'),(NULL,'SZ',NULL,'Suazilandia','SWZ'),(NULL,'SC',NULL,'Seychelles','SYC'),(NULL,'SY',NULL,'Siria','SYR'),(NULL,'TC',NULL,'Islas Turcas y Caicos','TCA'),(NULL,'TD',NULL,'Chad','TCD'),(NULL,'TG',NULL,'Togo','TGO'),(NULL,'TH',NULL,'Tailandia','THA'),(NULL,'TJ',NULL,'Tayikistán','TJK'),(NULL,'TK',NULL,'Tokelau','TKL'),(NULL,'TM',NULL,'Turkmenistán','TKM'),(NULL,'TL',NULL,'Timor Oriental','TLS'),(NULL,'TO',NULL,'Tonga','TON'),(NULL,'TT',NULL,'Trinidad y Tobago','TTO'),(NULL,'TN',NULL,'Túnez','TUN'),(NULL,'TR',NULL,'Turquía','TUR'),(NULL,'TV',NULL,'Tuvalu','TUV'),(NULL,'TW',NULL,'Taiwán','TWN'),(NULL,'TZ',NULL,'Tanzania','TZA'),(NULL,'UG',NULL,'Uganda','UGA'),(NULL,'UA',NULL,'Ucrania','UKR'),(NULL,'UM',NULL,'Islas Ultramarinas de Estados Unidos','UMI'),(NULL,'UY',NULL,'Uruguay','URY'),(NULL,'US',NULL,'Estados Unidos','USA'),(NULL,'UZ',NULL,'Uzbekistán','UZB'),(NULL,'VA',NULL,'Ciudad del Vaticano','VAT'),(NULL,'VC',NULL,'San Vicente y las Granadinas','VCT'),(NULL,'VE',NULL,'Venezuela','VEN'),(NULL,'VG',NULL,'Islas Vírgenes Británicas','VGB'),(NULL,'VI',NULL,'Islas Vírgenes de los Estados Unidos','VIR'),(NULL,'VN',NULL,'Vietnam','VNM'),(NULL,'VU',NULL,'Vanuatu','VUT'),(NULL,'WF',NULL,'Wallis y Futuna','WLF'),(NULL,'WS',NULL,'Samoa','WSM'),(NULL,'YE',NULL,'Yemen','YEM'),(NULL,'ZA',NULL,'Sudáfrica','ZAF'),(NULL,'ZM',NULL,'Zambia','ZMB'),(NULL,'ZW',NULL,'Zimbabue','ZWE');
/*!40000 ALTER TABLE `paises` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `respaldos`
--

DROP TABLE IF EXISTS `respaldos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `respaldos` (
  `idrespaldo` int NOT NULL AUTO_INCREMENT,
  `idoperacion` int NOT NULL,
  `nrocheque` int NOT NULL,
  `idbanco` int NOT NULL,
  `importe` double NOT NULL DEFAULT '0',
  PRIMARY KEY (`idrespaldo`),
  KEY `fk_respaldos_cheques1_idx` (`nrocheque`,`idbanco`),
  CONSTRAINT `fk_respaldos_cheques1` FOREIGN KEY (`nrocheque`, `idbanco`) REFERENCES `cheques` (`nrocheque`, `idbanco`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `respaldos`
--

LOCK TABLES `respaldos` WRITE;
/*!40000 ALTER TABLE `respaldos` DISABLE KEYS */;
INSERT INTO `respaldos` VALUES (1,9,1233,1,500000),(2,9,123456,3,1500000),(11,11,123456,3,500000),(12,11,123456,3,40000);
/*!40000 ALTER TABLE `respaldos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `series`
--

DROP TABLE IF EXISTS `series`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `series` (
  `irpf` double DEFAULT NULL,
  `idcuenta` int DEFAULT NULL,
  `codserie` varchar(2) COLLATE utf8_bin NOT NULL,
  `descripcion` varchar(100) COLLATE utf8_bin DEFAULT NULL,
  `siniva` tinyint(1) DEFAULT NULL,
  `codcuenta` varchar(6) COLLATE utf8_bin DEFAULT NULL,
  `codejercicio` varchar(4) COLLATE utf8_bin DEFAULT NULL,
  `numfactura` int DEFAULT '1',
  PRIMARY KEY (`codserie`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `series`
--

LOCK TABLES `series` WRITE;
/*!40000 ALTER TABLE `series` DISABLE KEYS */;
INSERT INTO `series` VALUES (0,NULL,'A','SERIE A',0,NULL,NULL,1),(0,NULL,'R','RECTIFICATIVAS',0,NULL,NULL,1);
/*!40000 ALTER TABLE `series` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2020-04-01 13:26:40
