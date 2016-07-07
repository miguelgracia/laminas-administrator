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
) ENGINE=InnoDB AUTO_INCREMENT=224 DEFAULT CHARSET=utf8;

/*Data for the table `admin_menus` */

insert  into `admin_menus`(`id`,`admin_module_id`,`parent`,`title`,`action`,`order`) values (2,0,0,'Administrador','',7),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',1),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',2),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',3),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',2),(214,1,0,'Home','index',1),(215,24,0,'Media','index',5),(216,26,0,'Megabanners','index',4),(217,23,207,'Categorías','index',3),(218,27,0,'Páginas Estáticas','index',6),(219,28,0,'Trabajos','index',2),(220,29,219,'Categorías','index',3),(221,28,219,'Listado','index',1),(222,0,219,'Nuevo Trabajo','add',2);

/*Table structure for table `admin_modules` */

DROP TABLE IF EXISTS `admin_modules`;

CREATE TABLE `admin_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zend_name` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`zend_name`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8;

/*Data for the table `admin_modules` */

insert  into `admin_modules`(`id`,`zend_name`,`public_name`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page'),(28,'job','job'),(29,'job-category','job-category');

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

insert  into `admin_profiles`(`id`,`key`,`name`,`description`,`is_admin`,`permissions`) values (1,'Superadmin','Superadmin','<p>Administrador de la plataforma</p>\r\n',1,''),(2,'Coordinador','Coordinador','<p>Usuario normal de la plataforma</p>\r\n',0,'[\"home.index\",\"blog.add\",\"blog.edit\",\"blog.index\",\"blog-category.add\",\"blog-category.edit\",\"blog-category.index\",\"media.connector\",\"media.index\",\"media.remove\",\"media.upload\",\"megabanner.add\",\"megabanner.delete\",\"megabanner.edit\",\"megabanner.index\",\"static-page.add\",\"static-page.delete\",\"static-page.edit\",\"static-page.index\",\"job.add\",\"job.edit\",\"job.index\",\"job-category.add\",\"job-category.edit\",\"job-category.index\"]'),(3,'RelationDirector','Director de Relacion','descripcion',0,'[\"user.edit\",\"menu.index\",\"menu.edit\"]'),(4,'Administrator','Administrador','Administrador\r\n',1,'[\"blog.add\",\"blog.edit\",\"blog.index\",\"blog-category.add\",\"blog-category.edit\",\"blog-category.index\",\"media.connector\",\"media.index\",\"media.remove\",\"media.upload\",\"megabanner.add\",\"megabanner.delete\",\"megabanner.edit\",\"megabanner.index\",\"static-page.add\",\"static-page.delete\",\"static-page.edit\",\"static-page.index\",\"job.add\",\"job.edit\",\"job.index\",\"job-category.add\",\"job-category.edit\",\"job-category.index\"]');

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
) ENGINE=InnoDB AUTO_INCREMENT=3809 DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories_locales` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_entries_locales` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories_locales` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs_locales` */

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `media` */

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

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
