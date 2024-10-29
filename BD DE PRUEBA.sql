-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-10-2024 a las 06:13:11
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `consultorio-medico`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_cambios`
--

CREATE TABLE `historial_cambios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `usuario_id` bigint(20) UNSIGNED NOT NULL,
  `tabla_modificada` varchar(255) NOT NULL,
  `tipo_cambio` varchar(255) NOT NULL,
  `datos_anteriores` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_anteriores`)),
  `datos_nuevos` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`datos_nuevos`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_clinico`
--

CREATE TABLE `historial_clinico` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `paciente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `paciente_no_logueado_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profesional_id` bigint(20) UNSIGNED NOT NULL,
  `tension_arterial` varchar(255) DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `motivo_consulta` text DEFAULT NULL,
  `datos_relevantes_examen_fisico` text DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento_indicaciones` text DEFAULT NULL,
  `documentacion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documentacion`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `historial_clinico`
--

INSERT INTO `historial_clinico` (`id`, `paciente_id`, `paciente_no_logueado_id`, `profesional_id`, `tension_arterial`, `peso`, `motivo_consulta`, `datos_relevantes_examen_fisico`, `diagnostico`, `tratamiento_indicaciones`, `documentacion`, `created_at`, `updated_at`) VALUES
(57, 2, NULL, 1, '100/50', 66.75, 'Dolor de cabeza', NULL, 'Dolor de cabeza cebero', 'Tomar next 1 pastilla por dia', '[\"img\\/historial_documentos\\/1729740318_Mobile wallpaper.jpeg\",\"img\\/historial_documentos\\/1729740318_Vaso Personalizado XV a\\u00f1os\\ud83e\\udd0d\\ud83e\\ude77-Photoroom.png\"]', '2024-10-24 01:46:14', '2024-10-24 01:46:14'),
(58, 2, NULL, 1, '120/60', 68, 'Nada que color preubas', NULL, NULL, '----', '[]', '2024-10-24 01:46:56', '2024-10-24 01:46:56'),
(59, NULL, 8, 1, '80/20', 81.25, 'Dolor corporal', NULL, 'Vino paciente con intenso dolor corporal', 'Reposo 72 hs', '[]', '2024-10-24 01:48:06', '2024-10-24 01:48:06'),
(60, 2, NULL, 2, NULL, NULL, 'preuba de otro profesional', NULL, NULL, 'ada', '[]', '2024-10-24 01:50:44', '2024-10-24 01:50:44'),
(61, 4, NULL, 1, '180/20', 72.25, 'Mica consutla', NULL, 'diangostico mica', 'Indicaciona Mica', '[]', '2024-10-24 06:25:18', '2024-10-24 06:25:18');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes_no_loqueados`
--


CREATE TABLE `pacientes_no_logueados` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `dni` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `obra_social` varchar(255) DEFAULT NULL,
  `numero_afiliado` varchar(255) DEFAULT NULL,
  `motivo_consulta` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes_no_logueados`
--

INSERT INTO `pacientes_no_logueados` (`id`, `dni`, `name`, `telefono`, `direccion`, `obra_social`, `numero_afiliado`, `motivo_consulta`, `created_at`, `updated_at`) VALUES
(8, '41258741', 'Mirta Perez', NULL, NULL, 'PAMI', NULL, NULL, '2024-10-21 10:28:21', '2024-10-23 03:19:47');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2024_08_28_215649_create_profesionales_table', 1),
(5, '2024_08_28_221612_create_pacientes_table', 1),
(6, '2024_09_16_195315_create_secretarios_table', 1),
(7, '2024_09_16_195332_create_turnos_table', 1),
(8, '2024_09_16_195725_create_historial_cambios_table', 1),
(9, '2024_10_01_194039_create_obras_sociales_table', 1),
(10, '2024_10_21_030038_create_pacientes_no_logueados_table', 1),
(11, '2024_10_22_195347_create_historial_clinico_table', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `obras_sociales`
--

CREATE TABLE `obras_sociales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `obras_sociales`
--

INSERT INTO `obras_sociales` (`id`, `nombre`, `created_at`, `updated_at`) VALUES
(1, 'ACA SALUD/AVALIAN', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(2, 'ACLISA/OSPAT', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(3, 'AMFFA SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(4, 'ASO MUTUAL MOTOCICLISTA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(5, 'BOREAL', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(6, 'BRAMED', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(7, 'CONSOLIDAR', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(8, 'DOSEP', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(9, 'DOSPU', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(10, 'FARMACIA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(11, 'FEDERADA SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(12, 'FEMESA SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(13, 'GALENO', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(14, 'INTEGRAL SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(15, 'JERARQUICOS SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(16, 'LUZ Y FUERZA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(17, 'MEDIFE', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(18, 'MEDISALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(19, 'MEDYCIN', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(20, 'MINEROS-ITER MEDICINA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(21, 'MUTUAL MED. RIO CUARTO', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(22, 'OMINT', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(23, 'OSETYA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(24, 'OSPATCA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(25, 'OSSEG', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(26, 'OSFATUN', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(27, 'OSMATA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(28, 'OSDE', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(29, 'O.SOCIAL DEL PERS DEL PAPEL/IMESA', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(30, 'PAMI', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(31, 'PERFUMISTAS', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(32, 'PREVENCION SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(33, 'RED SEGUROS MEDICO', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(34, 'ROISA-DOCTORED', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(35, 'SANCOR SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(36, 'SCIS', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(37, 'SWISS MEDICAL', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(38, 'TV SALUD', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(39, 'UNIMED', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(40, 'UTA-OSCTCP', '2024-10-22 23:58:38', '2024-10-22 23:58:38'),
(41, 'SIN OBRA SOCIAL', '2024-10-22 23:58:38', '2024-10-22 23:58:38');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pacientes`
--

CREATE TABLE `pacientes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `obra_social` varchar(255) DEFAULT NULL,
  `numero_afiliado` varchar(255) DEFAULT NULL,
  `motivo_consulta` varchar(255) DEFAULT NULL,
  `profesionales_a_cargo` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`profesionales_a_cargo`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `pacientes`
--

INSERT INTO `pacientes` (`id`, `user_id`, `obra_social`, `numero_afiliado`, `motivo_consulta`, `profesionales_a_cargo`, `created_at`, `updated_at`) VALUES
(2, 3, 'PAMI', '42278783', 'Motivo de la consulta inicial', NULL, '2024-10-18 22:29:08', '2024-10-22 04:32:25'),
(4, 5, 'SWISS MEDICAL', '42909769', 'Motivo de la consulta inicial', NULL, '2024-10-18 22:32:48', '2024-10-18 22:33:31'),
(6, 7, NULL, NULL, 'Motivo de la consulta inicial', NULL, '2024-10-18 22:35:49', '2024-10-18 22:35:49');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `profesionales`
--

CREATE TABLE `profesionales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `especialidad` varchar(255) DEFAULT NULL,
  `matricula` varchar(255) DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `profesionales`
--

INSERT INTO `profesionales` (`id`, `user_id`, `especialidad`, `matricula`, `imagen`, `created_at`, `updated_at`) VALUES
(1, 2, 'Medica Clinica', 'MP 3162', 'img/profesionales/1729388401-Imagen de WhatsApp 2024-09-28 a las 00.26.44_c4c35944.jpg', '2024-10-18 22:25:55', '2024-10-20 07:40:01'),
(2, 6, 'Futuro Kinesiologo', '---', 'img/profesionales/1729389020-WIN_20240607_13_49_40_Pro.jpg', '2024-10-18 22:34:05', '2024-10-20 07:50:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `secretarios`
--

CREATE TABLE `secretarios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `secretarios`
--

INSERT INTO `secretarios` (`id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 4, '2024-10-18 22:30:20', '2024-10-18 22:30:20');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turnos`
--

CREATE TABLE `turnos` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `paciente_id` bigint(20) UNSIGNED DEFAULT NULL,
  `profesional_id` bigint(20) UNSIGNED NOT NULL,
  `secretario_id` bigint(20) UNSIGNED DEFAULT NULL,
  `dia_hora` datetime NOT NULL,
  `estado` enum('disponible','reservado','completado') NOT NULL,
  `dni_paciente_no_registrado` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `turnos`
--

INSERT INTO `turnos` (`id`, `paciente_id`, `profesional_id`, `secretario_id`, `dia_hora`, `estado`, `dni_paciente_no_registrado`, `created_at`, `updated_at`) VALUES
(1, 2, 1, NULL, '2024-10-18 17:00:00', 'completado', NULL, '2024-10-18 22:29:45', '2024-10-20 07:16:25'),
(2, 4, 2, 1, '2024-10-18 18:00:00', 'completado', NULL, '2024-10-18 22:37:18', '2024-10-20 07:16:25'),
(7, 4, 1, NULL, '2024-10-21 12:00:00', 'completado', NULL, '2024-10-21 10:04:56', '2024-10-23 19:56:05'),
(9, NULL, 1, 1, '2024-10-21 17:00:00', 'completado', '41258741', '2024-10-21 10:28:28', '2024-10-23 19:56:05'),
(22, 2, 1, 1, '2024-10-23 10:00:00', 'completado', NULL, '2024-10-22 11:45:23', '2024-10-23 18:01:36'),
(23, 2, 1, NULL, '2024-10-23 17:00:00', 'completado', NULL, '2024-10-22 11:46:23', '2024-10-24 01:43:41'),
(46, 2, 2, 1, '2024-10-22 18:00:00', 'completado', NULL, '2024-10-22 23:27:13', '2024-10-23 02:04:41');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `fechaNacimiento` date DEFAULT NULL,
  `dni` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `role` enum('paciente','profesional','secretario','admin') NOT NULL DEFAULT 'paciente',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `telefono`, `fechaNacimiento`, `dni`, `direccion`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'admin@example.com', '$2y$12$VBl2d.LPbs33JVjskmooAuE/kzvNdj88xZftMnwoVQLgH.ITtZuZC', NULL, NULL, NULL, NULL, 'admin', NULL, '2024-10-22 23:58:32', '2024-10-22 23:58:32'),
(2, 'Adriana Guadalupe Mencarelli', 'agm83@live.com.ar', '$2y$12$XeEDXnN3er5IZGE6zfT9Ou5p4Xv2iZFZxiey0Jk9O7pkTD79UzzpG', '2656492619', '1973-01-13', '20139709', 'Jose hernandez s/n barrio san martín', 'profesional', NULL, '2024-10-18 22:25:50', '2024-10-18 22:27:37'),
(3, 'Leonardo Gallardo', 'leoja00@gmail.com', '$2y$12$aAu3OXgCnPX89E3MiqLioe8otf1wMqNBXXt2KovI7hpxvBQ/0ylX.', '2664001527', '2000-02-01', '42278783', 'Santa Rosa del Conlara, San Luis', 'paciente', NULL, '2024-10-18 22:29:08', '2024-10-18 22:29:29'),
(4, 'Valeria Perez', 'valeoluna@hotmail.com', '$2y$12$V7RCJdX2H2DZX44rgYf9..z9VZmq/xHq/yyx1S77K45ra2KDA.rcS', '2664544654', '1982-05-17', '27181745', 'Gilberto Gonzalez C15', 'secretario', NULL, '2024-10-18 22:30:16', '2024-10-18 22:32:12'),
(5, 'Micaela Guadalupe Mencarelli', 'miki2@live.com.ar', '$2y$12$10bQMsPS6dI78g2jiQayiu1Q0ZSi6uW3p6Zmy9/1vxlIyrZdNgt12', '2664240966', '2000-07-25', '42909769', 'La Rioja', 'paciente', NULL, '2024-10-18 22:32:48', '2024-10-18 22:33:31'),
(6, 'Profesional Prueba', 'profesional@gmail.com', '$2y$12$AQCnVJ1mjNSfcn8YadxxyOS40tmLCsAkGn8DuFLcvrRp/PxVTZ3YC', '2613352440', '1999-03-16', '21345678', 'La Paz (Desierto)', 'profesional', NULL, '2024-10-18 22:33:55', '2024-10-18 22:35:04'),
(7, 'Paciente Prueba', 'paciente@gmail.com', '$2y$12$rIv5qyFzoNvogVOcVOq06OCMmj1vejZ3tvmvcyLgVt352OOirC.m.', NULL, NULL, NULL, NULL, 'paciente', NULL, '2024-10-18 22:35:49', '2024-10-18 22:35:49');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indices de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

  -- Indices de la tabla `pacientes_no_logueados`
--
ALTER TABLE `pacientes_no_logueados`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_cambios`
--
ALTER TABLE `historial_cambios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_clinico_paciente_id_foreign` (`paciente_id`),
  ADD KEY `historial_clinico_paciente_no_logueado_id_foreign` (`paciente_no_logueado_id`),
  ADD KEY `historial_clinico_profesional_id_foreign` (`profesional_id`);

--
-- Indices de la tabla `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indices de la tabla `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `obras_sociales`
--
ALTER TABLE `obras_sociales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `obras_sociales_nombre_unique` (`nombre`);

--
-- Indices de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pacientes_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `profesionales_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `secretarios`
--
ALTER TABLE `secretarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `secretarios_user_id_foreign` (`user_id`);

--
-- Indices de la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `turnos_paciente_id_foreign` (`paciente_id`),
  ADD KEY `turnos_profesional_id_foreign` (`profesional_id`),
  ADD KEY `turnos_secretario_id_foreign` (`secretario_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_cambios`
--
ALTER TABLE `historial_cambios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT de la tabla `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `obras_sociales`
--
ALTER TABLE `obras_sociales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT de la tabla `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `profesionales`
--
ALTER TABLE `profesionales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `secretarios`
--
ALTER TABLE `secretarios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `turnos`
--
ALTER TABLE `turnos`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `pacientes_no_logueados`
--
ALTER TABLE `pacientes_no_logueados`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;


--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `historial_clinico`
--
ALTER TABLE `historial_clinico`
  ADD CONSTRAINT `historial_clinico_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_clinico_paciente_no_logueado_id_foreign` FOREIGN KEY (`paciente_no_logueado_id`) REFERENCES `pacientes_no_logueados` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historial_clinico_profesional_id_foreign` FOREIGN KEY (`profesional_id`) REFERENCES `profesionales` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `profesionales`
--
ALTER TABLE `profesionales`
  ADD CONSTRAINT `profesionales_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `secretarios`
--
ALTER TABLE `secretarios`
  ADD CONSTRAINT `secretarios_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `turnos`
--
ALTER TABLE `turnos`
  ADD CONSTRAINT `turnos_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_profesional_id_foreign` FOREIGN KEY (`profesional_id`) REFERENCES `profesionales` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `turnos_secretario_id_foreign` FOREIGN KEY (`secretario_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
