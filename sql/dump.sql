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

/*Table structure for table `admin_menus` */

DROP TABLE IF EXISTS `admin_menus`;

CREATE TABLE `admin_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_module_id` int(11) unsigned DEFAULT NULL,
  `parent` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `action` varchar(255) DEFAULT NULL,
  `order` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=230 DEFAULT CHARSET=utf8;

/*Data for the table `admin_menus` */

insert  into `admin_menus`(`id`,`admin_module_id`,`parent`,`title`,`action`,`order`) values (2,0,0,'Administrador','',7),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',1),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',2),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',3),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',2),(214,1,0,'Home','index',1),(215,24,0,'Media','index',5),(216,26,0,'Megabanners','index',4),(217,23,207,'Categorías','index',3),(218,27,0,'Páginas Legales','index',6),(219,28,0,'Trabajos','index',2),(220,29,219,'Categorías','index',3),(221,28,219,'Listado','index',1),(222,28,219,'Nuevo Trabajo','add',2),(224,0,0,'Web Menú','',0),(225,30,224,'Listado','index',0),(226,30,224,'Nueva sección','add',0),(227,32,2,'Idiomas','index',6),(228,0,0,'Modulos Home','',0),(229,33,228,'Listado','index',0);

/*Table structure for table `admin_modules` */

DROP TABLE IF EXISTS `admin_modules`;

CREATE TABLE `admin_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zend_name` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`zend_name`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8;

/*Data for the table `admin_modules` */

insert  into `admin_modules`(`id`,`zend_name`,`public_name`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page'),(28,'job','job'),(29,'job-category','job-category'),(30,'section','section'),(31,'job-video','job-video'),(32,'language','language'),(33,'home-module','home-module');

/*Table structure for table `admin_profiles` */

DROP TABLE IF EXISTS `admin_profiles`;

CREATE TABLE `admin_profiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `is_admin` tinyint(4) DEFAULT '0',
  `permissions` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `admin_profiles` */

insert  into `admin_profiles`(`id`,`key`,`name`,`description`,`is_admin`,`permissions`) values (1,'Superadmin','Superadmin','<p>Administrador de la plataforma</p>\r\n',1,''),(4,'Administrator','Administrador','Administrador\r\n',0,'[\"home.index\",\"blog.add\",\"blog.delete\",\"blog.edit\",\"blog.index\",\"blog-category.add\",\"blog-category.delete\",\"blog-category.edit\",\"blog-category.index\",\"media.connector\",\"media.index\",\"media.remove\",\"media.upload\",\"megabanner.add\",\"megabanner.delete\",\"megabanner.edit\",\"megabanner.index\",\"static-page.add\",\"static-page.delete\",\"static-page.edit\",\"static-page.index\",\"job.add\",\"job.delete\",\"job.edit\",\"job.index\",\"job-category.add\",\"job-category.delete\",\"job-category.edit\",\"job-category.index\",\"section.edit\",\"section.index\",\"job-video.add\",\"job-video.edit\",\"job-video.index\",\"home-module.add\",\"home-module.delete\",\"home-module.edit\",\"home-module.index\"]');

/*Table structure for table `admin_users` */

DROP TABLE IF EXISTS `admin_users`;

CREATE TABLE `admin_users` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_profile_id` int(11) unsigned NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `validado` tinyint(4) DEFAULT '0',
  `active` enum('0','1') DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

/*Data for the table `admin_users` */

insert  into `admin_users`(`id`,`admin_profile_id`,`username`,`password`,`validado`,`active`,`created_at`,`updated_at`,`deleted_at`,`last_login`) values (1,1,'dreamsite','e10adc3949ba59abbe56e057f20f883e',1,'1','2016-01-13 12:42:11',NULL,NULL,'2016-04-22 09:33:56'),(3,4,'admin','e10adc3949ba59abbe56e057f20f883e',1,'1','2016-03-29 12:05:16',NULL,NULL,'2016-04-18 17:45:34');

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

insert  into `blog_categories`(`id`,`key`,`created_at`,`updated_at`,`deleted_at`,`active`) values (1,'Cat','2016-07-12 22:03:04','2016-07-22 16:26:34',NULL,'0');

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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories_locales` */

insert  into `blog_categories_locales`(`id`,`related_table_id`,`title`,`url_key`,`meta_description`,`language_id`) values (1,1,'cat','cat','cat',2);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries` */

insert  into `blog_entries`(`id`,`blog_categories_id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,1,'Sdfsdddd','0','2016-07-12 22:03:24',NULL,NULL),(2,1,'NuevaEntradaDeBlog','1','2016-07-19 01:15:23',NULL,NULL),(3,1,'Asdf','0','2016-07-19 01:23:41',NULL,NULL);

/*Table structure for table `blog_entries_locales` */

DROP TABLE IF EXISTS `blog_entries_locales`;

CREATE TABLE `blog_entries_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries_locales` */

insert  into `blog_entries_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'asdfdf3333','asdf','<p>df</p>\r\n','sfd',2),(2,3,'asdf','asdfwwww','<p>asdf</p>\r\n','asdf',2);

/*Table structure for table `configuration` */

DROP TABLE IF EXISTS `configuration`;

CREATE TABLE `configuration` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `entry_key` varchar(100) NOT NULL,
  `entry_value` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `configuration` */

insert  into `configuration`(`id`,`entry_key`,`entry_value`) values (1,'nameAuthor','Miguel Graciá Martín'),(2,'nameClient','ABS Consultor'),(3,'introText','Content Manager for Business XXX '),(4,'logoImage','img_carrusel_SamsungTV+Ipad.jpg');

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
) ENGINE=InnoDB AUTO_INCREMENT=221 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_menu` */

insert  into `gestor_menu`(`id`,`gestor_module_id`,`padre`,`texto`,`accion`,`orden`) values (2,0,0,'Configuración','',90),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',2),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',1),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',1),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',1),(210,0,0,'Categorías de Blog','',1),(211,23,210,'Listado','index',1),(214,1,0,'Home','index',0),(215,24,0,'Media','index',0),(216,26,0,'Megabanners','index',0),(217,23,207,'Categorías','index',0),(218,27,0,'Páginas Estáticas','index',0),(219,0,0,'Trabajos','',0),(220,28,219,'Listado','index',0);

/*Table structure for table `gestor_modules` */

DROP TABLE IF EXISTS `gestor_modules`;

CREATE TABLE `gestor_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_zend` varchar(255) NOT NULL,
  `nombre_usable` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`nombre_zend`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_modules` */

insert  into `gestor_modules`(`id`,`nombre_zend`,`nombre_usable`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page'),(28,'job','job');

/*Table structure for table `gestor_modules_locale` */

DROP TABLE IF EXISTS `gestor_modules_locale`;

CREATE TABLE `gestor_modules_locale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `gestor_modules_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `locale` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`gestor_modules_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `gestor_modules_locale` */

/*Table structure for table `gestor_perfiles` */

DROP TABLE IF EXISTS `gestor_perfiles`;

CREATE TABLE `gestor_perfiles` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `es_admin` tinyint(4) DEFAULT '0',
  `permisos` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

/*Data for the table `gestor_perfiles` */

insert  into `gestor_perfiles`(`id`,`key`,`nombre`,`descripcion`,`es_admin`,`permisos`) values (1,'','Superadmin','Administrador de la plataforma',1,'[]'),(2,'','Coordinador','Usuario normal de la plataforma',0,'[\"home.index\",\"configuration.index\",\"user.index\",\"profile.add\",\"profile.delete\",\"menu.index\"]'),(3,'','Director de Relacion','descripcion',0,'[\"user.edit\",\"menu.index\",\"menu.edit\"]'),(4,'','Administrador','Administrador',1,'[\"user.index\",\"profile.add\",\"menu.add\",\"menu.saveOrder\",\"menu.delete\"]');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*Data for the table `historico_login` */

/*Table structure for table `home_modules` */

DROP TABLE IF EXISTS `home_modules`;

CREATE TABLE `home_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules` */

insert  into `home_modules`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Modulohome','1','2016-09-05 23:41:10','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `home_modules_locales` */

DROP TABLE IF EXISTS `home_modules_locales`;

CREATE TABLE `home_modules_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `link_text` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_id` int(10) NOT NULL,
  `target_link` enum('_self','_blank') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules_locales` */

insert  into `home_modules_locales`(`id`,`related_table_id`,`image_url`,`content`,`link_text`,`link_url`,`language_id`,`target_link`) values (1,1,'','<p>Contenido m&oacute;dulo</p>\r\n','','',2,NULL),(2,1,'','<p>My content home module</p>\r\n','','',1,NULL);

/*Table structure for table `job_categories` */

DROP TABLE IF EXISTS `job_categories`;

CREATE TABLE `job_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories` */

insert  into `job_categories`(`id`,`key`,`created_at`,`updated_at`,`deleted_at`,`active`) values (1,'NormativaIso','2016-07-08 20:47:06',NULL,NULL,'1');

/*Table structure for table `job_categories_locales` */

DROP TABLE IF EXISTS `job_categories_locales`;

CREATE TABLE `job_categories_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories_locales` */

insert  into `job_categories_locales`(`id`,`related_table_id`,`title`,`url_key`,`meta_description`,`language_id`) values (1,1,'Implantación ISO','implantacion-iso','',2);

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_categories_id` int(10) unsigned NOT NULL,
  `key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`job_categories_id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (2,1,'Mitrabajo','0','2016-07-10 19:42:49',NULL,NULL),(3,1,'4444','0','2016-07-10 19:50:05',NULL,NULL),(4,1,'Qwe','0','2016-07-12 20:07:29',NULL,NULL);

/*Table structure for table `jobs_locales` */

DROP TABLE IF EXISTS `jobs_locales`;

CREATE TABLE `jobs_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs_locales` */

insert  into `jobs_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (2,2,'titulo post','titulo-post','<p>hola hoal</p>\r\n','metaasdasdasd',2),(3,2,'eeswwwww','ee','<p>eess</p>\r\n','ee',1),(4,3,'444','444','<p>444</p>\r\n','4444',1),(5,3,'333','33','<p>3333</p>\r\n','333',2),(6,4,'qwe','qwe','<p>qwe</p>\r\n','qwe',2);

/*Table structure for table `jobs_videos` */

DROP TABLE IF EXISTS `jobs_videos`;

CREATE TABLE `jobs_videos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_id` int(10) unsigned NOT NULL,
  `url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `job_id` (`job_id`),
  CONSTRAINT `jobs_videos_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs_videos` */

/*Table structure for table `languages` */

DROP TABLE IF EXISTS `languages`;

CREATE TABLE `languages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(49) CHARACTER SET utf8 DEFAULT NULL,
  `code` char(2) CHARACTER SET utf8 DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_bin DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `languages` */

insert  into `languages`(`id`,`name`,`code`,`active`) values (1,'English','en','1'),(2,'Spanish','es','1');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media` */

/*Table structure for table `megabanners` */

DROP TABLE IF EXISTS `megabanners`;

CREATE TABLE `megabanners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_video` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners` */

insert  into `megabanners`(`id`,`key`,`is_video`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Mega1','0','0','2016-07-31 14:34:25','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `megabanners_locales` */

DROP TABLE IF EXISTS `megabanners_locales`;

CREATE TABLE `megabanners_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `element_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `element_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `link` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_link` enum('_self','_blank') COLLATE utf8_unicode_ci NOT NULL DEFAULT '_blank',
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `megabanners_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `megabanners` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners_locales` */

insert  into `megabanners_locales`(`id`,`related_table_id`,`element_url`,`element_title`,`link`,`target_link`,`language_id`) values (1,1,'/abs/IMAGEN_1_HOME_TRABAJOS_CARRUSEL.png','imagen grua','http://www.google.es','_self',2);

/*Table structure for table `sections` */

DROP TABLE IF EXISTS `sections`;

CREATE TABLE `sections` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `position` enum('header','footer') COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `deleted_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sections` */

insert  into `sections`(`id`,`key`,`position`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Empresa','header','1','2016-07-12 23:40:57','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Trabajos','header','1','2016-09-05 23:54:24','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'Blog','header','0','2016-09-05 23:55:24','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'Contacto','header','1','2016-09-05 23:56:19','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `sections_locales` */

DROP TABLE IF EXISTS `sections_locales`;

CREATE TABLE `sections_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `header_name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `sections_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sections_locales` */

insert  into `sections_locales`(`id`,`related_table_id`,`name`,`header_name`,`content`,`url_key`,`meta_description`,`language_id`) values (1,1,'Empresa','Empresa',NULL,'empresa','descripción empresa',2),(2,1,'Company','Company',NULL,'company','Company description',1),(3,2,'work','Work',NULL,'work','work description',1),(4,2,'Trabajos','Trabajos',NULL,'trabajos','descripción trabajos',2),(5,3,'Blog','Blog',NULL,'blog','Blog description',1),(6,3,'Blog','Blog',NULL,'blog','Descripción blog',2),(7,4,'Contact us','Contact Us',NULL,'contact-us','contact us description',1),(8,4,'Contacto','Contáctanos',NULL,'contacto','Descripción contacto',2);

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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages` */

insert  into `static_pages`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Privacidad','0','2016-07-04 09:18:01','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'Cookies','0','2016-07-07 16:30:23','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'AvisoLegal','0','2016-07-07 16:31:17','0000-00-00 00:00:00','0000-00-00 00:00:00');

/*Table structure for table `static_pages_locales` */

DROP TABLE IF EXISTS `static_pages_locales`;

CREATE TABLE `static_pages_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `static_pages_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `static_pages` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages_locales` */

insert  into `static_pages_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'política de privacidad','politica-de-privacidad','','',2),(2,2,'Política de tratamiento de cookies','politica-de-tratamiento-de-cookies','','',2),(3,3,'Aviso Legal','aviso-legal','','',2);

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
