/*
SQLyog Ultimate v11.33 (64 bit)
MySQL - 5.6.26 : Database - qwi250
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`qwi250` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `qwi250`;

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
) ENGINE=InnoDB AUTO_INCREMENT=236 DEFAULT CHARSET=utf8;

/*Data for the table `admin_menus` */

insert  into `admin_menus`(`id`,`admin_module_id`,`parent`,`title`,`action`,`order`) values (2,0,0,'Administrador','',13),(201,2,2,'Valores Generales','index',3),(202,3,2,'Usuarios','index',1),(203,4,2,'Perfil','index',4),(205,6,2,'Menú','index',2),(206,19,2,'Módulos','index',5),(207,0,0,'Blog','',8),(208,20,207,'Listado','index',1),(209,20,207,'Nueva entrada','add',2),(214,1,0,'Home','index',1),(215,0,0,'Media','',9),(216,26,0,'Megabanners','index',4),(217,23,207,'Categorías','index',3),(218,27,0,'Páginas Legales','index',10),(219,28,0,'Trabajos','index',7),(220,29,219,'Categorías','index',3),(221,28,219,'Listado','index',1),(222,28,219,'Nuevo Trabajo','add',2),(224,0,0,'Web Menú','',3),(225,30,224,'Listado','index',0),(226,30,224,'Nueva sección','add',0),(228,0,0,'Modulos Home','',5),(229,33,228,'Listado','index',0),(230,34,0,'Datos Web','index',2),(231,35,0,'Colaboradores','index',6),(232,24,215,'Video Posters','videoPoster',2),(233,24,215,'Explorar Archivos','index',1),(234,32,0,'Idiomas','index',11),(235,36,0,'Canal YouTube','index',12);

/*Table structure for table `admin_modules` */

DROP TABLE IF EXISTS `admin_modules`;

CREATE TABLE `admin_modules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `zend_name` varchar(255) NOT NULL,
  `public_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombreZend` (`zend_name`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8;

/*Data for the table `admin_modules` */

insert  into `admin_modules`(`id`,`zend_name`,`public_name`) values (1,'home','home'),(2,'configuration','Valores de configuración'),(3,'user','User'),(4,'profile','Perfiles'),(6,'menu','Entradas de menú'),(19,'module','Module'),(20,'blog','blog'),(23,'blog-category','blog-category'),(24,'media','media'),(26,'megabanner','megabanner'),(27,'static-page','static-page'),(28,'job','job'),(29,'job-category','job-category'),(30,'section','section'),(31,'job-video','job-video'),(32,'language','language'),(33,'home-module','home-module'),(34,'app-data','app-data'),(35,'partner','partner'),(36,'you-tube','you-tube');

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

insert  into `admin_profiles`(`id`,`key`,`name`,`description`,`is_admin`,`permissions`) values (1,'Superadmin','Superadmin','<p>Administrador de la plataforma</p>\r\n',1,''),(4,'administrator','Administrador','Administrador\r\n',0,'[\"home.index\",\"blog.add\",\"blog.delete\",\"blog.edit\",\"blog.index\",\"blog-category.add\",\"blog-category.delete\",\"blog-category.edit\",\"blog-category.index\",\"media.connector\",\"media.index\",\"megabanner.add\",\"megabanner.delete\",\"megabanner.edit\",\"megabanner.index\",\"static-page.edit\",\"static-page.index\",\"job.add\",\"job.delete\",\"job.edit\",\"job.index\",\"job-category.add\",\"job-category.delete\",\"job-category.edit\",\"job-category.index\",\"section.edit\",\"section.index\",\"language.edit\",\"language.index\",\"home-module.add\",\"home-module.delete\",\"home-module.edit\",\"home-module.index\",\"app-data.edit\",\"app-data.index\",\"partner.add\",\"partner.delete\",\"partner.edit\",\"partner.index\",\"you-tube.add\",\"you-tube.delete\",\"you-tube.edit\",\"you-tube.index\",\"you-tube.oauthCallback\",\"you-tube.sync\"]');

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

insert  into `admin_users`(`id`,`admin_profile_id`,`username`,`password`,`validado`,`active`,`created_at`,`updated_at`,`deleted_at`,`last_login`) values (1,1,'superabs','e10adc3949ba59abbe56e057f20f883e',1,'1','2016-01-13 12:42:11',NULL,NULL,'2016-04-22 09:33:56'),(3,4,'absconsultor.es','1741b1ec3d215170cdde116f11ad7e3e',1,'1','2016-03-29 12:05:16',NULL,NULL,'2016-04-18 17:45:34');

/*Table structure for table `app_datas` */

DROP TABLE IF EXISTS `app_datas`;

CREATE TABLE `app_datas` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `mail_inbox` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
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

insert  into `app_datas`(`id`,`key`,`mail_inbox`,`facebook`,`twitter`,`google_plus`,`instagram`,`created_at`,`updated_at`,`deleted_at`) values (1,'appdata','info@absconsultor.es','http://www.facebook.com/absonsultor.es','','','','2016-09-09 11:57:17',NULL,NULL);

/*Table structure for table `app_datas_locales` */

DROP TABLE IF EXISTS `app_datas_locales`;

CREATE TABLE `app_datas_locales` (
  `id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` tinyint(3) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT '',
  `phone` varchar(50) COLLATE utf8_unicode_ci DEFAULT '',
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `app_datas_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `app_datas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `app_datas_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_datas_locales` */

insert  into `app_datas_locales`(`id`,`related_table_id`,`email`,`phone`,`language_id`) values (1,1,'info@absconsultor.es','630 892 010',1),(2,1,'info@absconsultor.es','630 892 010',2);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_routes` */

insert  into `app_routes`(`id`,`key`,`visible`,`active`,`order`,`created_at`,`updated_at`,`deleted_at`) values (1,'company','1','1',1,'2016-07-12 23:40:57',NULL,NULL),(2,'jobs','1','1',2,'2016-09-05 23:54:24',NULL,NULL),(3,'blog','1','0',3,'2016-09-05 23:55:24',NULL,NULL),(4,'contact','1','1',4,'2016-09-05 23:56:19',NULL,NULL),(5,'company/colaborators','0','1',5,'2016-09-08 22:16:43',NULL,NULL);

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
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `app_routes_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `app_routes` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `app_routes_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `app_routes_locales` */

insert  into `app_routes_locales`(`id`,`related_table_id`,`name`,`header_name`,`content`,`url_key`,`meta_description`,`language_id`) values (1,1,'Empresa','Empresa','\n<p>ABS Consultor nace formalmente en el a&ntilde;o 2015 y&nbsp;es la heredera de la experiencia y &nbsp;actividad desarrollada de su fundador, Antonio Bravo Silva, desde 1991. La compa&ntilde;&iacute;a se sustenta en un experimentado&nbsp;personal que defiende una filosof&iacute;a de trabajo muy ligada al progreso, la investigaci&oacute;n y la aplicaci&oacute;n&nbsp;generalizada de nuevos protocolos y procesos.</p>\r\n\r\n<p>Nuestra actividad abarca todos los campos relacionados con el control t&eacute;cnico y de calidad en&nbsp;las actividades relacionadas con el montaje mec&aacute;nico.&nbsp;Nuestra sede social se encuentra en el municipio de Camarma de Esteruelas (Madrid) aunque, debido a que los&nbsp;principales trabajos se realizan en puertos mar&iacute;timos, nuestro equipo&nbsp;esta desplazado para poder dar&nbsp;un servicio pr&aacute;cticamente inmediato.</p>\n','empresa','descripción empresa',2),(2,1,'Company','Company','\n<p>ABS Consultor nace formalmente en el a&ntilde;o 2015, aunque en realidad es la heredera de la actividad desarrollada y&nbsp;experiencia de su fundador, Antonio Bravo Silva, desde 1991. La compa&ntilde;&iacute;a se sustenta en un experimentado&nbsp;personal que defiende una filosof&iacute;a de trabajo muy ligada al progreso, la excelencia, la investigaci&oacute;n y la aplicaci&oacute;n&nbsp;generalizada de nuevos protocolos y procesos.</p>\r\n\r\n<p>Nuestra actividad abarca pr&aacute;cticamente todos los campos relacionados con el control t&eacute;cnico y de calidad en todas&nbsp;las actividades relacionadas con el montaje mec&aacute;nico.&nbsp;Nuestra sede social se encuentra en el municipio de Camarma de Esteruelas (Madrid), aunque debido a que los&nbsp;principales trabajos se realizan en puertos mar&iacute;timos, nuestro equipo suele estar desplazado para poder dar&nbsp;un servicio pr&aacute;cticamente inmediato.</p>\n','company','Company description',1),(3,2,'Jobs','Jobs','','jobs','work description',1),(4,2,'Trabajos','Trabajos','','trabajos','descripción trabajos',2),(5,3,'Blog','Blog','','blog','Blog description',1),(6,3,'Blog','Blog','','blog','Descripción blog',2),(7,4,'Contact us','Contact Us',NULL,'contact-us','contact us description',1),(8,4,'Contacto','Contáctanos',NULL,'contacto','Descripción contacto',2),(9,5,'Colaborators','Colaborators','','colaborators','',1),(10,5,'Colaboradores','Colaboradores','','colaboradores','',2);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `blog_categories_locales` */

insert  into `blog_categories_locales`(`id`,`related_table_id`,`title`,`url_key`,`meta_description`,`language_id`) values (1,1,'cat-1','cat-1','cat',2),(2,2,'cat-2','cat-2','asdf',1),(3,2,'cat-2','cat-3','asf',2),(4,1,'cat-1','cat-4','fsfsdfsdf',1);

/*Table structure for table `blog_entries` */

DROP TABLE IF EXISTS `blog_entries`;

CREATE TABLE `blog_entries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `blog_categories_id` int(10) unsigned NOT NULL,
  `key` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8_unicode_ci,
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
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `blog_entries_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `blog_entries` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `blog_entries_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
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

/*Table structure for table `home_modules` */

DROP TABLE IF EXISTS `home_modules`;

CREATE TABLE `home_modules` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules` */

insert  into `home_modules`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'empresa','1','2016-09-18 20:40:46',NULL,NULL),(2,'trabajos','1','2016-09-18 20:55:17',NULL,NULL);

/*Table structure for table `home_modules_locales` */

DROP TABLE IF EXISTS `home_modules_locales`;

CREATE TABLE `home_modules_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8_unicode_ci NOT NULL,
  `content` mediumtext COLLATE utf8_unicode_ci NOT NULL,
  `link_text` varchar(40) COLLATE utf8_unicode_ci DEFAULT NULL,
  `link_url` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `target_link` enum('_self','_blank') COLLATE utf8_unicode_ci DEFAULT NULL,
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `home_modules_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `home_modules` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `home_modules_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `home_modules_locales` */

insert  into `home_modules_locales`(`id`,`related_table_id`,`title`,`image_url`,`content`,`link_text`,`link_url`,`target_link`,`language_id`) values (1,1,'Empresa','[\"\\/media\\/trabajos\\/noatum\\/SAM_0407.JPG\"]','\n<p>ABS Consultor nace formalmente en el a&ntilde;o 2015, aunque en realidad es la heredera de la actividad desarrollada y&nbsp;experiencia de su fundador, Antonio Bravo Silva, desde 1991...</p>\n','Más información','/empresa','_self',2),(2,1,'Company','[\"\\/media\\/trabajos\\/noatum\\/SAM_0407.JPG\"]','\n<p>ABS Consultor nace formalmente en el a&ntilde;o 2015, aunque en realidad es la heredera de la actividad desarrollada y&nbsp;experiencia de su fundador, Antonio Bravo Silva, desde 1991...</p>\n','More info','/company','_self',1),(3,2,'Trabajos','[\"https:\\/\\/www.youtube.com\\/embed\\/xvbYfF11oaw\",\"\\/media\\/trabajos\\/valencia_tcv\\/DSC_0294.JPG\"]','\n<p>Reparaciones, montajes, seguimientos de fabricaci&oacute;n... la calidad distingue a nuestros trabajos gracias a un equipo t&eacute;cnico &aacute;ltamente cualificado.</p>\n','Ver trabajos','/trabajos','_self',2),(4,2,'Jobs','[\"\\/media\\/trabajos\\/valencia_tcv\\/DSC_0294.JPG\"]','\n<p>Reparaciones, montajes, seguimientos de fabricaci&oacute;n... la calidad distingue a nuestros trabajos gracias a un equipo t&eacute;cnico &aacute;ltamente cualificado.</p>\n','View Jobs','/jobs','_self',1);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories` */

insert  into `job_categories`(`id`,`key`,`created_at`,`updated_at`,`deleted_at`,`active`) values (1,'calidad','2016-07-08 20:47:06',NULL,NULL,'1'),(2,'reparaciones','2016-09-11 16:16:16',NULL,NULL,'1'),(3,'montajes','2016-09-11 16:16:48',NULL,NULL,'1'),(4,'seguimiento-fabricacion','2016-09-11 16:18:07',NULL,NULL,'1'),(5,'modificaciones','2016-09-18 19:31:05',NULL,NULL,'1'),(6,'alineaciones-de-acoplamientos-y-motores','2016-09-25 22:25:31',NULL,NULL,'1');

/*Table structure for table `job_categories_locales` */

DROP TABLE IF EXISTS `job_categories_locales`;

CREATE TABLE `job_categories_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `job_categories_locales` */

insert  into `job_categories_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'Calidad','calidad','\n<p>Cualquier tipo de trabajo relacionado con la calidad, como puede ser:</p>\r\n\r\n<ul><li>Realizaci&oacute;n de dosieres de calidad</li>\r\n	<li>Implantaci&oacute;n de ISO 9001-2015</li>\r\n	<li>Seguimiento de calidad en talleres</li>\r\n	<li>Seguimiento de calidad en obras de montaje o reparaci&oacute;n</li>\r\n</ul>\n','Cualquier tipo de trabajo relacionado con la calidad, como puede ser:\r\nRealización de dosieres de calidad\r\nImplantación de ISO 9001-2015\r\nSeguimiento de calidad en talleres\r\nSeguimiento de calidad en obras de montaje o reparación',2),(2,1,'Calidad','calidad-en','','',1),(3,2,'Reparaciones - EN','reparaciones','','',1),(4,2,'Reparaciones','reparaciones','\n<p>Reparaci&oacute;n en estructuras o mecanismos. Se han reparado gr&uacute;as portainers da&ntilde;adas por accidentes, por desgastes de componentes o revisiones.</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\n','Reparación en estructuras o mecanismos. Se han reparado grúas portainers dañadas por accidentes, por desgastes de componentes o revisiones.',2),(5,3,'montajes - EN','montajes','','',1),(6,3,'Montajes','montajes','\n<p>Montaje de &nbsp;gr&uacute;as en su totalidad, desde el inicio, incluidas planimetr&iacute;as. Tambi&eacute;n cualquiera de sus componentes mec&aacute;nicos, incluyendo montajes en talleres colaboradores.</p>\n','Montaje grúas en su totalidad, desde el inicio, incluidas planimetrías. También cualquiera de sus componentes mecánicos, incluyendo montajes en talleres colaboradores.',2),(7,4,'Segumiento de fabricación - EN','seguimiento-de-fabricacion','','',1),(8,4,'Seguimiento de fabricación','seguimiento-de-fabricacion','\n<p>Asesoramiento a talleres en el proceso de fabricaci&oacute;n. Seguimiento de obras para el cumplimiento de PPI y plazos.</p>\r\n\r\n<p>Adem&aacute;s&nbsp;se&nbsp;estudian y redactan&nbsp;procedimientos para la realizaci&oacute;n de trabajos (instrucciones de fabricaci&oacute;n).</p>\r\n\r\n<p>&nbsp;</p>\r\n\r\n<p>&nbsp;</p>\n','Asesoramiento a talleres en el proceso de fabricación. Seguimiento de obras para el cumplimiento de PPI y plazos.\r\nAdemás se estudian y redactan procedimientos para la realización de trabajos (instrucciones de fabricación).',2),(9,5,'Modificaciones','modificaciones','\n<p>Trabajamos en cualquier tipo de modificaci&oacute;n mec&aacute;nica, realizando tambi&eacute;n el procedimiento a seguir.</p>\n','Trabajamos en cualquier tipo de modificación mecánica, realizando también el procedimiento a seguir.',2),(10,5,'modificaciones','modificaciones-en','','modificaciones',1),(11,6,'Alineaciones de acoplamientos y motores','alineaciones-de-acoplamientos-y-motores','\n<p><img alt=\"\" class=\"inline-element inline-element\" src=\"/media/INLINE/alineacion_acoplamiento_motor.jpg\"><br>\r\nLa tecnolog&iacute;a de medici&oacute;n por l&aacute;ser &uacute;nico, permite reducir los errores de rebote y ofrece datos m&aacute;s precisos. Permite completar de un modo r&aacute;pido y sencillo el alineamiento de las m&aacute;quinas.</p>\r\n\r\n<p>Se emite un informe con los datos obtenidos.</p>\n','La tecnología de medición por láser único permite reducir los errores de rebote y ofrece datos más precisos. Permite completar de un modo rápido y sencillo el alineamiento de las máquinas.\r\nSe emite un informe con los datos obtenidos del programa del alineador.',2),(12,6,'Alineaciones de acoplamientos y motores','alineaciones-de-acoplamientos-y-motores','\n<p><img alt=\"\" class=\"inline-element inline-element\" src=\"/media/INLINE/alineacion_acoplamiento_motor.jpg\"><br>\r\nLa tecnolog&iacute;a de medici&oacute;n por l&aacute;ser &uacute;nico permite reducir los errores de rebote y ofrece datos m&aacute;s precisos. Permite completar de un modo r&aacute;pido y sencillo el alineamiento de las m&aacute;quinas.&nbsp;</p>\r\n\r\n<p>La comprobaci&oacute;n din&aacute;mica de la tolerancia de su m&aacute;quina proporciona una evaluaci&oacute;n continua de los ajustes de alineamiento, para que sepa cuando su m&aacute;quina se encuentra en un rango aceptable.&nbsp;</p>\r\n\r\n<p>Se emitir&aacute; un informe con los datos obtenidos del programa del alineador.</p>\n','La tecnología de medición por láser único permite reducir los errores de rebote y ofrece datos más precisos. Permite completar de un modo rápido y sencillo el alineamiento de las máquinas.\r\nLa comprobación dinámica de la tolerancia de su máquina proporciona una evaluación continua de los ajustes de alineamiento, para que sepa cuando su máquina se encuentra en un rango aceptable. Se emitirá un informe con los datos obtenidos del programa del alineador.',1);

/*Table structure for table `jobs` */

DROP TABLE IF EXISTS `jobs`;

CREATE TABLE `jobs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `job_categories_id` int(10) unsigned NOT NULL,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `image_url` text COLLATE utf8_unicode_ci,
  `active` enum('0','1') COLLATE utf8_unicode_ci DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs` */

insert  into `jobs`(`id`,`job_categories_id`,`key`,`image_url`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,2,'las-palmas-luzp0101-eneroabril-2016','[\"\\/media\\/trabajos\\/las_palmas_luz_enero_abril_2016\\/IMG_20160105_092651.jpg\",\"\\/media\\/trabajos\\/las_palmas_luz_enero_abril_2016\\/IMG_20160209_153301.jpg\",\"\\/media\\/trabajos\\/las_palmas_luz_enero_abril_2016\\/IMG_20160328_162414.jpg\"]','1','2016-09-18 17:59:15',NULL,NULL),(2,3,'las-palmas-opc07-09','[\"\\/media\\/trabajos\\/las_palmas_opc_noviembre_febrero_2016\\/IMG_20160119_093000.jpg\",\"\\/media\\/trabajos\\/las_palmas_opc_noviembre_febrero_2016\\/IMG_20160119_094753.jpg\",\"\\/media\\/trabajos\\/las_palmas_opc_noviembre_febrero_2016\\/IMG_20160118_142301.jpg\"]','1','2016-09-18 18:32:00',NULL,NULL),(3,2,'las-palmas-opcp-08','[\"\\/media\\/trabajos\\/las_palmas_opcp08\\/SAM_1026.JPG\",\"\\/media\\/trabajos\\/las_palmas_opcp08\\/IMG_20151010_161452.jpg\",\"\\/media\\/trabajos\\/las_palmas_opcp08\\/SAM_0900.JPG\",\"\\/media\\/trabajos\\/las_palmas_opcp08\\/SAM_1041.JPG\"]','1','2016-09-18 18:43:43',NULL,NULL),(4,5,'noatum-julio-2015','[\"\\/media\\/trabajos\\/noatum\\/SAM_0278.JPG\",\"\\/media\\/trabajos\\/noatum\\/SAM_0367.JPG\",\"\\/media\\/trabajos\\/noatum\\/SAM_0407.JPG\",\"\\/media\\/trabajos\\/noatum\\/SAM_0417.JPG\"]','1','2016-09-18 19:33:08',NULL,NULL),(5,4,'seguimiento-de-fabricacion','[\"\\/media\\/trabajos\\/seguimiento_fabricacion\\/IMG_0275.jpg\",\"\\/media\\/trabajos\\/seguimiento_fabricacion\\/PICT0005.JPG\",\"\\/media\\/trabajos\\/seguimiento_fabricacion\\/SAM_0342.jpg\",\"\\/media\\/trabajos\\/seguimiento_fabricacion\\/PICT0014.JPG\"]','1','2016-09-18 19:48:58',NULL,NULL),(6,3,'valencia-tcvp0102','[\"\\/media\\/trabajos\\/valencia_tcv\\/DSC_0294.JPG\",\"\\/media\\/trabajos\\/valencia_tcv\\/DSC_0399.JPG\",\"\\/media\\/trabajos\\/valencia_tcv\\/Image-6.jpg\",\"https:\\/\\/www.youtube.com\\/embed\\/JtZcKqVzrN0\",\"https:\\/\\/www.youtube.com\\/embed\\/UDOcyglkcJ0\",\"https:\\/\\/www.youtube.com\\/embed\\/xvbYfF11oaw\"]','1','2016-09-18 19:52:32',NULL,NULL);

/*Table structure for table `jobs_locales` */

DROP TABLE IF EXISTS `jobs_locales`;

CREATE TABLE `jobs_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `url_key` (`url_key`,`language_id`),
  KEY `language_id` (`language_id`),
  KEY `jobs_locales_ibfk_1` (`related_table_id`),
  CONSTRAINT `jobs_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `jobs_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `jobs_locales` */

insert  into `jobs_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'Las Palmas Luz','las-palmas-luz','\n<p>La gr&uacute;a sufri&oacute; un accidente por rotura del cable de elevaci&oacute;n y se realiza la siguiente reparaci&oacute;n:</p>\r\n\r\n<ul><li>Sustituci&oacute;n de ejes de carretones</li>\r\n	<li>Cambio de poleas de elevaci&oacute;n</li>\r\n	<li>Reparaci&oacute;n de vigas portal</li>\r\n	<li>Cambio de toda la torniller&iacute;a estructural de la gr&uacute;a.</li>\r\n</ul>\n','Arreglo de grúa por rotura de cable de elevación. Sustitución de ejes de carretones, cambio de poleas de elevación, reparación de vigas portal, cambio de toda la tornilleria estructural de la grúa',2),(2,1,'Las Palmas Luz','las-palmas-luz-en','\n<p>La gr&uacute;a sufri&oacute; un accidente por rotura del cable de elevaci&oacute;n y se realiza la siguiente reparaci&oacute;n:</p>\r\n\r\n<ul><li>Sustituci&oacute;n de ejes de carretones</li>\r\n	<li>Cambio de poleas de elevaci&oacute;n</li>\r\n	<li>Reparaci&oacute;n de vigas portal</li>\r\n	<li>Cambio de toda la torniller&iacute;a estructural de la gr&uacute;a.</li>\r\n</ul>\n','Arreglo de grúa por rotura de cable de elevación. Sustitución de ejes de carretones, cambio de poleas de elevación, reparación de vigas portal, cambio de toda la tornilleria estructural de la grúa',1),(3,2,'las palmas opc07-09','las-palmas-opc07-09','\n<p>Montaje de segunda viga portal y rigidizadores en los nudos de la viga portal antigua, en dos gr&uacute;as portainer.</p>\n','Montaje de segunda viga portal y rigidizadores en los nudos de la viga portal antigua, en dos grúas portainer.',2),(4,2,'las palmas opc07-09','las-palmas-opc07-09-en','\n<p>Montaje de segunda viga portal y rigidizadores en los nudos de la viga portal antigua, en dos gr&uacute;as portainer.</p>\n','Montaje de segunda viga portal y rigidizadores en los nudos de la viga portal antigua, en dos grúas portainer.',1),(5,3,'Las palmas opcp08','las-palmas-opcp08','\n<ul><li>Reparaci&oacute;n por accidente, colisi&oacute;n de barco-gr&uacute;a.</li>\r\n	<li>Reparaci&oacute;n pata 4, reforzando pata y sustituyendo chapas da&ntilde;adas.</li>\r\n	<li>Reparaci&oacute;n de los 4 nudos vigas portales, sustituyendo los nudos atornillados por soldados.</li>\r\n	<li>Colocaci&oacute;n de rigidizadores y diafragmas.</li>\r\n	<li>Alineaci&oacute;n de pluma, corrigiendo la desviaci&oacute;n.</li>\r\n	<li>Colocaci&oacute;n de 2&ordf; viga portal</li>\r\n</ul>\n','Reparación pata cuatro y los nudos de las vigas portales.',2),(6,3,'Las palmas opcp08','las-palmas-opcp08-en','\n<ul><li>Reparaci&oacute;n por accidente, colisi&oacute;n de barco-gr&uacute;a.</li>\r\n	<li>Reparaci&oacute;n pata 4, reforzando pata y sustituyendo chapas da&ntilde;adas.</li>\r\n	<li>Reparaci&oacute;n de los 4 nudos vigas portales, sustituyendo los nudos atornillados por soldados.</li>\r\n	<li>Colocaci&oacute;n de rigidizadores y diafragmas.</li>\r\n	<li>Alineaci&oacute;n de pluma, corrigiendo la desviaci&oacute;n.</li>\r\n	<li>Colocaci&oacute;n de 2&ordf; viga portal</li>\r\n</ul>\n','Reparación por accidente, colisión de barco-grúa.\r\nReparación pata 4, reforzando pata y sustituyendo chapas dañadas.\r\nReparación de los 4 nudos vigas portales, sustituyendo los nudos atornillados por soldados.\r\nColocación de rigidizadores y diafragmas.\r\nAlineación de pluma, corrigiendo la desviación.\r\nColocación de 2ª viga portal',1),(7,4,'noatum 2015','noatum-julio-2015','\n<p>Modificaci&oacute;n de dos gr&uacute;as portainer con un recrecido de seis metros en altura.</p>\r\n\r\n<p>Cambio de tambores de elevaci&oacute;n.</p>\r\n\r\n<p>Refuerzo estructural</p>\n','Modificación de dos grúas portainer con un recrecido de seis metros en altura. Cambio de tambores de elevación. Refuerzo estructural',2),(8,4,'noatum 2015','noatum-2015-en','\n<p>Modificaci&oacute;n de dos gr&uacute;as portainer con un recrecido de seis metros en altura.</p>\r\n\r\n<p>Cambio de tambores de elevaci&oacute;n.</p>\r\n\r\n<p>Refuerzo estructural</p>\n','Modificación de dos grúas portainer con un recrecido de seis metros en altura. Cambio de tambores de elevación. Refuerzo estructural',1),(9,5,'Seguimiento de fabricación ABS','seguimiento-de-fabricacion-abs','','Seguimiento de fabricación ABS',2),(10,5,'Seguimiento de fabricación ABS','en-seguimiento-de-fabricacion-abs','','',1),(11,6,'Valencia TCVP0102','valencia-tcvp0102','\n<p>Montaje completo de dos gr&uacute;as portainer de 52 metros bajo spreader, utilizando JackUp</p>\n','Montaje completo de dos grúas portainer de 55 metros bajo espreader, utilizando JackUp',2),(12,6,'Valencia TCVP0102','valencia-tcvp0102-en','\n<p>Montaje completo de dos gr&uacute;as portainer de 52 metros bajo \"spreader\", utilizando \"JackUp\"</p>\n','Montaje completo de dos grúas portainer de 55 metros bajo espreader, utilizando JackUp',1);

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
  `id` tinyint(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(49) CHARACTER SET utf8 NOT NULL,
  `code` char(5) CHARACTER SET utf8 NOT NULL,
  `visible` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '1',
  `active` enum('0','1') COLLATE utf8_bin NOT NULL DEFAULT '0',
  `order` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

/*Data for the table `languages` */

insert  into `languages`(`id`,`name`,`code`,`visible`,`active`,`order`) values (1,'English','en_en','0','1',2),(2,'Spanish','es_es','1','1',1);

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
  `element_url` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '1',
  `order` int(11) NOT NULL DEFAULT '1',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `megabanners` */

insert  into `megabanners`(`id`,`element_url`,`active`,`order`,`created_at`,`updated_at`,`deleted_at`) values (1,'/media/trabajos/valencia_tcv/Image-6.jpg','1',2,'2016-09-18 20:20:20',NULL,NULL),(2,'/media/trabajos/valencia_tcv/DSC_0399.JPG','1',1,'2016-09-18 20:22:48',NULL,NULL),(3,'/media/trabajos/noatum/SAM_0407.JPG','1',3,'2016-10-25 22:32:47',NULL,NULL);

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `partners` */

insert  into `partners`(`id`,`name`,`logo`,`website`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'Gruas Bonet','/colaboradores/collab1.png','http://www.gruasbonet.es','1','2016-09-10 23:33:36',NULL,NULL),(3,'León Tubos','/colaboradores/collab3.png','http://www.leontubos.com','1','2016-09-10 23:57:03',NULL,NULL),(4,'Silmeca','/colaboradores/collab4.png','http://www.silmecasl.com','1','2016-09-10 23:59:17',NULL,NULL),(5,'Castolin Eutectic','/colaboradores/castolin.png','https://www.castolin.com/es-ES','1','2016-09-18 17:22:53',NULL,NULL),(6,'Teyme','/colaboradores/teyme.png','http://www.teyme.com/','1','2016-09-18 17:25:13',NULL,NULL);

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
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
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
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`,`url_key`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `sections_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `sections` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `sections_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `sections_locales` */

insert  into `sections_locales`(`id`,`related_table_id`,`name`,`header_name`,`content`,`url_key`,`meta_description`,`language_id`) values (1,1,'Empresa','Empresa',NULL,'empresa','descripción empresa',2),(2,1,'Company','Company',NULL,'company','Company description',1),(3,2,'jobs','Jobs',NULL,'jobs','work description',1),(4,2,'Trabajos','Trabajos',NULL,'trabajos','descripción trabajos',2),(5,3,'Blog','Blog',NULL,'blog','Blog description',1),(6,3,'Blog','Blog',NULL,'blog','Descripción blog',2),(7,4,'Contact us','Contact Us',NULL,'contact-us','contact us description',1),(8,4,'Contacto','Contáctanos',NULL,'contacto','Descripción contacto',2);

/*Table structure for table `static_pages` */

DROP TABLE IF EXISTS `static_pages`;

CREATE TABLE `static_pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `active` enum('0','1') COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `key` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages` */

insert  into `static_pages`(`id`,`key`,`active`,`created_at`,`updated_at`,`deleted_at`) values (1,'privacidad','1','2016-07-04 09:18:01',NULL,NULL),(2,'cookies','1','2016-07-07 16:30:23',NULL,NULL),(3,'avisolegal','0','2016-07-07 16:31:17',NULL,NULL);

/*Table structure for table `static_pages_locales` */

DROP TABLE IF EXISTS `static_pages_locales`;

CREATE TABLE `static_pages_locales` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `related_table_id` int(10) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `url_key` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  `language_id` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `related_table_id` (`related_table_id`),
  KEY `language_id` (`language_id`),
  CONSTRAINT `static_pages_locales_ibfk_1` FOREIGN KEY (`related_table_id`) REFERENCES `static_pages` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  CONSTRAINT `static_pages_locales_ibfk_2` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`) ON DELETE NO ACTION ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

/*Data for the table `static_pages_locales` */

insert  into `static_pages_locales`(`id`,`related_table_id`,`title`,`url_key`,`content`,`meta_description`,`language_id`) values (1,1,'Política de privacidad','politica-de-privacidad','\n<p>1. DATOS IDENTIFICATIVOS</p>\r\n\r\n<p>En cumplimiento con el deber de informaci&oacute;n recogido en art&iacute;culo 10 de la Ley 34/2002, de 11 de julio, de Servicios de la Sociedad de la Informaci&oacute;n y del Comercio Electr&oacute;nico, a continuaci&oacute;n se reflejan los siguientes datos: la empresa titular de dominio web es Bravo Silva Consultor&iacute;a T&eacute;cnica (en adelante ABS Consultor), con domicilio a estos efectos en C/ La Ronda 55, 28816, Camarma de Esteruelas n&uacute;mero de C.I.F.: B87453601 inscrita en el Tomo: 34.537, Folio: 1, Secci&oacute;n: 8, Hoja: M-621226, Inscripci&oacute;n: 1. Correo electr&oacute;nico de contacto: info@absconsultor.es del sitio web.</p>\r\n\r\n<p>2. USUARIOS</p>\r\n\r\n<p>El acceso y/o uso de este portal de ABS Consultor atribuye la condici&oacute;n de USUARIO, que acepta, desde dicho acceso y/o uso, las Condiciones Generales de Uso aqu&iacute; reflejadas. Las citadas Condiciones ser&aacute;n de aplicaci&oacute;n independientemente de las Condiciones Generales de Contrataci&oacute;n que en su caso resulten de obligado cumplimiento.</p>\r\n\r\n<p>3. USO DEL PORTAL</p>\r\n\r\n<p>absconsultor.es proporciona el acceso a multitud de informaciones, servicios, programas o datos (en adelante, &ldquo;los contenidos&rdquo;) en Internet pertenecientes a ABS Consultor o a sus licenciantes a los que el USUARIO pueda tener acceso. El USUARIO asume la responsabilidad del uso del portal. Dicha responsabilidad se extiende al registro que fuese necesario para acceder a determinados servicios o contenidos. En dicho registro el USUARIO ser&aacute; responsable de aportar informaci&oacute;n veraz y l&iacute;cita. Como consecuencia de este registro, al USUARIO se le puede proporcionar una contrase&ntilde;a de la que ser&aacute; responsable, comprometi&eacute;ndose a hacer un uso diligente y confidencial de la misma. El USUARIO se compromete a hacer un uso adecuado de los contenidos y servicios (como por ejemplo servicios de chat, foros de discusi&oacute;n o grupos de noticias) que Nombre de la empresa creadora del sitio web ofrece a trav&eacute;s de su portal y con car&aacute;cter enunciativo pero no limitativo, a no emplearlos para (i) incurrir en actividades il&iacute;citas, ilegales o contrarias a la buena fe y al orden p&uacute;blico; (ii) difundir contenidos o propaganda de car&aacute;cter racista, xen&oacute;fobo, pornogr&aacute;fico-ilegal, de apolog&iacute;a del terrorismo o atentatorio contra los derechos humanos; (iii) provocar da&ntilde;os en los sistemas f&iacute;sicos y l&oacute;gicos de Nombre de la empresa creadora del sitio web , de sus proveedores o de terceras personas, introducir o difundir en la red virus inform&aacute;ticos o cualesquiera otros sistemas f&iacute;sicos o l&oacute;gicos que sean susceptibles de provocar los da&ntilde;os anteriormente mencionados; (iv) intentar acceder y, en su caso, utilizar las cuentas de correo electr&oacute;nico de otros usuarios y modificaro manipular sus mensajes. Nombre de la empresa creadora del sitio web se reserva el derecho de retirar todos aquellos comentarios y aportaciones que vulneren el respeto a la dignidad de la persona, que sean discriminatorios, xen&oacute;fobos, racistas, pornogr&aacute;ficos, que atenten contra la juventud o la infancia, el orden o la seguridad p&uacute;blica o que, a su juicio, no resultaran adecuados para su publicaci&oacute;n. En cualquier caso, ABS Consultor no ser&aacute; responsable de las opiniones vertidas por los usuarios a trav&eacute;s de los foros, chats, u otras herramientas de participaci&oacute;n.</p>\r\n\r\n<p>4. PROTECCI&Oacute;N DE DATOS</p>\r\n\r\n<p>ABS Consultor cumple con las directrices de la Ley Org&aacute;nica 15/1999 de 13 de diciembre de Protecci&oacute;n de Datos de Car&aacute;cter Personal, el Real Decreto 1720/2007 de 21 de diciembre por el que se aprueba el Reglamento de desarrollo de la Ley Org&aacute;nica y dem&aacute;s normativa vigente en cada momento, y vela por garantizar un correcto uso y tratamiento de los datos personales del usuario. Para ello, junto a cada formulario de recabo de datos de car&aacute;cter personal, en los servicios que el usuario pueda solicitar a KKKKK, har&aacute; saber al usuario de la existencia y aceptaci&oacute;n de las condiciones particulares del tratamiento de sus datos en cada caso, inform&aacute;ndole de la responsabilidad del fichero creado, la direcci&oacute;n del responsable, la posibilidad de ejercer sus derechos de acceso, rectificaci&oacute;n, cancelaci&oacute;n u oposici&oacute;n, la finalidad del tratamiento y las comunicaciones de datos a terceros en su caso.</p>\r\n\r\n<p>Asimismo, ABS Consultor informa que da cumplimiento a la Ley 34/2002 de 11 de julio, de Servicios de la Sociedad de la Informaci&oacute;n y el Comercio Electr&oacute;nico y le solicitar&aacute; su consentimiento al tratamiento de su correo electr&oacute;nico con fines comerciales en cada momento.</p>\r\n\r\n<p>5. PROPIEDAD INTELECTUAL E INDUSTRIAL</p>\r\n\r\n<p>ABS Consultor por s&iacute; o como cesionaria, es titular de todos los derechos de propiedad intelectual e industrial desu p&aacute;gina web, as&iacute; como de los elementos contenidos en la misma (a t&iacute;tulo enunciativo, im&aacute;genes, sonido, audio, v&iacute;deo, software o textos; marcas o logotipos, combinaciones de colores, estructura y dise&ntilde;o, selecci&oacute;n de materiales usados, programas de ordenador necesarios para su funcionamiento, acceso y uso, etc.), titularidad de ABS Consultor o bien de sus licenciantes.</p>\r\n\r\n<p>Todos los derechos reservados. En virtud de lo dispuesto en los art&iacute;culos 8 y 32.1, p&aacute;rrafo segundo, de la Ley de Propiedad Intelectual, quedan expresamente prohibidas la reproducci&oacute;n, la distribuci&oacute;n y la comunicaci&oacute;n p&uacute;blica, incluida su modalidad de puesta a disposici&oacute;n, de la totalidad o parte de los contenidos de esta p&aacute;gina web, con fines comerciales, en cualquier soporte y por cualquier medio t&eacute;cnico, sin la autorizaci&oacute;n de ABS Consultor. El USUARIO se compromete a respetar los derechos de Propiedad Intelectual e Industrial titularidad de ABS Consultor. Podr&aacute; visualizar los elementos del portal e incluso imprimirlos, copiarlos y almacenarlos en el disco duro de su ordenador o en cualquier otro soporte f&iacute;sico siempre y cuando sea, &uacute;nica y exclusivamente, para su uso personal y privado. El USUARIO deber&aacute; abstenerse de suprimir, alterar, eludir o manipular cualquier dispositivo de protecci&oacute;n o sistema de seguridad que estuviera instalado en el las p&aacute;ginas de ABS Consultor.</p>\r\n\r\n<p>6. EXCLUSI&Oacute;N DE GARANT&Iacute;AS Y RESPONSABILIDAD</p>\r\n\r\n<p>ABS Consultor no se hace responsable, en ning&uacute;n caso, de los da&ntilde;os y perjuicios de cualquier naturaleza que pudieran ocasionar, a t&iacute;tulo enunciativo: errores u omisiones en los contenidos, falta de disponibilidad del portal o la transmisi&oacute;n de virus o programas maliciosos o lesivos en los contenidos, a pesar de haber adoptado todas las medidas tecnol&oacute;gicas necesarias para evitarlo.</p>\r\n\r\n<p>7. MODIFICACIONES</p>\r\n\r\n<p>ABS Consultor se reserva el derecho de efectuar sin previo aviso las modificaciones que considere oportunas en su portal, pudiendocambiar, suprimir o a&ntilde;adir tanto los contenidos y servicios que se presten a trav&eacute;s de la misma como la forma en la que &eacute;stos aparezcan presentados o localizados en su portal.</p>\r\n\r\n<p>8. ENLACES</p>\r\n\r\n<p>En el caso de que en absconsultor.es se dispusiesen enlaces o hiperv&iacute;nculos hac&iacute;a otros sitios de Internet, ABS Consultor no ejercer&aacute; ning&uacute;n tipo de control sobre dichos sitios y contenidos. En ning&uacute;n caso ABS Consultor asumir&aacute; responsabilidad alguna por los contenidos de alg&uacute;n enlace perteneciente a un sitio web ajeno, ni garantizar&aacute; la disponibilidad t&eacute;cnica, calidad, fiabilidad, exactitud, amplitud, veracidad, validez y constitucionalidad de cualquier material o informaci&oacute;n contenida en ninguno de dichos hiperv&iacute;nculos u otros sitios de Internet.</p>\r\n\r\n<p>Igualmente la inclusi&oacute;n de estas conexiones externas no implicar&aacute; ning&uacute;n tipo de asociaci&oacute;n, fusi&oacute;n o participaci&oacute;n con las entidades conectadas.</p>\r\n\r\n<p>9. DERECHO DE EXCLUSI&Oacute;N</p>\r\n\r\n<p>ABS Consultor se reserva el derecho a denegar o retirar el acceso a portal y/o los servicios ofrecidos sin necesidad de preaviso, a instancia propia o de un tercero, a aquellos usuarios que incumplan las presentes Condiciones Generales de Uso.</p>\r\n\r\n<p>10.GENERALIDADES</p>\r\n\r\n<p>ABS Consultor perseguir&aacute; el incumplimiento de las presentes condiciones as&iacute; como cualquier utilizaci&oacute;n indebida de su portal ejerciendo todas las acciones civiles y penales que le puedan corresponder en derecho.</p>\r\n\r\n<p>11.MODIFICACI&Oacute;N DE LAS PRESENTES CONDICIONES Y DURACI&Oacute;N</p>\r\n\r\n<p>ABS Consultor podr&aacute; modificar en cualquier momento las condiciones aqu&iacute; determinadas, siendo debidamente publicadas como aqu&iacute; aparecen.</p>\r\n\r\n<p>La vigencia de las citadas condiciones ir&aacute; en funci&oacute;n de su exposici&oacute;n y estar&aacute;n vigentes hasta debidamente publicadas. que sean modificadas por otras.</p>\r\n\r\n<p>12. LEGISLACI&Oacute;N APLICABLE Y JURISDICCI&Oacute;N</p>\r\n\r\n<p>La relaci&oacute;n entre ABS Consultor y el USUARIO se regir&aacute; por la normativa espa&ntilde;ola vigente y cualquier controversia se someter&aacute; a los Juzgados y tribunales de la ciudad de Madrid.</p>\n','ABS Consultor - Política de privacidad',2),(2,2,'Cookies','cookies','\n<p>Cookie&nbsp;es un fichero que se descarga en su ordenador al acceder a determinadas p&aacute;ginas web. Las cookies permiten a una p&aacute;gina web, entre otras cosas, almacenar y recuperar informaci&oacute;n sobre los h&aacute;bitos de navegaci&oacute;n de un usuario o de su equipo y, dependiendo de la informaci&oacute;n que contengan y de la forma en que utilice su equipo, pueden utilizarse para reconocer al usuario.. El navegador del usuario memoriza cookies en el disco duro solamente durante la sesi&oacute;n actual ocupando un espacio de memoria m&iacute;nimo y no perjudicando al ordenador. Las cookies no contienen ninguna clase de informaci&oacute;n personal espec&iacute;fica, y la mayor&iacute;a de las mismas se borran del disco duro al finalizar la sesi&oacute;n de navegador (las denominadas cookies de sesi&oacute;n).</p>\r\n\r\n<p>La mayor&iacute;a de los navegadores aceptan como est&aacute;ndar a las cookies y, con independencia de las mismas, permiten o impiden en los ajustes de seguridad las cookies temporales o memorizadas.</p>\r\n\r\n<p>Sin su expreso consentimiento &ndash;mediante la activaci&oacute;n de las cookies en su navegador&ndash; absconsultor.es no enlazar&aacute; en las cookies los datos memorizados con sus datos personales proporcionados en el momento del registro o la compra..</p>\r\n\r\n<p><strong>&iquest;Qu&eacute; tipos de cookies utiliza esta p&aacute;gina web?</strong></p>\r\n\r\n<p>- Cookies&nbsp;t&eacute;cnicas: Son aqu&eacute;llas que permiten al usuario la navegaci&oacute;n a trav&eacute;s de una p&aacute;gina web, plataforma o aplicaci&oacute;n y la utilizaci&oacute;n de las diferentes opciones o servicios que en ella existan como, por ejemplo, controlar el tr&aacute;fico y la comunicaci&oacute;n de datos, identificar la sesi&oacute;n, acceder a partes de acceso restringido, recordar los elementos que integran un pedido, realizar el proceso de compra de un pedido, realizar la solicitud de inscripci&oacute;n o participaci&oacute;n en un evento, utilizar elementos de seguridad durante la navegaci&oacute;n, almacenar contenidos para la difusi&oacute;n de videos o sonido o compartir contenidos a trav&eacute;s de redes sociales.</p>\r\n\r\n<p><strong>Cookies de terceros</strong>: La Web de absconsultor.es puede utilizar&nbsp;servicios de terceros que recopilaran informaci&oacute;n con fines estad&iacute;sticos, de uso del Site por parte del usuario y para la prestacion de otros servicios relacionados con la actividad del Website y otros servicios de Internet.</p>\r\n\r\n<p>En particular, este sitio Web utiliza los siguientes servicios:</p>\r\n\r\n<p>Google Analytics, un servicio anal&iacute;tico de web prestado por Google, Inc. con domicilio en los Estados Unidos con sede central en 1600 Amphitheatre Parkway, Mountain View, California 94043.&nbsp; Para la prestaci&oacute;n de estos servicios, estos&nbsp;utilizan cookies que recopilan la informaci&oacute;n, incluida la direcci&oacute;n IP del usuario, que ser&aacute;&nbsp;transmitida, tratada y almacenada por&nbsp;Google&nbsp;en los t&eacute;rminos fijados en la Web Google.com. Incluyendo la posible&nbsp;transmisi&oacute;n de dicha informaci&oacute;n a terceros por razones de exigencia legal o cuando dichos terceros&nbsp;procesen la informaci&oacute;n por cuenta de Google.</p>\r\n\r\n<p><strong>El Usuario acepta expresamente, por la utilizaci&oacute;n de este Site,&nbsp;el tratamiento de la informaci&oacute;n&nbsp;recabada en la forma y con los fines anteriormente mencionados.</strong>&nbsp;Y asimismo reconoce conocer la posibilidad de rechazar el tratamiento de&nbsp;tales datos o informaci&oacute;n&nbsp;rechazando el uso de Cookies mediante la selecci&oacute;n de la configuraci&oacute;n apropiada a tal fin en su navegador. Si bien esta opci&oacute;n de bloqueo de Cookies en su navegador puede no permitirle el uso pleno de todas las funcionalidades del Website.</p>\r\n\r\n<p>Puede usted permitir, bloquear o eliminar las cookies instaladas en su equipo mediante la configuraci&oacute;n de las opciones del navegador instalado en su ordenador:</p>\r\n\r\n<ul><li><a href=\"http://support.google.com/chrome/bin/answer.py?hl=es&amp;answer=95647\" target=\"_blank\">Chrome</a></li>\r\n	<li><a href=\"http://windows.microsoft.com/es-es/windows7/how-to-manage-cookies-in-internet-explorer-9\" target=\"_blank\">Explorer</a></li>\r\n	<li><a href=\"http://support.mozilla.org/es/kb/habilitar-y-deshabilitar-cookies-que-los-sitios-we\" target=\"_blank\">Firefox</a></li>\r\n	<li><a href=\"http://support.apple.com/kb/ph5042\" target=\"_blank\">Safari</a></li>\r\n</ul><p>Si tiene dudas sobre esta pol&iacute;tica de cookies, puede contactar con ABS Consutlro en info@absconsultor.es</p>\n','Política de tratamiento de cookies.',2),(3,3,'Aviso Legal','aviso-legal','','',2),(4,1,'Privacy policy','privacy-policy','','',1),(5,2,'Cookies','cookies','','',1),(6,3,'Legal Notice','legal-notice','','',1);

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

/*Table structure for table `youtube_videos` */

DROP TABLE IF EXISTS `youtube_videos`;

CREATE TABLE `youtube_videos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `visibility` enum('public','private','unlisted') DEFAULT 'public',
  `code` varchar(20) DEFAULT NULL,
  `channel_id` varchar(100) DEFAULT NULL,
  `channel_title` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=260 DEFAULT CHARSET=utf8;

/*Data for the table `youtube_videos` */

insert  into `youtube_videos`(`id`,`title`,`description`,`visibility`,`code`,`channel_id`,`channel_title`,`created_at`) values (256,'VALENCIA TCVP0102-1','Montaje completo de dos grúas portainer de 52 metros bajo spreader, utilizando JackUp','unlisted','xvbYfF11oaw','UCjTemr2faNCFkd2wch2RxfQ','ABS Consultor','2016-10-30 17:16:33'),(257,'VALENCIA TCVP0102','Montaje completo de dos grúas portainer de 52 metros bajo spreader, utilizando JackUp','unlisted','UDOcyglkcJ0','UCjTemr2faNCFkd2wch2RxfQ','ABS Consultor','2016-10-30 17:16:33'),(258,'VALENCIA TCVP0102','Montaje completo de dos grúas portainer de 52 metros bajo spreader, utilizando JackUp','unlisted','JtZcKqVzrN0','UCjTemr2faNCFkd2wch2RxfQ','ABS Consultor','2016-10-30 17:16:33');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
