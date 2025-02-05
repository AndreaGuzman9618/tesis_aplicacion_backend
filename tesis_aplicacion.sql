-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-02-2025 a las 05:57:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `tesis_aplicacion`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `centros_salud`
--

CREATE TABLE `centros_salud` (
  `id_centro` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `direccion` text NOT NULL,
  `latitud` decimal(10,8) DEFAULT NULL,
  `longitud` decimal(11,8) DEFAULT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `centros_salud`
--

INSERT INTO `centros_salud` (`id_centro`, `nombre`, `direccion`, `latitud`, `longitud`, `telefono`, `fecha_creacion`) VALUES
(1, 'Centro de Salud Oramas Gonzalez', 'CDLA. ORAMAS GONZÁLEZ MZ. 8 SOL. 36', -2.17358786, -79.82777667, NULL, '2025-01-26 19:53:51');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `citas`
--

CREATE TABLE `citas` (
  `id_cita` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_centro` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha_cita` date NOT NULL,
  `hora_cita` time NOT NULL,
  `motivo` text DEFAULT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `citas`
--

INSERT INTO `citas` (`id_cita`, `id_usuario`, `id_centro`, `id_especialidad`, `fecha_cita`, `hora_cita`, `motivo`, `id_estado`, `fecha_creacion`) VALUES
(1, 2, 1, 2, '2025-02-06', '08:45:00', NULL, 4, '2025-02-01 13:38:18'),
(2, 2, 1, 1, '2025-02-05', '12:30:00', NULL, 4, '2025-02-02 00:54:29'),
(3, 2, 1, 3, '2025-02-04', '08:00:00', NULL, 1, '2025-02-02 22:33:15'),
(4, 2, 1, 4, '2025-02-06', '11:00:00', NULL, 1, '2025-02-02 22:40:36'),
(5, 2, 1, 5, '2025-02-03', '08:00:00', NULL, 3, '2025-02-03 00:03:35'),
(6, 3, 1, 2, '2025-02-07', '11:45:00', NULL, 4, '2025-02-03 03:11:49'),
(7, 3, 1, 1, '2025-02-04', '08:45:00', NULL, 3, '2025-02-03 03:12:13');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `configuracion`
--

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL,
  `clave` varchar(100) NOT NULL,
  `valor` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `configuracion`
--

INSERT INTO `configuracion` (`id`, `clave`, `valor`) VALUES
(1, 'google_maps_api_key', 'AIzaSyBscbM7aq7pygWcvtSRPavGpBQfJkRFQv0');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `disponibilidades`
--

CREATE TABLE `disponibilidades` (
  `id_disponibilidad` int(11) NOT NULL,
  `id_centro` int(11) NOT NULL,
  `id_especialidad` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `especialidades`
--

CREATE TABLE `especialidades` (
  `id_especialidad` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `especialidades`
--

INSERT INTO `especialidades` (`id_especialidad`, `nombre`) VALUES
(1, 'MEDICINA GENERAL'),
(2, 'ODONTOLOGÍA'),
(3, 'PSICOLOGIA'),
(4, 'MEDICINA FAMILIAR'),
(5, 'OBSTETRICIA'),
(6, 'DISCAPICIDAD');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estados_citas`
--

CREATE TABLE `estados_citas` (
  `id_estado` int(11) NOT NULL,
  `estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estados_citas`
--

INSERT INTO `estados_citas` (`id_estado`, `estado`) VALUES
(1, 'Pendiente'),
(2, 'Confirmada'),
(3, 'Cancelada'),
(4, 'Reagendada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_notificaciones`
--

CREATE TABLE `estado_notificaciones` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_notificaciones`
--

INSERT INTO `estado_notificaciones` (`id_estado`, `nombre_estado`) VALUES
(1, 'pendiente'),
(2, 'leída'),
(3, 'eliminada');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evaluaciones`
--

CREATE TABLE `evaluaciones` (
  `id_evaluacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `calificacion` int(11) NOT NULL CHECK (`calificacion` between 1 and 5),
  `comentario` text DEFAULT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evaluaciones`
--

INSERT INTO `evaluaciones` (`id_evaluacion`, `id_usuario`, `calificacion`, `comentario`, `fecha_creacion`) VALUES
(1, 2, 5, 'Muy buena aplicación', '2025-02-05 09:04:40');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_notificaciones`
--

CREATE TABLE `historial_notificaciones` (
  `id_historial` int(11) NOT NULL,
  `id_notificacion` int(11) NOT NULL,
  `id_estado_anterior` int(11) NOT NULL,
  `id_estado_nuevo` int(11) NOT NULL,
  `fecha_cambio` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id_notificacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `icono` varchar(50) NOT NULL,
  `id_estado` int(11) NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `notificaciones`
--

INSERT INTO `notificaciones` (`id_notificacion`, `id_usuario`, `titulo`, `descripcion`, `icono`, `id_estado`, `fecha_creacion`) VALUES
(1, 2, 'Cita cancelada', 'Tu cita programada para el 2025-02-03 a las 08:00:00 ha sido cancelada.', 'cancel', 1, '2025-02-03 03:02:49'),
(2, 3, 'Cita cancelada', 'Tu cita programada para el 2025-02-04 a las 08:45:00 ha sido cancelada.', 'cancel', 1, '2025-02-03 03:12:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `proveedores_calendarios`
--

CREATE TABLE `proveedores_calendarios` (
  `id_proveedor` int(11) NOT NULL,
  `nombre_proveedor` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `proveedores_calendarios`
--

INSERT INTO `proveedores_calendarios` (`id_proveedor`, `nombre_proveedor`) VALUES
(1, 'Google'),
(2, 'iCloud');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id_rol` int(11) NOT NULL,
  `nombre_rol` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id_rol`, `nombre_rol`) VALUES
(1, 'paciente'),
(2, 'admin'),
(3, 'doctor');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sincronizacion_calendarios`
--

CREATE TABLE `sincronizacion_calendarios` (
  `id_sincronizacion` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_proveedor` int(11) NOT NULL,
  `token` text NOT NULL,
  `fecha_creacion` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cedula` varchar(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(15) DEFAULT NULL,
  `direccion` text DEFAULT NULL,
  `id_rol` int(11) NOT NULL,
  `coordenadas_lat` decimal(10,8) DEFAULT NULL,
  `coordenadas_lon` decimal(11,8) DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `cedula`, `nombre`, `email`, `password`, `telefono`, `direccion`, `id_rol`, `coordenadas_lat`, `coordenadas_lon`, `created_at`, `updated_at`) VALUES
(1, '0910875961', 'Juan Pérez', 'juan.perez@example.com', '$2y$10$5QEoWUsP2zbcuaAo4mL3buionMdKkH/IpiaXRXXmr2ZPNnvgryb1.', '1234567890', 'Calle Falsa 123', 1, -0.12345000, -78.12345000, '2025-01-25 21:55:08', '2025-01-25 21:55:08'),
(2, '0922219977', 'Allysson Guzman', 'andrea9698@hotmail.com', '$2y$10$qh4Re2/zRYB7.VqOnWHUquILLgKfLhB3O.RPtLa2vKVYcQkczgRDi', '0987749309', 'Google Building 1600, 1600 Plymouth St, Mountain View, CA 94043, USA', 1, 37.41739664, -122.08339870, '2025-01-26 01:59:05', '2025-02-03 03:03:32'),
(3, '1311339145', 'Luis Garcia', 'gerardogar_1982@hotmail.com', '$2y$10$61r56A7Qks0skQwaQxf11ep7YfGDNAKfe9rCp7YOtEf8pGxY55DfC', '0987749378', 'CWW3+CJ Palo Alto, CA, USA', 1, 37.44602254, -122.09590148, '2025-02-03 03:10:26', '2025-02-03 03:11:26');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `centros_salud`
--
ALTER TABLE `centros_salud`
  ADD PRIMARY KEY (`id_centro`);

--
-- Indices de la tabla `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id_cita`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_centro` (`id_centro`),
  ADD KEY `id_especialidad` (`id_especialidad`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`);

--
-- Indices de la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  ADD PRIMARY KEY (`id_disponibilidad`),
  ADD KEY `id_centro` (`id_centro`),
  ADD KEY `id_especialidad` (`id_especialidad`);

--
-- Indices de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id_especialidad`);

--
-- Indices de la tabla `estados_citas`
--
ALTER TABLE `estados_citas`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `estado_notificaciones`
--
ALTER TABLE `estado_notificaciones`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD PRIMARY KEY (`id_evaluacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `historial_notificaciones`
--
ALTER TABLE `historial_notificaciones`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_notificacion` (`id_notificacion`),
  ADD KEY `id_estado_anterior` (`id_estado_anterior`),
  ADD KEY `id_estado_nuevo` (`id_estado_nuevo`);

--
-- Indices de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id_notificacion`),
  ADD KEY `id_estado` (`id_estado`);

--
-- Indices de la tabla `proveedores_calendarios`
--
ALTER TABLE `proveedores_calendarios`
  ADD PRIMARY KEY (`id_proveedor`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id_rol`);

--
-- Indices de la tabla `sincronizacion_calendarios`
--
ALTER TABLE `sincronizacion_calendarios`
  ADD PRIMARY KEY (`id_sincronizacion`),
  ADD KEY `id_usuario` (`id_usuario`),
  ADD KEY `id_proveedor` (`id_proveedor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `id_rol` (`id_rol`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `centros_salud`
--
ALTER TABLE `centros_salud`
  MODIFY `id_centro` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `citas`
--
ALTER TABLE `citas`
  MODIFY `id_cita` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `configuracion`
--
ALTER TABLE `configuracion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  MODIFY `id_disponibilidad` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id_especialidad` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `estados_citas`
--
ALTER TABLE `estados_citas`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `estado_notificaciones`
--
ALTER TABLE `estado_notificaciones`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  MODIFY `id_evaluacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `historial_notificaciones`
--
ALTER TABLE `historial_notificaciones`
  MODIFY `id_historial` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id_notificacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `proveedores_calendarios`
--
ALTER TABLE `proveedores_calendarios`
  MODIFY `id_proveedor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id_rol` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `sincronizacion_calendarios`
--
ALTER TABLE `sincronizacion_calendarios`
  MODIFY `id_sincronizacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `disponibilidades`
--
ALTER TABLE `disponibilidades`
  ADD CONSTRAINT `disponibilidades_ibfk_2` FOREIGN KEY (`id_especialidad`) REFERENCES `especialidades` (`id_especialidad`) ON DELETE CASCADE;

--
-- Filtros para la tabla `evaluaciones`
--
ALTER TABLE `evaluaciones`
  ADD CONSTRAINT `evaluaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
