-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1:3306
-- Tiempo de generación: 02-09-2024 a las 22:43:53
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
-- Base de datos: `sistema_prestamos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `administracion`
--

DROP TABLE IF EXISTS `administracion`;
CREATE TABLE IF NOT EXISTS `administracion` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `mes` varchar(15) NOT NULL,
  `anio` int NOT NULL,
  `monto` decimal(10,2) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cajas`
--

DROP TABLE IF EXISTS `cajas`;
CREATE TABLE IF NOT EXISTS `cajas` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `monto_inicial` decimal(10,2) NOT NULL,
  `fecha_apertura` datetime NOT NULL,
  `ganancia` decimal(10,2) NOT NULL DEFAULT '0.00',
  `estado` int NOT NULL DEFAULT '1',
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ci_sessions`
--

DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE IF NOT EXISTS `ci_sessions` (
  `id` varchar(128) COLLATE utf8mb4_general_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `timestamp` bigint UNSIGNED NOT NULL DEFAULT '0',
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `clientes`
--

DROP TABLE IF EXISTS `clientes`;
CREATE TABLE IF NOT EXISTS `clientes` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `identidad` varchar(50) NOT NULL,
  `num_identidad` varchar(50) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `correo` varchar(150) NOT NULL,
  `direccion` text NOT NULL,
  `habido` varchar(20) DEFAULT NULL,
  `razon` varchar(255) DEFAULT NULL,
  `referencia` varchar(255) DEFAULT NULL,
  `imgperfil` varchar(255) NOT NULL,
  `nombre_negocio` varchar(100) DEFAULT NULL,
  `direccion_negocio` varchar(255) DEFAULT NULL,
  `tipo_negocio` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

DROP TABLE IF EXISTS `configuracion`;
CREATE TABLE IF NOT EXISTS `configuracion` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `identidad` varchar(20) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `telefono` varchar(50) NOT NULL,
  `direccion` text NOT NULL,
  `correo` varchar(150) NOT NULL,
  `mensaje` text NOT NULL,
  `tasa_interes` int NOT NULL,
  `cuotas` int NOT NULL,
  `mensaje_ticket` varchar(250) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_pagos`
--

DROP TABLE IF EXISTS `detalle_pagos`;
CREATE TABLE IF NOT EXISTS `detalle_pagos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `monto` decimal(10,2) NOT NULL,
  `prestamo_id` int NOT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `num` varchar(30) NOT NULL,
  `id_cliente` int NOT NULL,
  `id_metodo` int NOT NULL,
  `id_prestamo` int NOT NULL,
  `n_pagadas` int NOT NULL,
  `n_pendiente` int DEFAULT NULL,
  `n_totales` int DEFAULT NULL,
  `importe_cuota` decimal(10,2) NOT NULL,
  `pmora1` varchar(30) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_metodo` (`id_metodo`),
  KEY `id_prestamo` (`id_prestamo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `detalle_prestamos`
--

DROP TABLE IF EXISTS `detalle_prestamos`;
CREATE TABLE IF NOT EXISTS `detalle_prestamos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `cuota` int NOT NULL,
  `abono` decimal(10,2) NOT NULL DEFAULT '0.00',
  `f_vence` date DEFAULT NULL,
  `importe_cuota` decimal(10,2) NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `observaciones` longtext,
  `id_prestamo` int NOT NULL,
  `fecha1` timestamp NULL DEFAULT NULL,
  `c_pagadas` int DEFAULT NULL,
  `id_pago` int NOT NULL,
  `m_pago` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_prestamo` (`id_prestamo`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresas`
--

DROP TABLE IF EXISTS `empresas`;
CREATE TABLE IF NOT EXISTS `empresas` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `limite_usuarios` int NOT NULL DEFAULT '5',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `subdominio` varchar(100) NOT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `subdominio` (`subdominio`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `empresas`
--

INSERT INTO `empresas` (`id`, `nombre`, `limite_usuarios`, `created_at`, `updated_at`, `subdominio`, `estado`) VALUES
(1, 'PRINCIPAL', 5000, NULL, NULL, '', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `empresa_empresas`
--

DROP TABLE IF EXISTS `empresa_empresas`;
CREATE TABLE IF NOT EXISTS `empresa_empresas` (
  `empresa_empresas` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `factories`
--

DROP TABLE IF EXISTS `factories`;
CREATE TABLE IF NOT EXISTS `factories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(31) NOT NULL,
  `uid` varchar(31) NOT NULL,
  `class` varchar(63) NOT NULL,
  `icon` varchar(31) NOT NULL,
  `summary` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `uid` (`uid`),
  KEY `deleted_at_id` (`deleted_at`,`id`),
  KEY `created_at` (`created_at`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `metodos_pago`
--

DROP TABLE IF EXISTS `metodos_pago`;
CREATE TABLE IF NOT EXISTS `metodos_pago` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(200) NOT NULL,
  `estado` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `version` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `group` varchar(255) NOT NULL,
  `namespace` varchar(255) NOT NULL,
  `time` int NOT NULL,
  `batch` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `version`, `class`, `group`, `namespace`, `time`, `batch`) VALUES
(56, '2020-02-22-222222', 'Tests\\Support\\Database\\Migrations\\ExampleMigration', 'tests', 'Tests\\Support', 1725180110, 1),
(57, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateAdministracionTable', 'default', 'App', 1725180129, 2),
(58, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateCajasTable', 'default', 'App', 1725180129, 2),
(59, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateClientesTable', 'default', 'App', 1725180129, 2),
(60, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateMetodosPagoTable', 'default', 'App', 1725180129, 2),
(61, '2024-09-01-060446', 'App\\Database\\Migrations\\CreatePrestamosTable', 'default', 'App', 1725180129, 2),
(62, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateRolesTable', 'default', 'App', 1725180129, 2),
(63, '2024-09-01-060446', 'App\\Database\\Migrations\\CreateUsuariosTable', 'default', 'App', 1725180129, 2),
(64, '2024-09-01-060447', 'App\\Database\\Migrations\\CreateConfiguracionTable', 'default', 'App', 1725180129, 2),
(65, '2024-09-01-060447', 'App\\Database\\Migrations\\CreateDetallePagosTable', 'default', 'App', 1725180129, 2),
(66, '2024-09-01-060447', 'App\\Database\\Migrations\\CreateDetallePrestamosTable', 'default', 'App', 1725180129, 2),
(67, '2024-09-01-060447', 'App\\Database\\Migrations\\CreateEmpresasTable', 'default', 'App', 1725180129, 2),
(68, '2024-09-01-060447', 'App\\Database\\Migrations\\CreatePermisosTable', 'default', 'App', 1725180129, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `permisos`
--

DROP TABLE IF EXISTS `permisos`;
CREATE TABLE IF NOT EXISTS `permisos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `modulo` varchar(150) NOT NULL,
  `campos` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `permisos`
--

INSERT INTO `permisos` (`id`, `modulo`, `campos`) VALUES
(1, 'usuarios', '[\"listar usuarios\",\"crear usuario\",\"editar usuario\",\"eliminar usuario\"]'),
(2, 'clientes', '[\"listar clientes\",\"crear cliente\",\"editar cliente\",\"eliminar cliente\"]'),
(3, 'prestamos', '[\"nuevo prestamo\",\"listar prestamos\",\"eliminar prestamo\",\"contratos prestamo\"]'),
(4, 'cajas', '[\"movimientos caja\"]'),
(5, 'configuracion', '[\"actualizar configuracion\"]'),
(6, 'backup', '[\"crear respaldo\"]'),
(7, 'roles', '[\"listar roles\",\"crear rol\",\"editar rol\",\"eliminar rol\"]'),
(8, 'reportes', '[\"pdf prestamos\",\"excel prestamos\"]'),
(11, 'pagos', '[\"listar pagos\",\"editar pago\"]'),
(20, 'metodos', '[\"listar metodos\",\"crear metodo\",\"editar metodo\",\"eliminar metodo\"]'),
(21, 'movimientos', '[\"listar movimientos\",\"crear movimiento\",\"editar movimiento\",\"eliminar movimiento\"]'),
(22, 'principal', '[\"acceso principal\",\"configurar ajustes\",\"ver reportes\",\"gestionar contenido\"]');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `prestamos`
--

DROP TABLE IF EXISTS `prestamos`;
CREATE TABLE IF NOT EXISTS `prestamos` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `importe` decimal(10,2) NOT NULL,
  `modalidad` varchar(50) NOT NULL,
  `tasa_interes` decimal(10,2) NOT NULL,
  `cuotas` int NOT NULL,
  `fecha` datetime NOT NULL,
  `f_venc` date DEFAULT NULL,
  `estado` int NOT NULL DEFAULT '1',
  `t_estado` int NOT NULL,
  `id_cliente` int NOT NULL,
  `id_usuario` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_cliente` (`id_cliente`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(50) NOT NULL,
  `permisos` text,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `permisos`, `created_at`, `updated_at`, `estado`) VALUES
(3, 'Superadmin', '[\"listar usuarios\",\"crear usuario\",\"editar usuario\",\"listar clientes\",\"crear cliente\",\"editar cliente\",\"eliminar cliente\",\"nuevo prestamo\",\"listar prestamos\",\"eliminar prestamo\",\"contratos prestamo\",\"movimientos caja\",\"actualizar configuracion\",\"crear respaldo\",\"listar roles\",\"crear rol\",\"editar rol\",\"eliminar rol\",\"pdf prestamos\",\"excel prestamos\",\"listar pagos\",\"editar pago\",\"listar metodos\",\"crear metodo\",\"editar metodo\",\"eliminar metodo\",\"listar movimientos\",\"crear movimiento\",\"editar movimiento\",\"eliminar movimiento\",\"principal\"]', '0000-00-00 00:00:00', NULL, 1),
(2, 'COBRADOR', '[\"listar clientes\",\"crear cliente\",\"editar cliente\",\"nuevo prestamo\",\"listar prestamos\",\"contratos prestamo\"]', '0000-00-00 00:00:00', NULL, 1),
(1, 'ADMINISTRADOR', '[\"listar usuarios\",\"crear usuario\",\"editar usuario\",\"listar clientes\",\"crear cliente\",\"editar cliente\",\"eliminar cliente\",\"nuevo prestamo\",\"listar prestamos\",\"eliminar prestamo\",\"contratos prestamo\",\"movimientos caja\",\"actualizar configuracion\",\"crear respaldo\",\"listar roles\",\"crear rol\",\"editar rol\",\"eliminar rol\",\"pdf prestamos\",\"excel prestamos\",\"listar pagos\",\"editar pago\",\"listar metodos\",\"crear metodo\",\"editar metodo\",\"eliminar metodo\",\"listar movimientos\",\"crear movimiento\",\"editar movimiento\",\"eliminar movimiento\",\"principal\"]', '0000-00-00 00:00:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `apellido` varchar(100) NOT NULL,
  `correo` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` text,
  `clave` varchar(255) NOT NULL,
  `id_rol` int UNSIGNED NOT NULL,
  `id_empresa` int UNSIGNED DEFAULT NULL,
  `es_superadmin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `estado` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `correo` (`correo`),
  KEY `usuarios_id_rol_foreign` (`id_rol`),
  KEY `usuarios_id_empresa_foreign` (`id_empresa`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb3;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `apellido`, `correo`, `telefono`, `direccion`, `clave`, `id_rol`, `id_empresa`, `es_superadmin`, `created_at`, `updated_at`, `estado`) VALUES
(1, 'CREDIAKUNTA', 'CHOTA', 'admin@crediakunta.com', '999999999', 'PERU', '$2b$12$GPiVrbVD2VDc2CdGKthw.OrCRGQlS0GJceM31Tf4HPU.BTPjnSxtO', 1, NULL, 0, '0000-00-00 00:00:00', NULL, NULL),
(2, 'cobrador', 'crediacunta', 'cobrador@crediakunta.com', '913117698', 'Los olivos 320', '$2b$12$GPiVrbVD2VDc2CdGKthw.OrCRGQlS0GJceM31Tf4HPU.BTPjnSxtO', 1, NULL, 0, '0000-00-00 00:00:00', NULL, NULL),
(3, 'Superadmin', 'acunta', 'Superadmin@jtdesarrolloweb.com', '913117696', 'Los olivos 320', '$2y$10$jFvxtpZshe/74SONrkrJgOqzmmxTGw/nR63lTH27rvOzaSlBroFD6', 1, 1, 1, '0000-00-00 00:00:00', NULL, 1);

DELIMITER $$
--
-- Eventos
--
DROP EVENT IF EXISTS `actualizar_detalle_pagos`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `actualizar_detalle_pagos` ON SCHEDULE EVERY 1 SECOND STARTS '2024-01-01 00:00:00' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DECLARE last_id INT DEFAULT NULL;
    DECLARE last_id_prestamo INT DEFAULT NULL;
    DECLARE last_id_pago INT DEFAULT NULL;

    SELECT id, id_prestamo, id_pago INTO last_id, last_id_prestamo, last_id_pago
    FROM detalle_prestamos
    WHERE estado = 0
    ORDER BY id DESC
    LIMIT 1;

    IF last_id IS NOT NULL AND (last_id_pago IS NULL OR last_id_pago = 0 OR last_id_pago = '') THEN
        UPDATE detalle_pagos dp
        SET dp.prestamo_id = last_id
        WHERE dp.id_prestamo = last_id_prestamo
        AND (dp.prestamo_id IS NULL OR dp.prestamo_id = 0 OR dp.prestamo_id = '');
    END IF;
END$$

DROP EVENT IF EXISTS `actualizar_detalle_prestamos`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `actualizar_detalle_prestamos` ON SCHEDULE EVERY 3 SECOND STARTS '2024-01-01 00:00:01' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    UPDATE detalle_prestamos dpr
    JOIN (
        SELECT id_prestamo, id 
        FROM detalle_pagos 
        ORDER BY id DESC 
        LIMIT 1
    ) dp ON dp.id_prestamo = dpr.id_prestamo
    SET dpr.id_pago = dp.id
    WHERE dpr.estado = 0
    AND (dpr.id_pago IS NULL OR dpr.id_pago = 0 OR dpr.id_pago = '');
END$$

DROP EVENT IF EXISTS `actualizar_importe_cuota`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `actualizar_importe_cuota` ON SCHEDULE EVERY 1 SECOND STARTS '2024-05-04 04:39:12' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_pagos dp
    SET dp.importe_cuota = (
        SELECT MAX(dpres.importe_cuota)
        FROM detalle_prestamos dpres
        WHERE dpres.id_prestamo = dp.id_prestamo
    )$$

DROP EVENT IF EXISTS `actualizar_m_pagado`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `actualizar_m_pagado` ON SCHEDULE EVERY 1 SECOND STARTS '2024-05-12 06:04:58' ON COMPLETION NOT PRESERVE ENABLE DO BEGIN
    DECLARE total_importe DECIMAL(10, 2);

    SELECT SUM(importe_cuota)
    INTO total_importe
    FROM detalle_prestamo
    WHERE id_prestamo IN (SELECT id FROM detalle_pagos WHERE m_pagado IS NULL OR m_pagado = 0)
    AND estado = 0;

    UPDATE detalle_pagos
    SET m_pagado = total_importe
    WHERE id_prestamo IN (SELECT id FROM detalle_pagos WHERE m_pagado IS NULL OR m_pagado = 0)
    AND (m_pagado IS NULL OR m_pagado = 0);
END$$

DROP EVENT IF EXISTS `columna_n_pagadas`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `columna_n_pagadas` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 06:54:05' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_pagos dp
JOIN detalle_prestamos dpr ON dp.id_prestamo = dpr.id_prestamo
SET dp.n_pagadas = 
    CASE
        WHEN dpr.estado = 0 AND dp.monto = dpr.importe_cuota THEN 1
        WHEN dpr.estado = 0 AND dp.monto > dpr.importe_cuota THEN FLOOR(dp.monto / dpr.importe_cuota)
        WHEN dpr.estado = 0 THEN 1
        ELSE 0
    END$$

DROP EVENT IF EXISTS `c_pagadas`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `c_pagadas` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 19:49:31' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_prestamos dp
JOIN (
    SELECT id_prestamo, COUNT(*) AS c_pagadas
    FROM detalle_prestamos
    WHERE estado = 0
    GROUP BY id_prestamo
) AS subquery ON dp.id_prestamo = subquery.id_prestamo
CROSS JOIN (SELECT MAX(id_pago) + 1 AS next_id_pago FROM detalle_prestamos) AS next_id_sub
SET dp.c_pagadas = subquery.c_pagadas,
    dp.id_pago = next_id_sub.next_id_pago
WHERE dp.estado = 0 AND dp.c_pagadas = 0$$

DROP EVENT IF EXISTS `c_pendientes`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `c_pendientes` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 19:50:01' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_prestamos dp
JOIN (
    SELECT id_prestamo, COUNT(*) AS c_pendientes
    FROM detalle_prestamos
    WHERE estado = 1
    GROUP BY id_prestamo
) AS c_pendientes_sub ON dp.id_prestamo = c_pendientes_sub.id_prestamo
SET dp.c_pendientes = c_pendientes_sub.c_pendientes
WHERE dp.estado = 1$$

DROP EVENT IF EXISTS `esta0anulados`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `esta0anulados` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 02:08:06' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_prestamos dp
INNER JOIN prestamos p ON dp.id_prestamo = p.id
SET dp.estado = 3
WHERE p.estado = 0$$

DROP EVENT IF EXISTS `esta1co_hoy`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `esta1co_hoy` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 02:02:29' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE prestamos
SET estado = 2, t_estado = 1
WHERE estado = 1
AND id IN (
    SELECT id_prestamo
    FROM detalle_prestamos
    WHERE f_vence = CURDATE()
    AND estado = 1
)
AND id NOT IN (
    SELECT id_prestamo
    FROM detalle_prestamos
    WHERE f_vence < CURDATE()
    AND estado = 1
)$$

DROP EVENT IF EXISTS `esta2acti`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `esta2acti` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-02 02:00:23' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE prestamos p
SET p.estado = 2, p.t_estado = 1
WHERE p.id IN (
    SELECT dp.id_prestamo
    FROM detalle_prestamos dp
    WHERE dp.estado = 1
    AND dp.f_vence > CURDATE()
)
AND p.estado = 1$$

DROP EVENT IF EXISTS `esta4pagadas`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `esta4pagadas` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 01:38:14' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE prestamos p
SET p.estado = 4,
    p.t_estado = 4
WHERE NOT EXISTS (
    SELECT 1
    FROM detalle_prestamos dp
    WHERE dp.id_prestamo = p.id
    AND dp.estado <> 0
)
AND EXISTS (
    SELECT 1
    FROM detalle_prestamos dp
    WHERE dp.id_prestamo = p.id
)$$

DROP EVENT IF EXISTS `estado3_venc`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `estado3_venc` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 07:33:47' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE prestamos p
SET p.estado = 3,
    p.t_estado = 1
WHERE p.estado = 1
AND EXISTS (
    SELECT 1
    FROM detalle_prestamos dp
    WHERE dp.id_prestamo = p.id
    AND DATE(dp.f_vence) < CURDATE()
)$$

DROP EVENT IF EXISTS `fechaca`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `fechaca` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-02 22:16:23' ON COMPLETION NOT PRESERVE ENABLE DO SELECT dpg.id_prestamo, DATE(dpg.fecha) AS nueva_fecha
FROM detalle_pagos dpg
INNER JOIN detalle_prestamos dp ON dpg.id_prestamo = dp.id_prestamo
WHERE dp.estado = 0
AND dpg.num_cuotapagada <= dp.n_pagadas$$

DROP EVENT IF EXISTS `m_pagado`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `m_pagado` ON SCHEDULE EVERY 1 SECOND STARTS '2024-03-01 17:53:23' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_prestamos
INNER JOIN detalle_pagos ON detalle_prestamos.id_pago = detalle_pagos.id
SET detalle_prestamos.m_pago = detalle_pagos.monto$$

DROP EVENT IF EXISTS `n_pendientes`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `n_pendientes` ON SCHEDULE EVERY 1 SECOND STARTS '2024-01-01 10:52:18' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_pagos dp
SET dp.n_pendiente = (
    SELECT SUM(CASE WHEN estado = 0 THEN 1 ELSE 0 END)
    FROM detalle_prestamos
    WHERE id_prestamo = dp.id_prestamo
)
WHERE dp.n_pendiente = 0 OR dp.n_pendiente IS NULL$$

DROP EVENT IF EXISTS `n_totales`$$
CREATE DEFINER=`juvenaltarrillo`@`%` EVENT `n_totales` ON SCHEDULE EVERY 1 SECOND STARTS '2024-04-01 19:50:01' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE detalle_pagos dp
JOIN (
    SELECT id_prestamo, SUM(1) AS total_cuotas
    FROM detalle_prestamos
    GROUP BY id_prestamo
) AS cuotas_totales_sub ON dp.id_prestamo = cuotas_totales_sub.id_prestamo
SET dp.n_totales = cuotas_totales_sub.total_cuotas$$

DELIMITER ;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
