-- phpMyAdmin SQL Dump
-- version 4.0.8
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 10-12-2014 a las 17:23:52
-- Versión del servidor: 10.0.13-MariaDB
-- Versión de PHP: 5.5.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `grupo_13`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento`
--

CREATE TABLE IF NOT EXISTS `alimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `codigo` varchar(30) NOT NULL,
  `descripcion` varchar(100) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  `stock_total` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`codigo`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=65 ;

--
-- Volcado de datos para la tabla `alimento`
--

INSERT INTO `alimento` (`id`, `codigo`, `descripcion`, `baja`, `stock_total`) VALUES
(55, 'AZUCAR_1KG', 'Ledesma', 0, 0),
(61, 'BC_FRUTOS_ROJOS_390GRS', 'La Campagnola', 0, 0),
(63, 'GALLETITA_EXPRESS_100GRS', 'Arcor', 0, 0),
(62, 'HARINA_1KG', 'Blancaflor', 0, 0),
(60, 'LATA_DE_PALMITOS_400GRS', 'Arcor', 0, 0),
(58, 'LECHE_EN_POLVO_1KG', 'La Serenisima', 0, 0),
(64, 'PAN_DULCE_500GRS', 'Don Satur', 0, 0),
(56, 'PURE_INSTANTANEO_200GRS', 'Knor', 0, 0),
(57, 'YERBA_1KG', 'Playadito', 0, 0),
(59, 'YERBA_500GRS', 'Amanda', 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento_donante`
--

CREATE TABLE IF NOT EXISTS `alimento_donante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `detalle_alimento_id` int(11) NOT NULL,
  `donante_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`detalle_alimento_id`,`donante_id`,`id`),
  UNIQUE KEY `id` (`id`),
  KEY `donante_id` (`donante_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=33 ;

--
-- Volcado de datos para la tabla `alimento_donante`
--

INSERT INTO `alimento_donante` (`id`, `detalle_alimento_id`, `donante_id`, `cantidad`, `baja`) VALUES
(22, 21, 7, 100, 0),
(23, 22, 8, 50, 0),
(24, 23, 9, 200, 0),
(25, 24, 7, 200, 0),
(26, 25, 8, 20, 0),
(27, 26, 9, 75, 0),
(28, 27, 7, 100, 0),
(29, 28, 8, 50, 0),
(30, 29, 8, 200, 0),
(31, 30, 9, 300, 0),
(32, 31, 7, 1, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento_entrega_directa`
--

CREATE TABLE IF NOT EXISTS `alimento_entrega_directa` (
  `entrega_directa_id` int(11) NOT NULL,
  `detalle_alimento_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`entrega_directa_id`,`detalle_alimento_id`),
  KEY `detalle_alimento_id` (`detalle_alimento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alimento_entrega_directa`
--

INSERT INTO `alimento_entrega_directa` (`entrega_directa_id`, `detalle_alimento_id`, `cantidad`, `baja`) VALUES
(1, 22, 5, 0),
(1, 24, 5, 0),
(1, 26, 5, 0),
(1, 27, 5, 0),
(1, 30, 5, 0),
(2, 22, 5, 0),
(2, 24, 5, 0),
(2, 26, 5, 0),
(2, 27, 5, 0),
(2, 30, 5, 0),
(3, 22, 5, 0),
(3, 24, 5, 0),
(3, 26, 5, 0),
(3, 27, 5, 0),
(3, 30, 5, 0),
(4, 22, 5, 0),
(4, 24, 5, 0),
(4, 26, 5, 0),
(4, 27, 5, 0),
(4, 30, 5, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimento_pedido`
--

CREATE TABLE IF NOT EXISTS `alimento_pedido` (
  `pedido_numero` int(11) NOT NULL,
  `detalle_alimento_id` int(11) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`pedido_numero`,`detalle_alimento_id`),
  KEY `detalle_alimento_id` (`detalle_alimento_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `alimento_pedido`
--

INSERT INTO `alimento_pedido` (`pedido_numero`, `detalle_alimento_id`, `cantidad`, `baja`) VALUES
(8, 21, 2, 0),
(8, 23, 2, 0),
(8, 25, 2, 0),
(8, 28, 2, 0),
(8, 29, 2, 0),
(9, 21, 2, 0),
(9, 23, 2, 0),
(9, 25, 2, 0),
(9, 28, 2, 0),
(9, 29, 2, 0),
(10, 21, 2, 0),
(10, 23, 2, 0),
(10, 25, 2, 0),
(10, 28, 2, 0),
(10, 29, 2, 0),
(11, 21, 2, 0),
(11, 23, 2, 0),
(11, 25, 2, 0),
(11, 28, 2, 0),
(11, 29, 2, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) NOT NULL,
  `valor` varchar(150) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `baja`) VALUES
(1, 'vencimiento_stock', '15', 0),
(2, 'mapa_latitud', '-34.903605', 0),
(3, 'mapa_longitud', '-57.937726', 0),
(4, 'linkedin_api_key', '772ot2tfqntox5', 0),
(5, 'linkedin_secret_key', 'Y6kUKUlWcWc6yDkd', 0),
(6, 'linkedin_oauth_user_token', '94616be2-c292-472d-9f45-dfad9b78f2ef', 0),
(7, 'linkedin_oauth_user_secret', 'cdf34568-ed5f-4a27-a15d-4c8bf276fb4d', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_alimento`
--

CREATE TABLE IF NOT EXISTS `detalle_alimento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `alimento_codigo` varchar(30) NOT NULL,
  `fecha_vencimiento` date NOT NULL,
  `contenido` varchar(200) NOT NULL,
  `peso_paquete` double(6,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `reservado` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `alimento_codigo` (`alimento_codigo`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=32 ;

--
-- Volcado de datos para la tabla `detalle_alimento`
--

INSERT INTO `detalle_alimento` (`id`, `alimento_codigo`, `fecha_vencimiento`, `contenido`, `peso_paquete`, `stock`, `reservado`, `baja`) VALUES
(21, 'AZUCAR_1KG', '2015-03-01', '10 unidades', 10.00, 92, 8, 0),
(22, 'BC_FRUTOS_ROJOS_390GRS', '2014-12-20', '20 unidades', 7.80, 30, 0, 0),
(23, 'GALLETITA_EXPRESS_100GRS', '2015-02-01', '20 unidades', 2.00, 192, 8, 0),
(24, 'HARINA_1KG', '2014-12-20', '10 unidades', 10.00, 180, 0, 0),
(25, 'LATA_DE_PALMITOS_400GRS', '2015-03-01', '10 unidades', 4.00, 12, 8, 0),
(26, 'LECHE_EN_POLVO_1KG', '2014-12-20', '20 unidades', 20.00, 55, 0, 0),
(27, 'PAN_DULCE_500GRS', '2014-12-22', '20 unidades', 10.00, 80, 0, 0),
(28, 'PURE_INSTANTANEO_200GRS', '2015-04-01', '10 unidades', 2.00, 42, 8, 0),
(29, 'YERBA_1KG', '2015-03-02', '10 unidades', 10.00, 192, 8, 0),
(30, 'YERBA_500GRS', '2014-12-19', '20 unidades', 10.00, 280, 0, 0),
(31, 'AZUCAR_1KG', '2014-12-01', '10 unidades', 10.00, 1, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `donante`
--

CREATE TABLE IF NOT EXISTS `donante` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(100) NOT NULL,
  `apellido_contacto` varchar(50) NOT NULL,
  `nombre_contacto` varchar(50) NOT NULL,
  `telefono_contacto` varchar(30) NOT NULL,
  `mail_contacto` varchar(50) NOT NULL,
  `domicilio_contacto` varchar(200) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Volcado de datos para la tabla `donante`
--

INSERT INTO `donante` (`id`, `razon_social`, `apellido_contacto`, `nombre_contacto`, `telefono_contacto`, `mail_contacto`, `domicilio_contacto`, `baja`) VALUES
(7, 'NINI SA', 'RODRIGUEZ', 'MATIAS', '02214716661', 'rodriguezmatias@hotmail.com', '7 1080 LA PLATA', 0),
(8, 'VITAL SA', 'SANCHEZ', 'FEDERICO', '02214846565', 'sanchezfederico@hotmail.com', '520 1080 LA PLATA', 0),
(9, 'WALLMART', 'CATA', 'JUAN', '02214713606', 'catajuan@hotmail.com', '19 720 LA PLATA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entidad_receptora`
--

CREATE TABLE IF NOT EXISTS `entidad_receptora` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `razon_social` varchar(100) NOT NULL,
  `telefono` varchar(30) NOT NULL,
  `domicilio` varchar(200) NOT NULL,
  `estado_entidad_id` int(11) NOT NULL,
  `necesidad_entidad_id` int(11) NOT NULL,
  `servicio_prestado_id` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  `latitud` varchar(15) NOT NULL DEFAULT '0',
  `longitud` varchar(15) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `estado_entidad_id` (`estado_entidad_id`,`necesidad_entidad_id`,`servicio_prestado_id`),
  KEY `necesidad_entidad_id` (`necesidad_entidad_id`),
  KEY `servicio_prestado_id` (`servicio_prestado_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `entidad_receptora`
--

INSERT INTO `entidad_receptora` (`id`, `razon_social`, `telefono`, `domicilio`, `estado_entidad_id`, `necesidad_entidad_id`, `servicio_prestado_id`, `baja`, `latitud`, `longitud`) VALUES
(6, 'CARITAS', '42464354', '4 883 LA PLATA', 1, 1, 3, 0, '0', '0'),
(7, 'RAYUELA', '453135153', '119 282 LA PLATA', 1, 1, 1, 0, '0', '0'),
(8, 'SIEMPRE LOS PEQUES', '4343543', '13 3096 LA PLATA', 1, 1, 2, 0, '0', '0'),
(9, 'ARRULLOS', '42151315', '66 660 LA PLATA', 1, 1, 1, 0, '0', '0'),
(10, 'ANGEL AZUL', '48643415', '117 216 LA PLATA', 1, 1, 1, 0, '0', '0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `entrega_directa`
--

CREATE TABLE IF NOT EXISTS `entrega_directa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_receptora_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `entidad_receptora_id` (`entidad_receptora_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `entrega_directa`
--

INSERT INTO `entrega_directa` (`id`, `entidad_receptora_id`, `fecha`, `baja`) VALUES
(1, 6, '2014-12-09', 0),
(2, 7, '2014-12-09', 0),
(3, 8, '2014-12-09', 0),
(4, 9, '2014-12-09', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_entidad`
--

CREATE TABLE IF NOT EXISTS `estado_entidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `estado_entidad`
--

INSERT INTO `estado_entidad` (`id`, `descripcion`, `baja`) VALUES
(1, 'ALTA', 0),
(2, 'EN TRAMITE', 0),
(3, 'SUSPENDIDA', 0),
(4, 'BAJA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE IF NOT EXISTS `estado_pedido` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(20) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  `entregado` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`id`, `descripcion`, `baja`, `entregado`) VALUES
(8, 'Entregar a Juan', 0, 0),
(9, 'Entregar a Ernesto', 0, 0),
(10, 'Retira Pedro', 0, 1),
(11, 'Retira Rosendo', 0, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo`
--

CREATE TABLE IF NOT EXISTS `grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_grupo` varchar(15) NOT NULL,
  `descripcion_grupo` varchar(100) DEFAULT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  `level` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `grupo`
--

INSERT INTO `grupo` (`id`, `nombre_grupo`, `descripcion_grupo`, `baja`, `level`) VALUES
(1, 'admin', 'tiene asignado todos los permisos\n', 0, 1),
(2, 'gestion', 'solo confecciona pedidos y entregas', 0, 2),
(3, 'consultor', 'solo puede listar alimentos en stock', 0, 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_permiso`
--

CREATE TABLE IF NOT EXISTS `grupo_permiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_id` int(11) NOT NULL,
  `permiso_id` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`grupo_id`,`permiso_id`,`id`),
  UNIQUE KEY `id` (`id`),
  KEY `permiso_id` (`permiso_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Volcado de datos para la tabla `grupo_permiso`
--

INSERT INTO `grupo_permiso` (`id`, `grupo_id`, `permiso_id`, `baja`) VALUES
(1, 1, 1, 0),
(2, 1, 2, 0),
(3, 1, 3, 0),
(4, 1, 4, 0),
(5, 1, 5, 0),
(6, 1, 6, 0),
(7, 1, 7, 0),
(8, 1, 8, 0),
(9, 1, 9, 0),
(10, 1, 10, 0),
(11, 1, 11, 0),
(12, 1, 12, 0),
(13, 1, 13, 0),
(14, 1, 14, 0),
(15, 1, 15, 0),
(16, 1, 16, 0),
(17, 1, 17, 0),
(18, 1, 18, 0),
(19, 1, 19, 0),
(20, 1, 20, 0),
(21, 1, 21, 0),
(22, 1, 22, 0),
(23, 1, 23, 0),
(24, 1, 24, 0),
(25, 1, 25, 0),
(26, 1, 26, 0),
(27, 1, 27, 0),
(28, 1, 28, 0),
(30, 1, 29, 0),
(32, 1, 30, 0),
(34, 1, 31, 0),
(35, 1, 32, 0),
(36, 1, 33, 0),
(37, 1, 34, 0),
(29, 3, 4, 0),
(31, 3, 29, 0),
(33, 3, 30, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `necesidad_entidad`
--

CREATE TABLE IF NOT EXISTS `necesidad_entidad` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(15) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `necesidad_entidad`
--

INSERT INTO `necesidad_entidad` (`id`, `descripcion`, `baja`) VALUES
(1, 'MAXIMA', 0),
(2, 'MEDIANA', 0),
(3, 'MINIMA', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido_modelo`
--

CREATE TABLE IF NOT EXISTS `pedido_modelo` (
  `numero` int(11) NOT NULL AUTO_INCREMENT,
  `entidad_receptora_id` int(11) NOT NULL,
  `fecha_ingreso` date NOT NULL,
  `estado_pedido_id` int(11) NOT NULL,
  `turno_entrega_id` int(11) NOT NULL,
  `con_envio` tinyint(1) NOT NULL DEFAULT '0',
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`numero`),
  KEY `entidad_receptora_id` (`entidad_receptora_id`,`estado_pedido_id`,`turno_entrega_id`),
  KEY `turno_entrega_id` (`turno_entrega_id`),
  KEY `estado_pedido_id` (`estado_pedido_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `pedido_modelo`
--

INSERT INTO `pedido_modelo` (`numero`, `entidad_receptora_id`, `fecha_ingreso`, `estado_pedido_id`, `turno_entrega_id`, `con_envio`, `baja`) VALUES
(8, 6, '2014-12-09', 8, 8, 1, 0),
(9, 7, '2014-12-09', 9, 9, 1, 0),
(10, 6, '2014-12-09', 10, 10, 0, 0),
(11, 9, '2014-12-09', 11, 11, 0, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permiso`
--

CREATE TABLE IF NOT EXISTS `permiso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_permiso` varchar(20) NOT NULL,
  `descripcion_permiso` varchar(100) DEFAULT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=35 ;

--
-- Volcado de datos para la tabla `permiso`
--

INSERT INTO `permiso` (`id`, `nombre_permiso`, `descripcion_permiso`, `baja`) VALUES
(1, 'alimento_alta', 'permite ingresar nuevos alimentos', 0),
(2, 'alimento_baja', 'permite dar de baja alimentos', 0),
(3, 'alimento_modificar', 'permite modificar alimentos', 0),
(4, 'alimento_listado', 'permite listar todos los alimentos', 0),
(5, 'detalle_alta', 'permite ingresar un nuevo detalle', 0),
(6, 'detalle_baja', 'permite dar de baja un detalle', 0),
(7, 'detalle_modificar', 'permite modificar un detalle', 0),
(8, 'detalle_listado', 'permite listar todos los detalles', 0),
(9, 'donante_alta', 'permite ingresar un nuevo donante', 0),
(10, 'donante_baja', 'permite dar de baja un donante', 0),
(11, 'donante_modificar', 'permite modificar un donante', 0),
(12, 'donante_listado', 'permite listar todos los donantes', 0),
(13, 'donacion_alta', 'permite ingresar una nueva donacion', 0),
(14, 'donacion_baja', 'permite dar de baja una donacion', 0),
(15, 'donacion_modificar', 'permite modificar una donacion', 0),
(16, 'donacion_listado', 'permite listar todos los donaciones', 0),
(17, 'estado_alta', 'permite ingresar un nuevo estado', 0),
(18, 'estado_baja', 'permite dar de baja un estado', 0),
(19, 'estado_modificar', 'permite modificar un estado', 0),
(20, 'estado_listado', 'permite listar todos los estado', 0),
(21, 'necesitad_alta', 'permite ingresar una nueva necesitad', 0),
(22, 'necesitad_baja', 'permite dar de baja una necesitad', 0),
(23, 'necesitad_modificar', 'permite modificar una necesitad', 0),
(24, 'necesitad_listado', 'permite listar todas las necesitad', 0),
(25, 'servicio_alta', 'permite ingresar una nueva servicio', 0),
(26, 'servicio_baja', 'permite dar de baja una servicio', 0),
(27, 'servicio_modificar', 'permite modificar una servicio', 0),
(28, 'servicio_listado', 'permite listar todos los servicio', 0),
(29, 'alimento', 'habilita seccion alimento', 0),
(30, 'alimento_detalle', 'permite ver el detalle de un determinado alimento', 0),
(31, 'usuario_listado', NULL, 0),
(32, 'usuario_alta', NULL, 0),
(33, 'usuario_baja', NULL, 0),
(34, 'usuario_modificar', NULL, 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio_prestado`
--

CREATE TABLE IF NOT EXISTS `servicio_prestado` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(100) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Volcado de datos para la tabla `servicio_prestado`
--

INSERT INTO `servicio_prestado` (`id`, `descripcion`, `baja`) VALUES
(1, 'HOGAR DE NIÑOS', 0),
(2, 'COMEDOR ESCOLAR', 0),
(3, 'HOGAR DE ADOLESCENTES', 0),
(4, 'ALOJAMIENTO', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno_entrega`
--

CREATE TABLE IF NOT EXISTS `turno_entrega` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Volcado de datos para la tabla `turno_entrega`
--

INSERT INTO `turno_entrega` (`id`, `fecha`, `hora`, `baja`) VALUES
(8, '2014-12-11', '18:00:00', 0),
(9, '2014-12-11', '20:00:00', 0),
(10, '2014-12-09', '23:00:00', 0),
(11, '2014-12-09', '22:30:00', 0);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(15) NOT NULL,
  `clave` varchar(50) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  `rol` varchar(10) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id`, `username`, `clave`, `baja`, `rol`) VALUES
(1, 'pepeadmin', '81dc9bdb52d04dc20036dbd8313ed055', 0, 'admin'),
(2, 'pepegestor', '81dc9bdb52d04dc20036dbd8313ed055', 0, 'gestion'),
(3, 'pepeconsultor', '81dc9bdb52d04dc20036dbd8313ed055', 0, 'consultor'),
(4, 'lautaro', '81dc9bdb52d04dc20036dbd8313ed055', 0, 'admin'),
(26, '1234', '81dc9bdb52d04dc20036dbd8313ed055', 0, 'admin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupo`
--

CREATE TABLE IF NOT EXISTS `usuario_grupo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `grupo_id` int(11) NOT NULL,
  `baja` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`usuario_id`,`grupo_id`,`id`),
  UNIQUE KEY `id` (`id`),
  KEY `grupo_id` (`grupo_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Volcado de datos para la tabla `usuario_grupo`
--

INSERT INTO `usuario_grupo` (`id`, `usuario_id`, `grupo_id`, `baja`) VALUES
(1, 1, 1, 0),
(2, 2, 2, 0),
(3, 3, 3, 0),
(4, 4, 1, 0),
(7, 26, 1, 0);

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `alimento_donante`
--
ALTER TABLE `alimento_donante`
  ADD CONSTRAINT `alimento_donante_ibfk_1` FOREIGN KEY (`donante_id`) REFERENCES `donante` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `alimento_donante_ibfk_2` FOREIGN KEY (`detalle_alimento_id`) REFERENCES `detalle_alimento` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `alimento_entrega_directa`
--
ALTER TABLE `alimento_entrega_directa`
  ADD CONSTRAINT `alimento_entrega_directa_ibfk_1` FOREIGN KEY (`detalle_alimento_id`) REFERENCES `detalle_alimento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `alimento_entrega_directa_ibfk_2` FOREIGN KEY (`entrega_directa_id`) REFERENCES `entrega_directa` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `alimento_pedido`
--
ALTER TABLE `alimento_pedido`
  ADD CONSTRAINT `alimento_pedido_ibfk_1` FOREIGN KEY (`detalle_alimento_id`) REFERENCES `detalle_alimento` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `alimento_pedido_ibfk_2` FOREIGN KEY (`pedido_numero`) REFERENCES `pedido_modelo` (`numero`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `detalle_alimento`
--
ALTER TABLE `detalle_alimento`
  ADD CONSTRAINT `detalle_alimento_ibfk_1` FOREIGN KEY (`alimento_codigo`) REFERENCES `alimento` (`codigo`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entidad_receptora`
--
ALTER TABLE `entidad_receptora`
  ADD CONSTRAINT `entidad_receptora_ibfk_1` FOREIGN KEY (`estado_entidad_id`) REFERENCES `estado_entidad` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entidad_receptora_ibfk_2` FOREIGN KEY (`necesidad_entidad_id`) REFERENCES `necesidad_entidad` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `entidad_receptora_ibfk_3` FOREIGN KEY (`servicio_prestado_id`) REFERENCES `servicio_prestado` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `entrega_directa`
--
ALTER TABLE `entrega_directa`
  ADD CONSTRAINT `entrega_directa_ibfk_1` FOREIGN KEY (`entidad_receptora_id`) REFERENCES `entidad_receptora` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `grupo_permiso`
--
ALTER TABLE `grupo_permiso`
  ADD CONSTRAINT `grupo_permiso_ibfk_1` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `grupo_permiso_ibfk_2` FOREIGN KEY (`permiso_id`) REFERENCES `permiso` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `pedido_modelo`
--
ALTER TABLE `pedido_modelo`
  ADD CONSTRAINT `pedido_modelo_ibfk_1` FOREIGN KEY (`entidad_receptora_id`) REFERENCES `entidad_receptora` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_modelo_ibfk_2` FOREIGN KEY (`turno_entrega_id`) REFERENCES `turno_entrega` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pedido_modelo_ibfk_3` FOREIGN KEY (`estado_pedido_id`) REFERENCES `estado_pedido` (`id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `usuario_grupo`
--
ALTER TABLE `usuario_grupo`
  ADD CONSTRAINT `usuario_grupo_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `usuario_grupo_ibfk_2` FOREIGN KEY (`grupo_id`) REFERENCES `grupo` (`id`) ON UPDATE CASCADE;
