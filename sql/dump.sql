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
) ENGINE=InnoDB AUTO_INCREMENT=232 DEFAULT CHARSET=utf8;

/*Data for the table `admin_menus` */

insert  into `admin_menus`(`id`,`admin_module_id`,`parent`,`title`,`action`,`order`) values (2,0,0,'Administrador','',7),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',1),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',2),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',3),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',2),(214,1,0,'Home','index',1),(215,24,0,'Media','index',5),(216,26,0,'Megabanners','index',4),(217,23,207,'Categorías','index',3),(218,27,0,'Páginas Legales','index',6),(219,28,0,'Trabajos','index',2),(220,29,219,'Categorías','index',3),(221,28,219,'Listado','index',1),(222,28,219,'Nuevo Trabajo','add',2),(224,0,0,'Web Menú','',0),(225,30,224,'Listado','index',0),(226,30,224,'Nueva sección','add',0),(227,32,2,'Idiomas','index',6),(228,0,0,'Modulos Home','',0),(229,33,228,'Listado','index',0),(230,34,0,'Datos Web','index',0),(231,35,0,'Colaboradores','index',0);

/*Table structure for table `admin_modules` */

DROP TABLE IF EXISTS `admin_modules`;

CREATE TABLE `admin_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zend_name` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`zend_name`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;

/*Data for the table `admin_modules` */

insert  into `admin_modules`(`id`,`zend_name`,`public_name`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page'),(28,'job','job'),(29,'job-category','job-category'),(30,'section','section'),(31,'job-video','job-video'),(32,'language','language'),(33,'home-module','home-module'),(34,'app-data','app-data'),(35,'partner','partner');

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

insert  into `admin_profiles`(`id`,`key`,`name`,`description`,`is_admin`,`permissions`) values (1,'Superadmin','Superadmin','<p>Administrador de la plataforma</p>\r\n',1,''),(4,'administrator','Administrador','Administrador\r\n',0,'[\"home.index\",\"blog.add\",\"blog.delete\",\"blog.edit\",\"blog.index\",\"blog-category.add\",\"blog-category.delete\",\"blog-category.edit\",\"blog-category.index\",\"media.connector\",\"media.index\",\"media.remove\",\"media.upload\",\"megabanner.add\",\"megabanner.delete\",\"megabanner.edit\",\"megabanner.index\",\"static-page.add\",\"static-page.delete\",\"static-page.edit\",\"static-page.index\",\"job.add\",\"job.delete\",\"job.edit\",\"job.index\",\"job-category.add\",\"job-category.delete\",\"job-category.edit\",\"job-category.index\",\"section.edit\",\"section.index\",\"job-video.add\",\"job-video.edit\",\"job-video.index\",\"home-module.add\",\"home-module.delete\",\"home-module.edit\",\"home-module.index\",\"partner.add\",\"partner.delete\",\"partner.edit\",\"partner.index\"]');

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

/*Table structure for table `app_datas` */

DROP TABLE IF EXISTS `app_datas`;

CREATE TABLE `app_datas` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `facebook` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `twitter` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `google_plus` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `instagram` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_datas` */

insert  into `app_datas`(`id`,`key`,`facebook`,`twitter`,`google_plus`,`instagram`,`created_at`,`updated_at`,`deleted_at`) values (1,'Appdata','http://www.facebook.com/absonsultor.es','http://www.twitter.com','','','2016-09-09 11:57:17',NULL,NULL);

/*Table structure for table `app_datas_locales` */

DROP TABLE IF EXISTS `app_datas_locales`;

CREATE TABLE `app_datas_locales` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` tinyint(3) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_datas_locales` */

insert  into `app_datas_locales`(`id`,`related_table_id`,`email`,`phone`,`language_id`) values (1,1,'info@absconsultor.com','658 888 222',1),(2,1,'info@absconsultor.es','658 777 888',2);

/*Table structure for table `app_routes` */

DROP TABLE IF EXISTS `app_routes`;

CREATE TABLE `app_routes` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `visible` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `order` tinyint(4) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_routes` */

insert  into `app_routes`(`id`,`key`,`visible`,`active`,`order`,`created_at`,`updated_at`,`deleted_at`) values (1,'company','1','1',1,'2016-07-12 23:40:57',NULL,NULL),(2,'jobs','1','1',2,'2016-09-05 23:54:24',NULL,NULL),(3,'blog','1','1',3,'2016-09-05 23:55:24',NULL,NULL),(4,'contact','1','1',4,'2016-09-05 23:56:19',NULL,NULL),(5,'company/colaborators','0','1',5,'2016-09-08 22:16:43',NULL,NULL);

/*Table structure for table `app_routes_locales` */

DROP TABLE IF EXISTS `app_routes_locales`;

CREATE TABLE `app_routes_locales` (
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
  CONSTRAINT `app_routes_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `app_routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_routes_locales` */

insert  into `app_routes_locales`(`id`,`related_table_id`,`name`,`header_name`,`content`,`url_key`,`meta_description`,`language_id`) values (1,1,'Empresa','Empresa','<p><img alt=\"\" src=\"/maqueta/company1.png\" /></p>\r\n\r\n<p>Ingles Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et nunc pulvinar dui elementum euismod quis ut elit.&nbsp;Sed cursus et arcu at malesuada. Integer rhoncus congue cursus. Sed dictum viverra egestas. Nulla facilisi.Nam volutpat rhoncus magna non porttitor. Donec euismod vitae velit in sollicitudin. Nam vitae dui pulvinar, lobortis mi a,&nbsp;ultricies ante. Morbi ligula mi, dapibus ac nisi sagittis, placerat mollis erat. Vivamus cursus augue et ex aliquam sollicitudin.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et nunc pulvinar dui elementum euismod quis ut elit.&nbsp;Sed cursus et arcu at malesuada. Integer rhoncus congue cursus. Sed dictum viverra egestas. Nulla facilisi.&nbsp;Nam volutpat rhoncus magna non porttitor. Donec euismod vitae velit in sollicitudin. Nam vitae dui pulvinar, lobortis mi a,&nbsp;ultricies ante. Morbi ligula mi, dapibus ac nisi sagittis, placerat mollis erat. Vivamus cursus augue et ex aliquam sollicitudin.</p>\r\n','empresa','descripción empresa',2),(2,1,'Company','Company','<p><img alt=\"\" src=\"/media/maqueta/company1.png\" style=\"width:100%\" /></p>\r\n\r\n<p>\r\n<video controls=\"controls\" height=\"auto\" id=\"video2016810222521\" poster=\"\" width=\"100%\"><source src=\"/media/video/grua.mp4\" type=\"video/mp4\" />Su navegador no soporta VIDEO.<br />\r\nPor favor, descargue el fichero: <a href=\"/media/video/grua.mp4\">video/mp4</a></video>\r\n</p>\r\n\r\n<p>Ingles Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et nunc pulvinar dui elementum euismod quis ut elit.&nbsp;Sed cursus et arcu at malesuada. Integer rhoncus congue cursus. Sed dictum viverra egestas. Nulla facilisi.Nam volutpat rhoncus magna non porttitor. Donec euismod vitae velit in sollicitudin. Nam vitae dui pulvinar, lobortis mi a,&nbsp;ultricies ante. Morbi ligula mi, dapibus ac nisi sagittis, placerat mollis erat. Vivamus cursus augue et ex aliquam sollicitudin.</p>\r\n\r\n<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Pellentesque et nunc pulvinar dui elementum euismod quis ut elit.&nbsp;Sed cursus et arcu at malesuada. Integer rhoncus congue cursus. Sed dictum viverra egestas. Nulla facilisi.&nbsp;Nam volutpat rhoncus magna non porttitor. Donec euismod vitae velit in sollicitudin. Nam vitae dui pulvinar, lobortis mi a,&nbsp;ultricies ante. Morbi ligula mi, dapibus ac nisi sagittis, placerat mollis erat. Vivamus cursus augue et ex aliquam sollicitudin.</p>\r\n','company','Company description',1),(3,2,'Work','Work',NULL,'work','work description',1),(4,2,'Trabajos','Trabajos',NULL,'trabajos','descripción trabajos',2),(5,3,'Blog','Blog',NULL,'blog','Blog description',1),(6,3,'Blog','Blog',NULL,'blog','Descripción blog',2),(7,4,'Contact us','Contact Us',NULL,'contact-us','contact us description',1),(8,4,'Contacto','Contáctanos',NULL,'contacto','Descripción contacto',2),(9,5,'Colaborators','Colaborators','','colaborators','',1),(10,5,'Colaboradores','Colaboradores','','colaboradores','',2);

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules` */

insert  into `home_modules`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'modulohome','1','2016-09-05 23:41:10',NULL,NULL),(2,'modulo2','1','2016-09-10 20:29:56',NULL,NULL),(3,'modulo-3','1','2016-09-10 20:31:58',NULL,NULL);

/*Table structure for table `home_modules_locales` */

DROP TABLE IF EXISTS `home_modules_locales`;

CREATE TABLE `home_modules_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `link_text` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_id` int(10) NOT NULL,
  `target_link` enum('_self','_blank') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules_locales` */

insert  into `home_modules_locales`(`id`,`related_table_id`,`title`,`image_url`,`content`,`link_text`,`link_url`,`language_id`,`target_link`) values (1,1,'Empresa','/maqueta/home1.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','+ Información','/empresa',2,'_self'),(2,1,'Company','/maqueta/home1.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','+ Info','/company',1,'_self'),(3,2,'Works','/maqueta/home2.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','View more','/works',1,'_self'),(4,2,'Trabajos','/maqueta/home2.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','Ver más','/trabajos',2,'_self'),(5,3,'Blog','/maqueta/home3.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','View more','/blog',1,'_self'),(6,3,'Blog','/maqueta/home3.png','<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vivamus id nunc convallis, accumsan enim ac, convallis risus. Aliquam facilisis mi lectus, nec vestibulum ipsum vestibulum nec. Curabitur tincidunt ultricies nisi eu mattis. Donec blandit vitae mi vitae commodo. Ut tortor dui, vulputate et nunc et, suscipit vestibulum ipsum.</p>\r\n','Ver más','/blog',2,'_self');

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
  `name` char(49) CHARACTER SET utf8 NOT NULL,
  `code` char(5) CHARACTER SET utf8 NOT NULL,
  `active` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `languages` */

insert  into `languages`(`id`,`name`,`code`,`active`) values (1,'English','en_en','1'),(2,'Spanish','es_es','1');

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
  `is_video` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners` */

insert  into `megabanners`(`id`,`is_video`,`active`,`created_at`,`updated_at`,`deleted_at`) values (3,'0','1','2016-09-10 16:29:34',NULL,'2016-09-14 18:58:04'),(4,'1','1','2016-09-10 16:52:33',NULL,NULL);

/*Table structure for table `megabanners_locales` */

DROP TABLE IF EXISTS `megabanners_locales`;

CREATE TABLE `megabanners_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `element_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `element_title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `language_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `megabanners_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `megabanners` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners_locales` */

insert  into `megabanners_locales`(`id`,`related_table_id`,`element_url`,`element_title`,`language_id`) values (1,3,'/maqueta/company1.png','Grua',1),(2,3,'/maqueta/company1.png','Grua',2),(3,4,'/video/grua.mp4','Video grua',1),(4,4,'/video/grua.mp4','Video grua',2);

/*Table structure for table `partners` */

DROP TABLE IF EXISTS `partners`;

CREATE TABLE `partners` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `website` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `partners` */

insert  into `partners`(`id`,`name`,`logo`,`website`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Gruas Bonet','/maqueta/collab1.png','http://www.gruasbonet.es','1','2016-09-10 23:33:36',NULL,NULL),(2,'Hnos. Jiménez Gómez','/maqueta/collab2.png','http://www.hjimenez.com','1','2016-09-10 23:50:04',NULL,NULL),(3,'León Tubos','/maqueta/collab3.png','http://www.leontubos.com','1','2016-09-10 23:57:03',NULL,NULL),(4,'Silmeca','/maqueta/collab4.png','http://www.silmecasl.com','1','2016-09-10 23:59:17',NULL,NULL);

/*Table structure for table `partners_locales` */

DROP TABLE IF EXISTS `partners_locales`;

CREATE TABLE `partners_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci,
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  CONSTRAINT `partners_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `partners` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `partners_locales` */

insert  into `partners_locales`(`id`,`related_table_id`,`content`,`language_id`) values (1,1,'<p>Gruas Bonet Information</p>\r\n',1),(2,1,'<p>Informaci&oacute;n Gruas Bonet</p>\r\n',2),(3,2,'<p>Ingles Info colaborador</p>\r\n',1),(4,2,'<p>Espa&ntilde;ol Info colaborador</p>\r\n',2),(5,3,'',1),(6,3,'',2),(7,4,'',1),(8,4,'',2);

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

insert  into `sections`(`id`,`key`,`position`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'company','header','1','2016-07-12 23:40:57','0000-00-00 00:00:00','0000-00-00 00:00:00'),(2,'jobs','header','1','2016-09-05 23:54:24','0000-00-00 00:00:00','0000-00-00 00:00:00'),(3,'blog','header','0','2016-09-05 23:55:24','0000-00-00 00:00:00','0000-00-00 00:00:00'),(4,'Contacto','header','1','2016-09-05 23:56:19','0000-00-00 00:00:00','0000-00-00 00:00:00');

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
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages` */

insert  into `static_pages`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'privacidad','1','2016-07-04 09:18:01',NULL,NULL),(2,'cookies','1','2016-07-07 16:30:23',NULL,NULL),(3,'avisolegal','1','2016-07-07 16:31:17',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages_locales` */

insert  into `static_pages_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'Política de privacidad','politica-de-privacidad','','',2),(2,2,'Cookies','cookies','','',2),(3,3,'Aviso Legal','aviso-legal','','',2),(4,1,'Privacy policy','privacy-policy','','',1),(5,2,'Cookies','cookies','','',1),(6,3,'Legal Notice','legal-notice','','',1);

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
