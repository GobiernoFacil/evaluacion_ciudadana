-- phpMyAdmin SQL Dump
-- version 4.1.12
-- http://www.phpmyadmin.net
--
-- Servidor: localhost:8889
-- Tiempo de generación: 17-03-2015 a las 18:53:25
-- Versión del servidor: 5.5.34
-- Versión de PHP: 5.5.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de datos: `agentes`
--
CREATE DATABASE IF NOT EXISTS `agentes` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `agentes`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `applicants`
--

DROP TABLE IF EXISTS `applicants`;
CREATE TABLE `applicants` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `blueprint_id` int(11) NOT NULL,
  `user_email` varchar(128) CHARACTER SET latin1 NOT NULL,
  `form_key` varchar(128) CHARACTER SET latin1 NOT NULL,
  `is_over` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Truncar tablas antes de insertar `applicants`
--

TRUNCATE TABLE `applicants`;
--
-- Volcado de datos para la tabla `applicants`
--

INSERT INTO `applicants` (`id`, `blueprint_id`, `user_email`, `form_key`, `is_over`) VALUES
(8, 1, 'natasha.pizzey@gmail.com', 'ba198a0cafb12894b5382d6d2a213882', 0),
(9, 1, 'kerry@reboot.org', '210f0a7cb8e5f42f5cc3c2dfc68e3076', 0),
(10, 1, 'leticia@crea.org.mx', 'e3dc4a8d3bc94084b91e9ae252f3937f', 0),
(11, 1, 'alejandra.ruizdelrio@presidencia.gob.mx', '27d39bce5b7813dddb215731f112ded2', 0),
(12, 1, 'rogelio.grados@prospera.gob.mx', '3c3f41cdc224ce448311305cd85ec2e7', 0),
(13, 1, 'alejandro.silva@prospera.gob.mx', 'eb94df538350e277cbb6abefb46e2a2f', 0),
(14, 1, 'fabaguz@gmail.com', '5a110dba38353405dd7a135a7db2355d', 0),
(15, 1, 'aura_martinezo@hacienda.gob.mx', '8c1304634df53212450eb26df976dd9a', 0),
(16, 1, 'hugo@gobiernofacil.com', '3b819e36d53629523d15e2701cfc6d98', 0),
(17, 1, 'nubia.lizth@gmail.com', 'ade1a4f9f16366a293069211f98c3c71', 0),
(18, 1, 'boris@gobiernofacil.com', 'd4f999b9f0052ada8d7cf93e9a3dd98b', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `questions`
--

DROP TABLE IF EXISTS `questions`;
CREATE TABLE `questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `section_id` int(11) NOT NULL,
  `blueprint_id` int(11) NOT NULL,
  `question` text CHARACTER SET latin1 NOT NULL,
  `is_description` tinyint(1) NOT NULL,
  `is_location` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('text','number') CHARACTER SET latin1 NOT NULL DEFAULT 'text',
  `order_num` int(11) DEFAULT NULL,
  `default_value` int(11) DEFAULT NULL,
  `creation_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Truncar tablas antes de insertar `questions`
--

TRUNCATE TABLE `questions`;
--
-- Volcado de datos para la tabla `questions`
--

INSERT INTO `questions` (`id`, `section_id`, `blueprint_id`, `question`, `is_description`, `is_location`, `type`, `order_num`, `default_value`, `creation_date`) VALUES
(1, 1, 1, '<p>&iexcl;Hola! Muchas gracias por participar en este estudio. Tu opini&oacute;n es muy importante pues permitir&aacute; que Prospera conozca y trabaje sobre los aspectos que consideras necesarios para mejorar la forma en que el programa atiende tus necesidades. Gracias a tu participaci&oacute;n haremos de Prospera un programa m&aacute;s cercano a los j&oacute;venes mexicanos.</p><p>El estudio tiene cuatro componentes principales. En primer lugar, te pedimos que nos proporciones algunos datos generales sobre ti. En segundo lugar, te pediremos que nos ayudes a contar con informaci&oacute;n relativa al ambiente escolar en el que te desenvuelves. En tercer lugar, si eres beneficiario de los apoyos de Prospera, podr&aacute;s calificar tu experiencia con el Programa. Finalmente, te haremos algunas preguntas sobre el portal &iexcl;Vas! A mover a M&eacute;xico. Toda la informaci&oacute;n que nos proporciones ser&aacute; confidencial y utilizada &uacute;nicamente con la finalidad de atender de manera m&aacute;s adecuada tus necesidades.</p>', 1, 0, 'text', 1, 0, '2015-02-23 18:14:59'),
(3, 2, 1, '&iquest;Cu&aacute;l es tu edad?', 0, 0, 'number', 1, NULL, '2015-02-23 22:11:53'),
(4, 2, 1, 'Sexo', 0, 0, 'number', 2, NULL, '2015-02-23 22:13:31'),
(5, 2, 1, '&iquest;D&oacute;nde vives? (localidad, municipio y estado)', 0, 1, 'text', 3, NULL, '2015-02-23 22:20:18'),
(6, 2, 1, 'En la actualidad &iquest;A qu&eacute; te dedicas?', 0, 0, 'number', 4, NULL, '2015-02-23 22:22:54'),
(7, 3, 1, 'El trato que recibes por parte de tus profesores es:', 0, 0, 'number', 1, NULL, '2015-02-23 22:27:33'),
(8, 3, 1, '&iquest;Tu escuela cuenta con todas las instalaciones y espacios que necesitas?', 0, 0, 'number', 2, NULL, '2015-02-23 22:28:33'),
(9, 3, 1, 'En tu sal&oacute;n de clases &iquest;Existe un buen ambiente con tus compa&ntilde;eros?', 0, 0, 'number', 3, NULL, '2015-02-23 22:30:55'),
(10, 3, 1, 'En tu escuela &iquest;Practicas lo que te ense&ntilde;an?', 0, 0, 'number', 4, NULL, '2015-02-23 22:31:25'),
(11, 3, 1, 'Lo que te han ense&ntilde;ado en la escuela ha sido:', 0, 0, 'number', 5, NULL, '2015-02-23 22:31:51'),
(12, 4, 1, '&iquest;Recibes actualmente una beca para estudiar por parte de Prospera?', 0, 0, 'number', 1, NULL, '2015-02-24 18:48:50'),
(13, 5, 1, '&iquest;Faltas a la escuela debido a que no recibes tu beca a tiempo?', 0, 0, 'number', 1, NULL, '2015-02-24 18:56:17'),
(14, 5, 1, 'Con la beca que recibes:', 0, 0, 'number', 2, NULL, '2015-02-24 18:56:17'),
(15, 5, 1, '&iquest;Aunque tienes beca debes trabajar para ayudar a tu familia?', 0, 0, 'number', 3, NULL, '2015-02-24 18:56:17'),
(16, 5, 1, '&iquest;Es f&aacute;cil cobrar tu beca?', 0, 0, 'number', 4, NULL, '2015-02-24 18:56:17'),
(17, 5, 1, '&iquest;Aunque recibes una beca has pensado en abandonar la escuela?', 0, 0, 'number', 5, NULL, '2015-02-24 18:56:17'),
(18, 5, 1, '&iquest;Sabes exactamente cu&aacute;nto dinero recibes por la beca?', 0, 0, 'number', 6, NULL, '2015-02-24 18:56:17'),
(19, 5, 1, '&iquest;Sabes cu&aacute;les son  las condiciones que debes cumplir para seguir recibiendo tu beca?', 0, 0, 'number', 7, NULL, '2015-02-24 18:56:17'),
(20, 5, 1, '&iquest;Recibes frecuentemente informaci&oacute;n sobre las becas que ofrece Prospera?', 0, 0, 'number', 8, NULL, '2015-02-24 18:56:17'),
(21, 6, 1, 'Una vez que termines la escuela &iquest;A qu&eacute; te dedicar&aacute;s?', 0, 0, 'number', 1, NULL, '2015-02-24 18:59:11'),
(22, 7, 1, '&iquest;Has recibido informaci&oacute;n sobre el apoyo “J&oacute;venes con Prospera”?', 0, 0, 'number', 1, NULL, '2015-02-24 19:01:21'),
(23, 7, 1, 'La informaci&oacute;n que has recibido sobre “J&oacute;venes con Prospera” es:', 0, 0, 'number', 2, NULL, '2015-02-24 19:01:21'),
(24, 7, 1, '&iquest;Ha sido &uacute;til para ti la informaci&oacute;n recibida sobre “J&oacute;venes con Prospera”?', 0, 0, 'number', 3, NULL, '2015-02-24 19:01:21'),
(25, 8, 1, '&iquest;Recibiste el apoyo de “J&oacute;venes con Prospera” (antes “J&oacute;venes con Oportunidades”)?', -100, 0, 'number', 1, NULL, '2015-02-24 19:02:57'),
(26, 9, 1, '&iquest;Recibiste a tiempo el apoyo de “J&oacute;venes con Prospera”?', 0, 0, 'number', 1, NULL, '2015-02-24 19:06:31'),
(27, 9, 1, '&iquest;Fue f&aacute;cil obtener el apoyo de “J&oacute;venes con Prospera”?', 0, 0, 'number', 2, NULL, '2015-02-24 19:06:31'),
(28, 9, 1, '&iquest;Dedicaste el apoyo recibido a la continuaci&oacute;n de tus estudios?', 0, 0, 'number', 3, NULL, '2015-02-24 19:06:31'),
(29, 9, 1, '&iquest;El apoyo te permiti&oacute; cubrir los gastos para acceder a la universidad?', 0, 0, 'number', 4, NULL, '2015-02-24 19:06:31'),
(30, 9, 1, '&iquest;Suspendiste tus estudios universitarios por falta de recursos?', 0, 0, 'number', 5, NULL, '2015-02-24 19:06:31'),
(31, 10, 1, 'En la actualidad &iquest;A qu&eacute; te dedicas?', 0, 0, 'number', 1, NULL, '2015-02-24 19:07:37'),
(32, 11, 1, '&iquest;Recibes &uacute;tiles escolares por parte de Prospera?', 0, 0, 'number', 1, NULL, '2015-02-24 19:08:17'),
(33, 12, 1, '&iquest;Recibes los &uacute;tiles escolares antes de comenzar el ciclo escolar?', 0, 0, 'number', 1, NULL, '2015-02-24 21:45:04'),
(34, 12, 1, 'Los &uacute;tiles escolares que recibes &iquest;Son los que necesitas en la escuela?', 0, 0, 'number', 2, NULL, '2015-02-24 21:45:04'),
(35, 12, 1, '&iquest;T&uacute; o tus padres deben gastar para que tengas todos los &uacute;tiles que ocupas en la escuela?', 0, 0, 'number', 3, NULL, '2015-02-24 21:45:04'),
(36, 12, 1, 'Los &uacute;tiles que recibes:', 0, 0, 'number', 4, NULL, '2015-02-24 21:45:04'),
(37, 12, 1, 'Los &uacute;tiles que recibes:', 0, 0, 'number', 5, NULL, '2015-02-24 21:45:04'),
(38, 13, 1, '&iquest;Recibes o has recibido los cursos de capacitaci&oacute;n para el autocuidado de la salud que otorga Prospera?', 0, 0, 'number', 1, NULL, '2015-02-24 21:45:44'),
(39, 14, 1, 'Las capacitaciones para el cuidado de la salud se llevan a cabo:', 0, 0, 'number', 1, NULL, '2015-02-24 21:48:35'),
(40, 14, 1, 'La capacitaciones para el cuidado de la salud han sido:', 0, 0, 'number', 2, NULL, '2015-02-24 21:48:35'),
(41, 14, 1, '&iquest;Es f&aacute;cil acceder a las capacitaciones que imparten los centros de salud?', 0, 0, 'number', 3, NULL, '2015-02-24 21:48:35'),
(42, 14, 1, '&iquest;Entiendes la informaci&oacute;n que recibes en las capacitaciones?', 0, 0, 'number', 4, NULL, '2015-02-24 21:48:35'),
(43, 14, 1, '&iquest;Puedes atender los consejos para el cuidado de salud recibidos en las capacitaciones?', 0, 0, 'number', 5, NULL, '2015-02-24 21:48:35'),
(44, 15, 1, 'La informaci&oacute;n del portal ¡Vas! es:', 0, 0, 'number', 1, NULL, '2015-02-24 21:52:41'),
(45, 15, 1, '&iquest;Es f&aacute;cil acceder a la informaci&oacute;n que necesitas?', 0, 0, 'number', 2, NULL, '2015-02-24 21:52:41'),
(46, 15, 1, '&iquest;La informaci&oacute;n que proporciona el portal ¡Vas! se presenta con claridad?', 0, 0, 'number', 3, NULL, '2015-02-24 21:52:41'),
(47, 15, 1, 'Qu&eacute; te parece el dise&ntilde;o del portal ¡Vas!', 0, 0, 'number', 4, NULL, '2015-02-24 21:52:41'),
(48, 15, 1, 'Has conseguido alg&uacute;n apoyo gracias al portal ¡Vas!', 0, 0, 'number', 5, NULL, '2015-02-24 21:52:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `small_vas`
--

DROP TABLE IF EXISTS `small_vas`;
CREATE TABLE `small_vas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(256) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Truncar tablas antes de insertar `small_vas`
--

TRUNCATE TABLE `small_vas`;
--
-- Volcado de datos para la tabla `small_vas`
--

INSERT INTO `small_vas` (`id`, `name`, `email`) VALUES
(1, 'Natasha', 'natasha.pizzey@gmail.com'),
(2, 'Kerry', 'kerry@reboot.org'),
(3, 'LETICIA', 'leticia@crea.org.mx'),
(4, 'ALEJANDRA', 'alejandra.ruizdelrio@presidencia.gob.mx'),
(5, 'ROGELIO', 'rogelio.grados@prospera.gob.mx'),
(6, 'ALDO', 'alejandro.silva@prospera.gob.mx'),
(7, 'FRANCISCO', 'fabaguz@gmail.com'),
(8, 'AURA', 'aura_martinezo@hacienda.gob.mx'),
(9, 'Hugo', 'hugo@gobiernofacil.com'),
(10, 'NUBIA', 'nubia.lizth@gmail.com'),
(11, 'boris', 'boris@gobiernofacil.com');
