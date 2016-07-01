/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 10.1.8-MariaDB : Database - abs_consultor
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`abs_consultor` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */;

USE `abs_consultor`;

/*Table structure for table `blog_categories` */

DROP TABLE IF EXISTS `blog_categories`;

CREATE TABLE `blog_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories` */

insert  into `blog_categories`(`id`,`key`,`created_at`,`updated_at`,`deleted_at`,`active`) values (1,'Mi cat','2016-05-29 20:28:00','2016-07-01 14:13:22',NULL,'1');

/*Table structure for table `blog_categories_locales` */

DROP TABLE IF EXISTS `blog_categories_locales`;

CREATE TABLE `blog_categories_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories_locales` */

insert  into `blog_categories_locales`(`id`,`related_table_id`,`title`,`url_key`,`meta_description`,`language_id`) values (1,1,'11231313','jo-jo-jo','',1),(2,1,'12321323','jo-jo-jo','213',2),(3,1,'23423423424','','23342',3),(4,1,'3424242','','4322',4),(5,1,'13','','34543535',5);

/*Table structure for table `blog_entries` */

DROP TABLE IF EXISTS `blog_entries`;

CREATE TABLE `blog_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_categories_id` int(10) unsigned NOT NULL,
  `key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries` */

insert  into `blog_entries`(`id`,`blog_categories_id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,1,'MI blog','0','2016-05-29 21:20:18',NULL,NULL),(7,1,'jjjjj','0','2016-05-31 13:10:56',NULL,NULL),(8,1,'sss','0','2016-06-01 11:07:26',NULL,NULL),(9,1,'ssss','0','2016-06-01 13:00:47',NULL,NULL),(10,1,'caca','0','2016-06-01 16:52:51',NULL,NULL),(11,1,'qqqqqqqqqqqqqqqq','0','2016-06-02 16:56:20',NULL,NULL);

/*Table structure for table `blog_entries_locales` */

DROP TABLE IF EXISTS `blog_entries_locales`;

CREATE TABLE `blog_entries_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries_locales` */

insert  into `blog_entries_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'pepe11111112222','asdfna-sdfnadsk-fjnasl-dfjdnlas-fkj','<p>Contenido en ingles</p>\r\n','',1),(2,1,'juan','tuuuso-que-pasa-contigo','<p>ssss</p>\r\n','',2),(3,1,'Venga, que estamos que lo tiramos','','<p>contenidoooorrrr</p>\r\n','fff\r\n',3),(4,1,'que tal','','','',4),(5,1,'estamos','','<p>ASS</p>\r\n','',5),(6,9,'www','','ww','wwww',2);

/*Table structure for table `estadisticaslogins` */

DROP TABLE IF EXISTS `estadisticaslogins`;

CREATE TABLE `estadisticaslogins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `idusuario` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `ip` varchar(15) DEFAULT NULL,
  `correcto` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `estadisticaslogins` */

/*Table structure for table `gestor_controlador` */

DROP TABLE IF EXISTS `gestor_controlador`;

CREATE TABLE `gestor_controlador` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_zend` varchar(255) NOT NULL,
  `nombre_usable` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`nombre_zend`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_controlador` */

insert  into `gestor_controlador`(`id`,`nombre_zend`,`nombre_usable`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','Usuarios del gestor'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'controller','Controladores');

/*Table structure for table `gestor_menu` */

DROP TABLE IF EXISTS `gestor_menu`;

CREATE TABLE `gestor_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gestor_module_id` int(11) unsigned DEFAULT NULL,
  `padre` int(11) DEFAULT NULL,
  `texto` varchar(255) DEFAULT NULL,
  `accion` varchar(255) DEFAULT NULL,
  `orden` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=219 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_menu` */

insert  into `gestor_menu`(`id`,`gestor_module_id`,`padre`,`texto`,`accion`,`orden`) values (2,0,0,'Configuración','',90),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',2),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',1),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',1),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',1),(210,0,0,'Categorías de Blog','',1),(211,23,210,'Listado','index',1),(214,1,0,'Home','index',0),(215,24,0,'Media','index',0),(216,26,0,'Megabanners','index',0),(217,23,207,'Categorías','index',0),(218,27,0,'Páginas Estáticas','index',0);

/*Table structure for table `gestor_modules` */

DROP TABLE IF EXISTS `gestor_modules`;

CREATE TABLE `gestor_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_zend` varchar(255) NOT NULL,
  `nombre_usable` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`nombre_zend`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_modules` */

insert  into `gestor_modules`(`id`,`nombre_zend`,`nombre_usable`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page');

/*Table structure for table `gestor_perfiles` */

DROP TABLE IF EXISTS `gestor_perfiles`;

CREATE TABLE `gestor_perfiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `es_admin` tinyint(4) DEFAULT '0',
  `permisos` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_perfiles` */

insert  into `gestor_perfiles`(`id`,`nombre`,`descripcion`,`es_admin`,`permisos`) values (1,'Superadmin','Administrador de la plataforma',1,'[]'),(2,'Coordinador','Usuario normal de la plataforma',0,'[\"home.index\",\"configuration.index\",\"user.index\",\"profile.add\",\"profile.delete\",\"menu.index\"]'),(3,'Director de Relacion','descripcion',0,'[\"user.edit\",\"menu.index\",\"menu.edit\"]'),(4,'Administrador','Administrador',1,'[\"user.index\",\"profile.add\",\"menu.add\",\"menu.saveOrder\",\"menu.delete\"]');

/*Table structure for table `gestor_permisos` */

DROP TABLE IF EXISTS `gestor_permisos`;

CREATE TABLE `gestor_permisos` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gestor_perfil_id` int(11) unsigned NOT NULL,
  `gestor_controlador_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=154 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_permisos` */

insert  into `gestor_permisos`(`id`,`gestor_perfil_id`,`gestor_controlador_id`) values (152,4,1),(153,2,1);

/*Table structure for table `gestor_usuarios` */

DROP TABLE IF EXISTS `gestor_usuarios`;

CREATE TABLE `gestor_usuarios` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `gestor_perfil_id` int(11) unsigned NOT NULL,
  `login` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `validado` tinyint(4) DEFAULT '0',
  `active` enum('0','1') DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB AUTO_INCREMENT=3809 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_usuarios` */

insert  into `gestor_usuarios`(`id`,`gestor_perfil_id`,`login`,`password`,`validado`,`active`,`created_at`,`updated_at`,`deleted_at`,`last_login`) values (1,1,'dreamsite','e10adc3949ba59abbe56e057f20f883e',1,'1','2016-01-13 12:42:11',NULL,NULL,'2016-04-22 09:33:56'),(3,4,'entropy','698d51a19d8a121ce581499d7b701668',1,'1','2016-03-29 12:05:16',NULL,NULL,'2016-04-18 17:45:34'),(4,4,'bbvaaaar','8f14e45fceea167a5a36dedd4bea2543',1,'1','2016-03-30 18:13:54',NULL,NULL,'2016-04-13 17:18:48'),(3797,3,'U502562','e10adc3949ba59abbe56e057f20f883e',1,'1','2016-03-29 12:29:19',NULL,NULL,'2016-04-19 14:34:46'),(3799,2,'usercitot','310dcbbf4cce62f762a2aaa148d556bd',1,'1','2016-04-21 11:54:07',NULL,NULL,'2016-04-21 16:55:16'),(3806,1,'tuuuu','e10adc3949ba59abbe56e057f20f883e',0,'1','2016-05-28 18:33:38',NULL,NULL,NULL),(3807,1,'megauser4444','00b7691d86d96aebd21dd9e138f90840',0,'1','0000-00-00 00:00:00',NULL,NULL,'0000-00-00 00:00:00'),(3808,10,'caca','0aeb773c5cebbcb9c04213b978490cdf',0,'0','2016-06-01 11:18:02',NULL,NULL,NULL);

/*Table structure for table `historico_login` */

DROP TABLE IF EXISTS `historico_login`;

CREATE TABLE `historico_login` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `id_usuario` int(11) NOT NULL,
  `id_perfil` int(10) unsigned NOT NULL,
  `ip` varchar(15) NOT NULL,
  `fecha` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=402 DEFAULT CHARSET=utf8;

/*Data for the table `historico_login` */

insert  into `historico_login`(`id`,`id_usuario`,`id_perfil`,`ip`,`fecha`) values (108,1,1,'213.37.1.91','2016-03-29 13:37:12'),(109,3,4,'213.37.1.91','2016-03-29 14:03:40'),(110,1,1,'213.37.1.91','2016-03-29 14:33:11'),(111,3797,3,'213.37.1.91','2016-03-29 14:35:25'),(112,4336,2,'213.37.1.91','2016-03-29 14:54:02'),(113,3797,3,'213.37.1.91','2016-03-29 15:07:52'),(114,1,1,'213.37.1.91','2016-03-29 16:09:33'),(115,1,1,'213.37.1.91','2016-03-29 16:41:42'),(116,3797,3,'213.37.1.91','2016-03-29 16:48:03'),(117,1,1,'213.37.1.91','2016-03-29 17:12:10'),(118,3,4,'213.37.1.91','2016-03-29 17:12:44'),(119,3797,3,'213.37.1.91','2016-03-29 17:45:40'),(120,1,1,'213.37.1.91','2016-03-29 18:18:19'),(121,3,4,'213.37.1.91','2016-03-29 18:18:51'),(122,1,1,'213.37.1.91','2016-03-30 09:58:55'),(123,3797,3,'213.37.1.91','2016-03-30 09:59:48'),(124,3797,3,'213.37.1.91','2016-03-30 10:00:21'),(125,3797,3,'213.37.1.91','2016-03-30 10:00:52'),(126,1,1,'213.37.1.91','2016-03-30 10:45:44'),(127,2,2,'213.37.1.91','2016-03-30 11:37:22'),(128,1,1,'213.37.1.91','2016-03-30 12:52:07'),(129,3,4,'213.37.1.91','2016-03-30 12:54:04'),(130,3797,3,'213.37.1.91','2016-03-30 17:14:18'),(131,3797,3,'213.37.1.91','2016-03-30 17:23:18'),(132,3,4,'80.28.203.156','2016-03-30 17:39:39'),(133,3,4,'80.28.203.156','2016-03-30 17:43:17'),(134,3,4,'213.60.52.236','2016-03-30 17:44:23'),(135,3,4,'80.28.203.156','2016-03-30 17:50:41'),(136,1,1,'213.37.1.91','2016-03-30 18:01:46'),(137,4,4,'213.37.1.91','2016-03-30 18:14:57'),(138,4,4,'213.37.1.91','2016-03-30 18:22:21'),(139,3,4,'213.60.52.236','2016-03-30 18:25:34'),(140,4339,2,'213.60.52.236','2016-03-30 18:28:56'),(141,3,4,'213.60.52.236','2016-03-31 09:17:56'),(142,4339,2,'213.60.52.236','2016-03-31 09:18:17'),(143,4,4,'213.60.52.236','2016-03-31 09:49:42'),(144,4,4,'80.28.203.156','2016-03-31 10:07:44'),(145,4,4,'80.28.203.156','2016-03-31 10:34:26'),(146,3,4,'80.28.203.156','2016-03-31 10:40:55'),(147,3,4,'80.28.203.156','2016-03-31 10:47:59'),(148,3,4,'213.60.52.236','2016-03-31 11:05:50'),(149,4339,2,'213.60.52.236','2016-03-31 11:16:05'),(150,3,4,'80.28.203.156','2016-03-31 11:17:02'),(151,4339,2,'80.28.203.156','2016-03-31 11:17:18'),(152,3,4,'80.28.203.156','2016-03-31 11:18:47'),(153,4343,2,'80.28.203.156','2016-03-31 11:20:36'),(154,3,4,'80.28.203.156','2016-03-31 11:21:04'),(155,3,4,'213.60.52.236','2016-03-31 11:21:12'),(156,4338,2,'213.60.52.236','2016-03-31 11:21:38'),(157,3,4,'80.28.203.156','2016-03-31 11:21:45'),(158,1,1,'213.37.1.91','2016-03-31 11:57:29'),(159,4339,2,'213.37.1.91','2016-03-31 11:58:45'),(160,4339,2,'213.37.1.91','2016-03-31 11:59:45'),(161,4339,2,'213.37.1.91','2016-03-31 11:59:53'),(162,4339,2,'213.37.1.91','2016-03-31 12:00:06'),(163,4338,2,'213.60.52.236','2016-03-31 12:00:50'),(164,4344,2,'213.37.1.91','2016-03-31 12:01:44'),(165,4344,2,'213.60.52.236','2016-03-31 12:01:55'),(166,3797,3,'213.37.1.91','2016-03-31 12:03:02'),(167,3,4,'213.60.52.236','2016-03-31 12:03:38'),(168,3797,3,'213.60.52.236','2016-03-31 12:04:10'),(169,4345,2,'213.37.1.91','2016-03-31 12:05:47'),(170,4,4,'89.107.180.34','2016-03-31 12:09:32'),(171,1,1,'213.37.1.91','2016-03-31 12:25:13'),(172,4347,3,'213.37.1.91','2016-03-31 12:26:25'),(173,1,1,'213.37.1.91','2016-03-31 12:45:46'),(174,4,4,'80.28.203.156','2016-03-31 13:09:26'),(175,4340,2,'80.28.203.156','2016-03-31 13:27:17'),(176,3,4,'213.60.52.236','2016-03-31 13:42:15'),(177,3,4,'213.60.52.236','2016-03-31 13:43:15'),(178,3797,3,'213.60.52.236','2016-03-31 13:46:41'),(179,3,4,'80.28.203.156','2016-03-31 13:51:59'),(180,3797,3,'80.28.203.156','2016-03-31 13:52:09'),(181,3797,3,'80.28.203.156','2016-03-31 13:52:10'),(182,3,4,'80.28.203.156','2016-03-31 13:54:23'),(183,3797,3,'80.28.203.156','2016-03-31 13:56:52'),(184,3797,3,'89.107.180.34','2016-03-31 14:04:32'),(185,1,1,'213.37.1.91','2016-03-31 14:41:17'),(186,3,4,'213.60.52.236','2016-03-31 15:12:46'),(187,4,4,'89.107.180.35','2016-03-31 15:20:46'),(188,3,4,'213.60.52.236','2016-03-31 15:24:06'),(189,4339,3,'213.60.52.236','2016-03-31 15:24:53'),(190,3,4,'213.60.52.236','2016-03-31 15:37:28'),(191,1,1,'213.37.1.91','2016-03-31 15:48:47'),(192,4338,2,'213.60.52.236','2016-03-31 15:54:01'),(193,4339,3,'213.60.52.236','2016-03-31 15:54:19'),(194,4339,3,'213.60.52.236','2016-03-31 16:04:51'),(195,4339,3,'89.107.180.75','2016-03-31 16:41:24'),(196,3,4,'213.60.52.236','2016-03-31 17:42:08'),(197,1,1,'213.37.1.91','2016-03-31 18:20:51'),(198,4339,3,'213.60.52.236','2016-04-01 08:29:20'),(199,3,4,'213.60.52.236','2016-04-01 08:29:49'),(200,1,1,'213.37.1.91','2016-04-01 09:19:31'),(201,4,4,'89.107.180.75','2016-04-01 13:07:59'),(202,3,4,'213.60.52.236','2016-04-04 10:09:36'),(203,1,1,'66.249.93.232','2016-04-04 11:02:06'),(204,4339,3,'66.249.93.232','2016-04-04 11:07:01'),(205,4339,3,'213.60.52.236','2016-04-04 12:37:37'),(206,4339,3,'213.60.52.236','2016-04-04 12:42:52'),(207,3,4,'213.60.52.236','2016-04-04 13:36:33'),(208,3,4,'80.28.203.156','2016-04-04 13:44:49'),(209,4339,3,'213.37.1.91','2016-04-04 13:55:16'),(210,3,4,'213.60.52.236','2016-04-04 15:18:56'),(211,3,4,'80.28.203.156','2016-04-04 17:27:41'),(212,3,4,'213.60.52.236','2016-04-04 17:36:40'),(213,4339,3,'213.60.52.236','2016-04-04 18:17:04'),(214,4339,3,'213.37.1.91','2016-04-04 18:17:20'),(215,3,4,'213.60.52.236','2016-04-04 19:03:17'),(216,4339,3,'213.60.52.236','2016-04-04 19:19:34'),(217,1,1,'213.37.1.91','2016-04-05 09:35:36'),(218,1,1,'213.37.1.91','2016-04-05 09:38:30'),(219,3,4,'213.37.1.91','2016-04-05 10:04:47'),(220,1,1,'213.37.1.91','2016-04-05 10:32:00'),(221,1,1,'213.37.1.91','2016-04-05 10:35:22'),(222,1,1,'213.37.1.91','2016-04-05 11:00:15'),(223,2,2,'213.37.1.91','2016-04-05 11:00:53'),(224,1,1,'213.37.1.91','2016-04-05 11:08:03'),(225,1,1,'213.37.1.91','2016-04-05 11:43:54'),(226,4344,3,'213.37.1.91','2016-04-05 11:44:31'),(227,4344,3,'213.37.1.91','2016-04-05 11:44:42'),(228,4345,3,'213.37.1.91','2016-04-05 11:45:34'),(229,4342,3,'213.37.1.91','2016-04-05 11:46:02'),(230,1,1,'213.37.1.91','2016-04-05 11:52:56'),(231,1,1,'213.37.1.91','2016-04-05 13:41:13'),(232,1,1,'213.37.1.91','2016-04-05 13:44:06'),(233,1,1,'213.37.1.91','2016-04-05 14:32:50'),(234,1,1,'213.37.1.91','2016-04-05 14:54:21'),(235,1,1,'213.37.1.91','2016-04-05 14:54:56'),(236,4352,3,'213.37.1.91','2016-04-05 14:59:37'),(237,1,1,'213.37.1.91','2016-04-05 15:36:13'),(238,1,1,'213.37.1.91','2016-04-05 15:37:17'),(239,1,1,'213.37.1.91','2016-04-05 16:19:52'),(240,1,1,'213.37.1.91','2016-04-05 16:27:03'),(241,4353,3,'213.37.1.91','2016-04-05 16:28:40'),(242,4353,3,'213.37.1.91','2016-04-05 17:05:37'),(243,4,4,'213.60.52.236','2016-04-05 17:23:16'),(244,4339,3,'213.60.52.236','2016-04-05 17:23:58'),(245,3,4,'80.28.203.156','2016-04-05 17:26:05'),(246,4,4,'80.28.203.156','2016-04-05 17:44:21'),(247,4,4,'89.107.180.34','2016-04-05 18:46:56'),(248,3,4,'213.60.52.236','2016-04-06 09:07:01'),(249,4,4,'89.107.180.35','2016-04-06 09:07:36'),(250,4355,2,'213.60.52.236','2016-04-06 09:10:11'),(251,3,4,'213.60.52.236','2016-04-06 09:16:53'),(252,1,1,'213.37.1.91','2016-04-06 09:31:35'),(253,4357,3,'89.107.180.35','2016-04-06 09:39:54'),(254,4,4,'89.107.180.35','2016-04-06 10:12:15'),(255,3,4,'213.60.52.236','2016-04-06 10:36:09'),(256,4357,3,'213.60.52.236','2016-04-06 10:37:21'),(257,4357,3,'213.60.52.236','2016-04-06 10:38:27'),(258,4355,2,'213.60.52.236','2016-04-06 10:40:27'),(259,4357,3,'89.107.180.35','2016-04-06 10:50:11'),(260,4357,3,'213.60.52.236','2016-04-06 10:57:06'),(261,1,1,'213.37.1.91','2016-04-06 11:20:51'),(262,4344,3,'213.37.1.91','2016-04-06 11:21:09'),(263,4344,3,'213.37.1.91','2016-04-06 11:21:16'),(264,4344,3,'213.37.1.91','2016-04-06 11:21:27'),(265,4344,3,'213.37.1.91','2016-04-06 11:21:49'),(266,4344,3,'213.37.1.91','2016-04-06 11:37:17'),(267,4357,3,'89.107.180.35','2016-04-06 12:02:03'),(268,4355,2,'213.60.52.236','2016-04-06 12:15:02'),(269,3797,3,'213.60.52.236','2016-04-06 12:17:37'),(270,3,4,'213.60.52.236','2016-04-06 12:18:54'),(271,1,1,'213.37.1.91','2016-04-06 12:46:49'),(272,4353,3,'213.37.1.91','2016-04-06 12:47:26'),(273,4357,3,'213.60.52.236','2016-04-06 13:02:41'),(274,4357,3,'213.60.52.236','2016-04-06 13:27:31'),(275,4353,3,'213.37.1.91','2016-04-06 14:47:04'),(276,4357,3,'213.60.52.236','2016-04-06 15:12:57'),(277,4357,3,'89.107.180.35','2016-04-06 15:22:54'),(278,4357,3,'213.60.52.236','2016-04-06 15:39:32'),(279,4353,3,'213.37.1.91','2016-04-06 18:01:42'),(280,3,4,'213.60.52.236','2016-04-06 18:11:12'),(281,1,1,'213.37.1.91','2016-04-06 18:18:48'),(282,3,4,'213.60.52.236','2016-04-07 09:05:02'),(283,4357,3,'213.60.52.236','2016-04-07 09:05:45'),(284,3,4,'213.60.52.236','2016-04-07 09:08:45'),(285,4357,3,'213.60.52.236','2016-04-07 09:11:13'),(286,3,4,'213.60.52.236','2016-04-07 09:39:48'),(287,4357,3,'213.60.52.236','2016-04-07 09:40:45'),(288,4357,3,'213.60.52.236','2016-04-07 10:21:48'),(289,3,4,'213.60.52.236','2016-04-07 10:31:18'),(290,3,4,'80.28.203.156','2016-04-07 10:35:52'),(291,3,4,'80.28.203.156','2016-04-07 12:02:54'),(292,4357,3,'213.60.52.236','2016-04-07 12:13:08'),(293,3,4,'213.60.52.236','2016-04-07 13:30:38'),(294,4355,2,'213.60.52.236','2016-04-07 13:34:16'),(295,4355,2,'213.60.52.236','2016-04-07 15:20:21'),(296,4357,3,'89.107.180.34','2016-04-07 16:39:57'),(297,1,1,'213.37.1.91','2016-04-07 16:46:51'),(298,1,1,'213.37.1.91','2016-04-07 16:59:53'),(299,3,4,'213.60.52.236','2016-04-07 17:43:24'),(300,4357,3,'213.60.52.236','2016-04-07 17:45:22'),(301,4355,2,'213.60.52.236','2016-04-07 17:46:13'),(302,1,1,'213.37.1.91','2016-04-07 17:58:27'),(303,1,1,'213.37.1.91','2016-04-07 18:13:34'),(304,4342,3,'213.37.1.91','2016-04-07 18:15:15'),(305,1,1,'213.37.1.91','2016-04-07 19:03:06'),(306,1,1,'84.77.29.237','2016-04-07 19:29:24'),(307,1,1,'213.37.1.91','2016-04-08 09:13:59'),(308,3,4,'80.28.203.156','2016-04-08 09:19:34'),(309,1,1,'213.37.1.91','2016-04-08 09:22:35'),(310,4,4,'89.107.180.35','2016-04-08 11:35:51'),(311,4,4,'89.107.180.75','2016-04-08 15:08:58'),(312,4357,3,'89.107.180.75','2016-04-08 15:41:52'),(313,4357,3,'83.43.70.203','2016-04-10 11:14:32'),(314,4357,3,'83.43.70.203','2016-04-10 11:35:27'),(315,1,1,'213.37.1.91','2016-04-11 09:42:24'),(316,4355,2,'213.60.52.236','2016-04-11 09:51:53'),(317,3,4,'213.60.52.236','2016-04-11 10:11:32'),(318,4357,3,'89.107.180.34','2016-04-11 10:13:54'),(319,1,1,'213.37.1.91','2016-04-11 10:43:02'),(320,3,4,'213.60.52.236','2016-04-11 10:44:31'),(321,4357,3,'89.107.180.34','2016-04-11 11:35:03'),(322,1,1,'213.37.1.91','2016-04-11 11:47:53'),(323,4,4,'89.107.180.75','2016-04-11 11:51:40'),(324,4357,3,'89.107.180.75','2016-04-11 12:52:28'),(325,1,1,'213.37.1.91','2016-04-11 12:53:37'),(326,4357,3,'89.107.180.34','2016-04-11 13:15:16'),(327,4357,3,'89.107.180.34','2016-04-11 14:40:40'),(328,4355,2,'213.60.52.236','2016-04-11 15:46:34'),(329,3,4,'213.60.52.236','2016-04-11 16:15:17'),(330,4357,3,'213.60.52.236','2016-04-11 16:17:07'),(331,4355,2,'213.60.52.236','2016-04-11 16:20:35'),(332,4362,3,'213.60.52.236','2016-04-11 16:51:18'),(333,4355,2,'213.60.52.236','2016-04-11 16:52:38'),(334,4355,2,'213.60.52.236','2016-04-11 16:58:04'),(335,3,4,'213.60.52.236','2016-04-11 17:55:35'),(336,4355,2,'213.60.52.236','2016-04-11 17:56:24'),(337,4357,3,'213.60.52.236','2016-04-11 17:57:12'),(338,4357,3,'213.60.52.236','2016-04-11 17:58:15'),(339,4357,3,'89.107.180.35','2016-04-11 18:05:56'),(340,4355,2,'89.107.180.35','2016-04-11 18:15:58'),(341,1,1,'213.37.1.91','2016-04-12 09:33:00'),(342,1,1,'213.37.1.91','2016-04-12 10:27:27'),(343,1,1,'213.37.1.91','2016-04-12 11:34:26'),(344,3,4,'213.60.52.236','2016-04-12 11:50:46'),(345,1,1,'213.37.1.91','2016-04-12 12:17:13'),(346,1,1,'213.37.1.91','2016-04-12 12:22:22'),(347,4,4,'80.28.203.156','2016-04-12 12:32:11'),(348,1,1,'213.37.1.91','2016-04-12 12:32:55'),(349,3,4,'213.60.52.236','2016-04-12 12:59:45'),(350,4363,3,'213.60.52.236','2016-04-12 13:02:26'),(351,3,4,'213.60.52.236','2016-04-12 13:17:25'),(352,4363,3,'213.60.52.236','2016-04-12 13:18:32'),(353,3,4,'213.60.52.236','2016-04-12 13:20:13'),(354,4363,3,'213.60.52.236','2016-04-12 13:21:21'),(355,3,4,'213.60.52.236','2016-04-12 13:56:51'),(356,4363,3,'213.60.52.236','2016-04-12 13:59:10'),(357,3,4,'213.60.52.236','2016-04-12 15:30:31'),(358,4,4,'213.60.52.236','2016-04-12 15:37:20'),(359,4,4,'213.60.52.236','2016-04-12 15:38:06'),(360,3,4,'213.60.52.236','2016-04-12 16:14:11'),(361,3,4,'213.60.52.236','2016-04-12 16:15:22'),(362,3,4,'213.60.52.236','2016-04-12 16:16:04'),(363,3,4,'213.60.52.236','2016-04-12 17:10:55'),(364,4355,2,'89.107.180.75','2016-04-13 15:33:11'),(365,3,4,'213.60.52.236','2016-04-13 15:37:38'),(366,3,4,'213.60.52.236','2016-04-13 15:40:13'),(367,4364,3,'213.60.52.236','2016-04-13 15:42:29'),(368,1,1,'213.37.1.91','2016-04-13 17:06:15'),(369,1,1,'213.37.1.91','2016-04-13 17:17:23'),(370,4,4,'89.107.180.75','2016-04-13 17:18:48'),(371,4364,3,'89.107.180.75','2016-04-13 17:20:31'),(372,1,1,'213.37.1.91','2016-04-13 17:29:29'),(373,3,4,'213.37.1.91','2016-04-13 17:33:02'),(374,1,1,'213.37.1.91','2016-04-13 17:41:02'),(375,3,4,'213.60.52.236','2016-04-14 09:48:06'),(376,1,1,'213.37.1.91','2016-04-14 09:55:36'),(377,1,1,'213.37.1.91','2016-04-14 10:01:39'),(378,1,1,'213.37.1.91','2016-04-14 10:07:34'),(379,1,1,'213.37.1.91','2016-04-14 10:44:37'),(380,3,4,'213.60.52.236','2016-04-14 10:56:01'),(381,3,4,'213.60.52.236','2016-04-14 13:08:14'),(382,1,1,'213.37.1.91','2016-04-14 13:50:34'),(383,4364,3,'89.107.177.17','2016-04-14 15:30:17'),(384,4364,3,'89.107.177.17','2016-04-14 15:32:39'),(385,4364,3,'89.107.180.34','2016-04-14 16:48:40'),(386,3,4,'213.60.52.236','2016-04-14 18:12:26'),(387,4355,2,'89.107.180.34','2016-04-14 18:24:26'),(388,4355,2,'89.107.180.35','2016-04-15 12:14:21'),(389,3,4,'213.60.52.236','2016-04-15 12:53:38'),(390,4355,2,'213.60.52.236','2016-04-18 09:57:34'),(391,3797,3,'213.37.1.91','2016-04-18 12:31:56'),(392,1,1,'213.37.1.91','2016-04-18 16:28:14'),(393,3,4,'213.60.52.236','2016-04-18 17:45:34'),(394,4364,3,'89.107.177.17','2016-04-19 09:34:22'),(395,4355,2,'89.107.177.17','2016-04-19 09:40:13'),(396,1,1,'213.37.1.91','2016-04-19 10:24:47'),(397,4355,2,'89.107.180.35','2016-04-19 10:27:28'),(398,1,1,'213.37.1.91','2016-04-19 14:06:41'),(399,1,1,'127.0.0.1','2016-04-19 14:27:18'),(400,3797,3,'127.0.0.1','2016-04-19 14:34:46'),(401,1,1,'127.0.0.1','2016-04-19 16:43:33');

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(49) CHARACTER SET utf8 DEFAULT NULL,
  `code` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_bin DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `languages` */

insert  into `languages`(`id`,`name`,`code`,`active`) values (1,'English','en','0'),(2,'Spanish','es','1'),(3,'Italian','it','0'),(4,'German','de','0'),(5,'French','fr','0');

/*Table structure for table `media` */

DROP TABLE IF EXISTS `media`;

CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `id_parent` int(11) NOT NULL,
  `model` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tmp_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `error` int(255) NOT NULL,
  `size` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media` */

insert  into `media`(`id`,`id_parent`,`model`,`name`,`type`,`tmp_name`,`error`,`size`,`created_at`) values (1,1,'blog','1-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\phpB2A8.tmp',0,8171,'2016-06-13 14:29:22'),(2,1,'blog','2-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\php99BE.tmp',0,8171,'2016-06-13 14:30:21'),(3,1,'blog','3-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\php4D8E.tmp',0,8171,'2016-06-13 14:31:07'),(4,1,'blog','4-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\php8D52.tmp',0,8171,'2016-06-13 14:32:29'),(5,1,'blog','5-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\php283A.tmp',0,8171,'2016-06-13 14:40:47'),(6,1,'blog','6-imgres2.jpg','image/jpeg','C:\\xampp\\tmp\\phpA6FA.tmp',0,8171,'2016-06-13 14:50:04');

/*Table structure for table `megabanners` */

DROP TABLE IF EXISTS `megabanners`;

CREATE TABLE `megabanners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners` */

/*Table structure for table `megabanners_locales` */

DROP TABLE IF EXISTS `megabanners_locales`;

CREATE TABLE `megabanners_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_alt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_link` enum('_self','_blank') COLLATE utf8_unicode_ci NOT NULL DEFAULT '_blank',
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `megabanners_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `megabanners` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners_locales` */

/*Table structure for table `static_pages` */

DROP TABLE IF EXISTS `static_pages`;

CREATE TABLE `static_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages` */

/*Table structure for table `static_pages_locales` */

DROP TABLE IF EXISTS `static_pages_locales`;

CREATE TABLE `static_pages_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `static_pages_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `static_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages_locales` */

/*Table structure for table `valores_configuracion` */

DROP TABLE IF EXISTS `valores_configuracion`;

CREATE TABLE `valores_configuracion` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_key` varchar(100) NOT NULL,
  `entry_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `valores_configuracion` */

insert  into `valores_configuracion`(`id`,`entry_key`,`entry_value`) values (1,'nameAuthor','4422222222'),(2,'nameClient','Col ZF2888'),(3,'introText','Content Manager for Business XXX '),(4,'logoImage','imgres2.jpg');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
