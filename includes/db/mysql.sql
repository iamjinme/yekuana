-- default charset

SET NAMES 'utf8';
SET CHARSET 'utf8';

--
-- Table structure for table `datalists`
--

CREATE TABLE `prefix_datalists` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `name` varchar(32) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY  (`id`,`name`)
);

INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('conference_name', 'My Event');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('conference_link', 'http://www.my-event.org');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('adminmail', 'admin@my-event.org');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('general_mail', 'noreply@my-event.org');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('limite', '100');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('def_hora_ini', '8');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('def_hora_fin', '22');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('max_inscripcionTA', '2');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('max_inscripcionTU', '3');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('send_mail', '0');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('smtp', '');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('wwwroot', '');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('unique_mail', '0');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('clean_url', '0');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('public_proposals', '1');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('public_schedule', '1');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('auth', '');
INSERT INTO `prefix_datalists`(`name`,`value`) VALUES ('locale', '');

--
-- Table structure for table `administrador`
--

CREATE TABLE `prefix_administrador` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(15) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `nombrep` varchar(50) NOT NULL default '',
  `apellidos` varchar(50) NOT NULL default '',
  `mail` varchar(100) NOT NULL default '',
  `id_tadmin` int(10) NOT NULL default '0',
  PRIMARY KEY  (`id`,`login`)
);

-- Default user:   admin
-- Default passwd: admin

INSERT INTO `prefix_administrador`(`login`,`passwd`,`nombrep`,`apellidos`,`mail`,`id_tadmin`) VALUES ('admin',md5('admin'),'Administrador','Principal','admin@softwarelibre.org.bo',1);

--
-- Table structure for table `asistente`
--

CREATE TABLE `prefix_asistente` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(15) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `nombrep` varchar(50) NOT NULL default '',
  `apellidos` varchar(50) NOT NULL default '',
  `sexo` char(1) NOT NULL default '',
  `mail` varchar(100) NOT NULL default '',
  `ciudad` varchar(100) default NULL,
  `org` varchar(100) default NULL,
  `fecha_nac` date default NULL,
  `asistencia` tinyint(4) default '0',
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `act_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `id_estudios` int(10) unsigned NOT NULL default '0',
  `id_tasistente` int(10) unsigned NOT NULL default '0',
  `id_estado` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`login`)
);

--
-- Table structure for table `config`
--

CREATE TABLE `prefix_config` (
  `id` int(11) NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  `status` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`)
);

-- All open by default 
-- 1 Open 0 Close 

INSERT INTO `prefix_config`(`descr`,`status`) VALUES ('Registro ponentes',1);
INSERT INTO `prefix_config`(`descr`,`status`) VALUES ('Registro asistentes',1);
INSERT INTO `prefix_config`(`descr`,`status`) VALUES ('Registro ponencias',1);
INSERT INTO `prefix_config`(`descr`,`status`) VALUES ('Inscripción Talleres',1);

--
-- Table structure for table `estado`
--

CREATE TABLE `prefix_estado` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_estado`(`descr`) VALUES ('Lara');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Yaracuy');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Portuguesa');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Falcón');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Zulia');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Carabobo');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Merida');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Maracay');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Caracas');
INSERT INTO `prefix_estado`(`descr`) VALUES ('Otro');

--
-- Table structure for table `estudios`
--

CREATE TABLE `prefix_estudios` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_estudios`(`descr`) VALUES ('Básico');
INSERT INTO `prefix_estudios`(`descr`) VALUES ('Técnico');
INSERT INTO `prefix_estudios`(`descr`) VALUES ('Licenciatura');
INSERT INTO `prefix_estudios`(`descr`) VALUES ('Ingenieria');
INSERT INTO `prefix_estudios`(`descr`) VALUES ('Maestría');
INSERT INTO `prefix_estudios`(`descr`) VALUES ('Doctorado');

--
-- Table structure for table `evento`
--

CREATE TABLE `prefix_evento` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `id_propuesta` int(10) unsigned NOT NULL default '0',
  `id_administrador` int(10) unsigned NOT NULL default '0',
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `act_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`,`id_propuesta`,`id_administrador`)
);

--
-- Table structure for table `evento_ocupa`
--

CREATE TABLE `prefix_evento_ocupa` (
  `id_evento` tinyint(4) NOT NULL default '0',
  `hora` tinyint(4) NOT NULL default '0',
  `id_fecha` int(11) NOT NULL default '0',
  `id_lugar` int(11) NOT NULL default '0',
  PRIMARY KEY  (`hora`,`id_fecha`,`id_lugar`,`id_evento`)
);

--
-- Table structure for table `extauth_hash`
--

CREATE TABLE `prefix_extauth_hash` (
  `id` int(11) NOT NULL auto_increment,
  `login` varchar(15) NOT NULL default '',
  `hash` varchar(32) NOT NULL default '',
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
);

--
-- Table structure for table `fecha_evento`
--

CREATE TABLE `prefix_fecha_evento` (
  `id` int(11) NOT NULL auto_increment,
  `fecha` date default NULL,
  `descr` varchar(50) default NULL,
  PRIMARY KEY  (`id`)
);

--
-- Table structure for table `inscribe`
--

CREATE TABLE `prefix_inscribe` (
  `id_asistente` int(10) unsigned NOT NULL default '0',
  `id_evento` int(10) unsigned NOT NULL default '0',
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `act_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id_asistente`,`id_evento`)
);

--
-- Table structure for table `lugar`
--

CREATE TABLE `prefix_lugar` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `cupo` int(11) NOT NULL default '99999',
  `nombre_lug` varchar(100) NOT NULL default '',
  `ubicacion` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

--
-- Table structure for table `orientacion`
--

CREATE TABLE `prefix_orientacion` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Desarrollo de Software');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Seguridad y Redes');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Aplicaciones');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Principiantes');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Software Libre');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Negocios y Linux');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Educación');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Juegos y Entretenimiento');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Protocolo del evento');
INSERT INTO `prefix_orientacion`(`descr`) VALUES ('Otro');

--
-- Table structure for table `ponente`
--

CREATE TABLE `prefix_ponente` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `login` varchar(15) NOT NULL default '',
  `passwd` varchar(32) NOT NULL default '',
  `nombrep` varchar(50) NOT NULL default '',
  `apellidos` varchar(50) NOT NULL default '',
  `sexo` char(1) NOT NULL default '',
  `mail` varchar(100) NOT NULL default '',
  `ciudad` varchar(100) default NULL,
  `org` varchar(100) default NULL,
  `titulo` varchar(50) default NULL,
  `resume` text,
  `domicilio` varchar(255) default NULL,
  `telefono` varchar(100) NOT NULL default '',
  `fecha_nac` date default NULL,
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `act_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `id_estudios` int(10) unsigned NOT NULL default '0',
  `id_estado` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`,`login`)
);

--
-- Table structure for table `prop_comments`
--

CREATE TABLE `prefix_prop_comments` (
  `id` int(11) NOT NULL auto_increment,
  `id_propuesta` int(11) NOT NULL default '0',
  `login` varchar(15) NOT NULL,
  `author_type` tinyint(4) NOT NULL,
  `body` varchar(255) NOT NULL,
  `time` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`),
  KEY `id_propuesta` (`id_propuesta`)
);

--
-- Table structure for table `prop_files`
--

CREATE TABLE `prefix_prop_files` (
  `id` int(11) NOT NULL auto_increment,
  `id_propuesta` int(11) NOT NULL default '0',
  `name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL default '',
  `descr` varchar(255) NOT NULL default '',
  `public` tinyint(4) NOT NULL default '0',
  `size` int(11) NOT NULL default '0',
  `reg_time` int(11) NOT NULL default '0',
  `deleted` tinyint(4) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `id_propuesta` (`id_propuesta`)
);

--
-- Table structure for table `prop_nivel`
--

CREATE TABLE `prefix_prop_nivel` (
  `id` int(11) NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

-- Modify or add options as you need

INSERT INTO `prefix_prop_nivel`(`descr`) VALUES ('Básico');
INSERT INTO `prefix_prop_nivel`(`descr`) VALUES ('Intermedio');
INSERT INTO `prefix_prop_nivel`(`descr`) VALUES ('Avanzado');

--
-- Table structure for table `prop_status`
--

CREATE TABLE `prefix_prop_status` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Nueva');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Detalles Requeridos');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Rechazada');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Por Aceptar');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Aceptada');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Cancelada');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Eliminada');
INSERT INTO `prefix_prop_status`(`descr`) VALUES ('Programada');

--
-- Table structure for table `prop_tipo`
--

CREATE TABLE `prefix_prop_tipo` (
  `id` int(10) unsigned NOT NULL default '0',
  `descr` varchar(100) NOT NULL default '',
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_prop_tipo` VALUES (1, 'Conferencia');
INSERT INTO `prefix_prop_tipo` VALUES (50, 'Taller');
INSERT INTO `prefix_prop_tipo` VALUES (51, 'Tutorial');
INSERT INTO `prefix_prop_tipo` VALUES (100, 'Clase Magistral');
INSERT INTO `prefix_prop_tipo` VALUES (101, 'Evento de Organización');
INSERT INTO `prefix_prop_tipo` VALUES (2, 'Plática Informal');

--
-- Table structure for table `propuesta`
--

CREATE TABLE `prefix_propuesta` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `nombre` varchar(150) NOT NULL default '',
  `id_nivel` int(10) NOT NULL default '0',
  `duracion` int(10) unsigned NOT NULL default '0',
  `resumen` text NOT NULL,
  `reqtecnicos` text,
  `reqasistente` text,
  `id_ponente` int(10) unsigned NOT NULL default '0',
  `id_prop_tipo` int(10) unsigned NOT NULL default '0',
  `id_administrador` int(10) unsigned NOT NULL default '0',
  `id_orientacion` int(10) unsigned NOT NULL default '0',
  `id_status` int(10) unsigned NOT NULL default '1',
  `reg_time` datetime NOT NULL default '0000-00-00 00:00:00',
  `act_time` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
  `nombreFile` varchar(100) default NULL,
  `tipoFile` varchar(25) default NULL,
  `dirFile` varchar(255) default NULL,
  PRIMARY KEY  (`id`,`id_ponente`,`id_administrador`,`id_orientacion`,`id_status`,`id_prop_tipo`)
);

--
-- Table structure for table `tadmin`
--

CREATE TABLE `prefix_tadmin` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  `tareas` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_tadmin`(`descr`) VALUES ('Total');
INSERT INTO `prefix_tadmin`(`descr`) VALUES ('Parcial');
INSERT INTO `prefix_tadmin`(`descr`) VALUES ('Evaluador');

--
-- Table structure for table `tasistente`
--

CREATE TABLE `prefix_tasistente` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `descr` varchar(100) default NULL,
  PRIMARY KEY  (`id`)
);

INSERT INTO `prefix_tasistente` VALUES (1,'Estudiante');
INSERT INTO `prefix_tasistente` VALUES (101,'Voluntario');
INSERT INTO `prefix_tasistente` VALUES (102,'Organizador');
INSERT INTO `prefix_tasistente` VALUES (2,'Académico');
INSERT INTO `prefix_tasistente` VALUES (3,'Empresa');
INSERT INTO `prefix_tasistente` VALUES (4,'Otro');
