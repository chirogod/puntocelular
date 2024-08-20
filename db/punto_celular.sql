-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 17-08-2024 a las 16:32:54
-- Versión del servidor: 8.3.0
-- Versión de PHP: 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `punto_celular`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `articulo`
--

DROP TABLE IF EXISTS `articulo`;
CREATE TABLE IF NOT EXISTS `articulo` (
  `id_articulo` int NOT NULL AUTO_INCREMENT,
  `articulo_descripcion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `articulo_stock` int NOT NULL,
  `articulo_stock_min` int NOT NULL,
  `articulo_stock_max` int NOT NULL,
  `id_rubro` int NOT NULL,
  `id_sucursal` int NOT NULL,
  `articulo_garantia` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `articulo_observacion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `articulo_activo` varchar(10) COLLATE utf8mb4_general_ci NOT NULL,
  `articulo_precio_compra` decimal(30,2) NOT NULL,
  `articulo_precio_venta` decimal(30,2) NOT NULL,
  `articulo_marca` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `articulo_modelo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_articulo`),
  KEY `id_rubro` (`id_rubro`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `caja`
--

DROP TABLE IF EXISTS `caja`;
CREATE TABLE IF NOT EXISTS `caja` (
  `id_caja` int NOT NULL AUTO_INCREMENT,
  `caja_fecha` date NOT NULL,
  `caja_monto` decimal(20,2) NOT NULL,
  `caja_tipo_movimiento` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `caja_detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_usuario` int NOT NULL,
  `id_sucursal` int NOT NULL,
  PRIMARY KEY (`id_caja`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_sucursal` (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cliente`
--

DROP TABLE IF EXISTS `cliente`;
CREATE TABLE IF NOT EXISTS `cliente` (
  `id_cliente` int NOT NULL AUTO_INCREMENT,
  `cliente_nombre_completo` varchar(300) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_telefono_1` int NOT NULL,
  `cliente_telefono_2` int NOT NULL,
  `cliente_domicilio` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_localidad` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_provincia` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_pais` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cliente_dni` int NOT NULL,
  PRIMARY KEY (`id_cliente`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion`
--

DROP TABLE IF EXISTS `cotizacion`;
CREATE TABLE IF NOT EXISTS `cotizacion` (
  `id_cotizacion` int NOT NULL AUTO_INCREMENT,
  `cotizacion_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cotizacion_fecha` date NOT NULL,
  `id_usuario` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_sucursal` int NOT NULL,
  `cotizacion_validez` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cotizacion_observaciones` text COLLATE utf8mb4_general_ci NOT NULL,
  `cotizacion_forma_pago` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `cotizacion_importe` decimal(20,2) NOT NULL,
  PRIMARY KEY (`id_cotizacion`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_usuario` (`id_usuario`),
  KEY `cotizacion_codigo` (`cotizacion_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cotizacion_detalle`
--

DROP TABLE IF EXISTS `cotizacion_detalle`;
CREATE TABLE IF NOT EXISTS `cotizacion_detalle` (
  `id_cotizacion_detalle` int NOT NULL AUTO_INCREMENT,
  `cotizacion_detalle_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `id_articulo` int NOT NULL,
  `cotizacion_detalle_monto` decimal(20,2) NOT NULL,
  `cotizacion_detalle_descripcion_producto` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `cotizacion_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_cotizacion_detalle`),
  KEY `cotizacion_codigo` (`cotizacion_codigo`),
  KEY `id_articulo` (`id_articulo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `equipo`
--

DROP TABLE IF EXISTS `equipo`;
CREATE TABLE IF NOT EXISTS `equipo` (
  `id_equipo` int NOT NULL AUTO_INCREMENT,
  `equipo_marca` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `equipo_modelo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `equipo_nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_equipo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `gasto`
--

DROP TABLE IF EXISTS `gasto`;
CREATE TABLE IF NOT EXISTS `gasto` (
  `id_gasto` int NOT NULL AUTO_INCREMENT,
  `gasto_fecha` date NOT NULL,
  `gasto_importe` decimal(20,2) NOT NULL,
  `gasto_detalle` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `id_usuario` int NOT NULL,
  `id_sucursal` int NOT NULL,
  PRIMARY KEY (`id_gasto`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden`
--

DROP TABLE IF EXISTS `orden`;
CREATE TABLE IF NOT EXISTS `orden` (
  `id_orden` int NOT NULL AUTO_INCREMENT,
  `orden_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `orden_fecha` date NOT NULL,
  `orden_importe` decimal(20,2) NOT NULL,
  `id_cliente` int NOT NULL,
  `id_usuario` int NOT NULL,
  `id_sucursal` int NOT NULL,
  `orden_tipo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `orden_falla` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `orden_detalles` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `orden_observaciones` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_orden`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_usuario` (`id_usuario`),
  KEY `id_cliente` (`id_cliente`),
  KEY `orden_codigo` (`orden_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orden_detalle`
--

DROP TABLE IF EXISTS `orden_detalle`;
CREATE TABLE IF NOT EXISTS `orden_detalle` (
  `id_orden_detalle` int NOT NULL AUTO_INCREMENT,
  `orden_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `id_equipo` int NOT NULL,
  `orden_detalle_observaciones` text COLLATE utf8mb4_general_ci NOT NULL,
  `orden_detalle_informe_tecnico` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_orden_detalle`),
  KEY `id_equipo` (`id_equipo`),
  KEY `orden_codigo` (`orden_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `rubro`
--

DROP TABLE IF EXISTS `rubro`;
CREATE TABLE IF NOT EXISTS `rubro` (
  `id_rubro` int NOT NULL AUTO_INCREMENT,
  `rubro_descripcion` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_rubro`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sucursal`
--

DROP TABLE IF EXISTS `sucursal`;
CREATE TABLE IF NOT EXISTS `sucursal` (
  `id_sucursal` int NOT NULL AUTO_INCREMENT,
  `sucursal_descripcion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal_direccion` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal_telefono` int NOT NULL,
  `sucursal_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal_pie_nota` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal_pie_comprobante` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `sucursal_firma_email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_sucursal`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

DROP TABLE IF EXISTS `usuario`;
CREATE TABLE IF NOT EXISTS `usuario` (
  `id_usuario` int NOT NULL AUTO_INCREMENT,
  `usuario_nombre_completo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_telefono` int NOT NULL,
  `usuario_dni` int NOT NULL,
  `usuario_nacimiento` date NOT NULL,
  `usuario_usuario` varchar(20) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_clave` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `usuario_rol` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta`
--

DROP TABLE IF EXISTS `venta`;
CREATE TABLE IF NOT EXISTS `venta` (
  `id_venta` int NOT NULL AUTO_INCREMENT,
  `venta_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `venta_fecha` date NOT NULL,
  `venta_importe` decimal(20,2) NOT NULL,
  `id_sucursal` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_usuario` int NOT NULL,
  `venta_forma_pago` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_venta`),
  KEY `venta_codigo` (`venta_codigo`),
  KEY `id_sucursal` (`id_sucursal`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `venta_detalle`
--

DROP TABLE IF EXISTS `venta_detalle`;
CREATE TABLE IF NOT EXISTS `venta_detalle` (
  `id_venta_detalle` int NOT NULL AUTO_INCREMENT,
  `venta_detalle_cantidad_producto` int NOT NULL,
  `venta_detalle_precio_compra_producto` decimal(20,2) NOT NULL,
  `venta_detalle_precio_venta_producto` decimal(20,2) NOT NULL,
  `venta_detalle_descripcion_producto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `venta_detalle_total` decimal(20,2) NOT NULL,
  `id_articulo` int NOT NULL,
  `venta_codigo` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_venta_detalle`),
  KEY `id_articulo` (`id_articulo`),
  KEY `venta_codigo` (`venta_codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `articulo`
--
ALTER TABLE `articulo`
  ADD CONSTRAINT `articulo_ibfk_1` FOREIGN KEY (`id_rubro`) REFERENCES `rubro` (`id_rubro`),
  ADD CONSTRAINT `articulo_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `caja`
--
ALTER TABLE `caja`
  ADD CONSTRAINT `caja_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `caja_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `cotizacion`
--
ALTER TABLE `cotizacion`
  ADD CONSTRAINT `cotizacion_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `cotizacion_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `cotizacion_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `cotizacion_detalle`
--
ALTER TABLE `cotizacion_detalle`
  ADD CONSTRAINT `cotizacion_detalle_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id_articulo`),
  ADD CONSTRAINT `cotizacion_detalle_ibfk_2` FOREIGN KEY (`cotizacion_codigo`) REFERENCES `cotizacion` (`cotizacion_codigo`);

--
-- Filtros para la tabla `gasto`
--
ALTER TABLE `gasto`
  ADD CONSTRAINT `gasto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `gasto_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`);

--
-- Filtros para la tabla `orden`
--
ALTER TABLE `orden`
  ADD CONSTRAINT `orden_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`),
  ADD CONSTRAINT `orden_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `orden_ibfk_3` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`);

--
-- Filtros para la tabla `orden_detalle`
--
ALTER TABLE `orden_detalle`
  ADD CONSTRAINT `orden_detalle_ibfk_1` FOREIGN KEY (`orden_codigo`) REFERENCES `orden` (`orden_codigo`),
  ADD CONSTRAINT `orden_detalle_ibfk_2` FOREIGN KEY (`id_equipo`) REFERENCES `equipo` (`id_equipo`);

--
-- Filtros para la tabla `venta`
--
ALTER TABLE `venta`
  ADD CONSTRAINT `venta_ibfk_1` FOREIGN KEY (`id_cliente`) REFERENCES `cliente` (`id_cliente`),
  ADD CONSTRAINT `venta_ibfk_2` FOREIGN KEY (`id_sucursal`) REFERENCES `sucursal` (`id_sucursal`),
  ADD CONSTRAINT `venta_ibfk_3` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `venta_detalle`
--
ALTER TABLE `venta_detalle`
  ADD CONSTRAINT `venta_detalle_ibfk_1` FOREIGN KEY (`id_articulo`) REFERENCES `articulo` (`id_articulo`),
  ADD CONSTRAINT `venta_detalle_ibfk_2` FOREIGN KEY (`venta_codigo`) REFERENCES `venta` (`venta_codigo`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
