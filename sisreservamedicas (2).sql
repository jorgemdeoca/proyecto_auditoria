-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jul 01, 2026 at 09:00 PM
-- Server version: 8.4.3
-- PHP Version: 8.3.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sisreservamedicas`
--

-- --------------------------------------------------------

--
-- Table structure for table `administradores`
--

CREATE TABLE `administradores` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `primer_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_documento` enum('V','E','P','J') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `estado_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `prefijo_tlf` enum('+58','+57','+1','+34') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_tlf` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tema_dinamico` tinyint(1) NOT NULL DEFAULT '0',
  `tipo_admin` enum('Administrador','Root','Supervisor','Recepcionista') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Administrador',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `administradores`
--

INSERT INTO `administradores` (`id`, `user_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `fecha_nac`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `prefijo_tlf`, `numero_tlf`, `genero`, `foto_perfil`, `banner_perfil`, `banner_color`, `tema_dinamico`, `tipo_admin`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Super', NULL, 'Admin', NULL, 'V', '10000001', '1985-01-01', 1, 1, 1, 1, 'Sede Central', '+58', '4120000001', 'Masculino', NULL, NULL, NULL, 0, 'Root', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 'Ana', NULL, 'Ramos', NULL, 'V', '15000002', '1990-05-15', 1, 1, 1, 1, 'Av. Adria, 5, Piso 4, Valle Sebastian Edo. Cojedes, 3322', '+58', '4140000002', 'Femenino', NULL, NULL, NULL, 0, 'Supervisor', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 3, 'Pedro', NULL, 'Castillo', NULL, 'V', '16000003', '1988-11-20', 1, 1, 1, 1, 'Av. Espinosa, Piso 94, Maria de Mata Edo. Miranda', '+58', '4160000003', 'Masculino', NULL, NULL, NULL, 0, 'Recepcionista', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 4, 'Luisa', NULL, 'Mendoza', NULL, 'V', '17000004', '1992-03-10', 1, 1, 1, 1, 'Av. Jorge, Apto 0, Santa Alonsode Asis Edo. Apure, 7067', '+58', '4240000004', 'Femenino', NULL, NULL, NULL, 0, 'Recepcionista', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `administrador_consultorio`
--

CREATE TABLE `administrador_consultorio` (
  `id` bigint UNSIGNED NOT NULL,
  `administrador_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auditorias_historia_base`
--

CREATE TABLE `auditorias_historia_base` (
  `id` bigint UNSIGNED NOT NULL,
  `historia_clinica_base_id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `tipo_accion` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `campo_modificado` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `valor_anterior` text COLLATE utf8mb4_unicode_ci,
  `valor_nuevo` text COLLATE utf8mb4_unicode_ci,
  `motivo_cambio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auditorias_historia_base`
--

INSERT INTO `auditorias_historia_base` (`id`, `historia_clinica_base_id`, `medico_id`, `tipo_accion`, `campo_modificado`, `valor_anterior`, `valor_nuevo`, `motivo_cambio`, `ip_address`, `user_agent`, `created_at`, `updated_at`) VALUES
(1, 4, 8, 'CREACION', NULL, NULL, '{\"grupo_sanguineo\":\"O\",\"factor_rh\":\"+\",\"tipo_sangre\":\"O+\",\"antecedentes_personales\":\"Ninguna\",\"antecedentes_familiares\":\"Cardiaco\",\"enfermedades_cronicas\":null,\"cirugias_previas\":null,\"alergias\":null,\"alergias_medicamentos\":null,\"medicamentos_actuales\":null,\"habito_tabaco\":\"No fuma\",\"habito_alcohol\":\"No consume\",\"actividad_fisica\":\"Ligera\",\"dieta\":\"Sin restricciones\",\"habitos\":null}', 'Creación inicial de historia clínica base', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-30 16:00:35', '2026-06-30 16:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `auditable_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `auditable_id` bigint UNSIGNED NOT NULL,
  `causer_id` bigint UNSIGNED DEFAULT NULL,
  `causer_nombre` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `event` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `modulo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_values` json DEFAULT NULL,
  `new_values` json DEFAULT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `auth_logs`
--

CREATE TABLE `auth_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `correo_intentado` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_type` enum('LOGIN_OK','LOGIN_FAIL','LOGOUT','LOCKOUT','UNLOCK') COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` varchar(512) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `session_id` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `auth_logs`
--

INSERT INTO `auth_logs` (`id`, `user_id`, `correo_intentado`, `event_type`, `ip_address`, `user_agent`, `session_id`, `created_at`) VALUES
(1, 1, 'admin@clinica.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'jKsvbOntYz2n5hYcrsDxY9DUKRA1ILD7PcfkuQig', '2026-07-01 18:45:06'),
(2, 1, 'admin@clinica.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '8f8nTeHQ1luJ7NKaC7nHMxrYKPZGSs99uWaTjyFF', '2026-07-01 18:45:27'),
(3, 12, 'jorgepaciente@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Yfb4vcZ3e7jdmkNMoTKKc48ewzfU7DF8b6zA6egY', '2026-07-01 18:45:37'),
(4, 11, 'jorgemedico@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'RVf3uIQ8BW3WXRJA1qcTFSzxM9whNPM73zsJiUdH', '2026-07-01 18:45:53'),
(5, 1, 'admin@clinica.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'wl6xXHBG2eNl90ShzoimY7uMCEjHBXK85ONYIzMP', '2026-07-01 18:47:47'),
(6, 11, 'jorgemedico@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'eFQyEFp47Dut4hQsDfsAg2b6xuQRBlNtNATP6Nkv', '2026-07-01 18:49:01'),
(7, 12, 'jorgepaciente@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'JEkYXGsk9aKxIkL3tDJXA2E3sVRTyYyK1jQXa4Pq', '2026-07-01 18:49:54'),
(8, 12, 'jorgepaciente@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '8yfg27jDD1LGI2FRdhIApig0eluHF2L4fjxGwfII', '2026-07-01 18:52:24'),
(9, 12, 'jorgepaciente@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '4JyP9Xf7vruxKdNs46taVqHApZK29HmTJoe2CH5z', '2026-07-01 18:57:07'),
(10, 12, 'jorgepaciente@gmail.com', 'LOGOUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '4JyP9Xf7vruxKdNs46taVqHApZK29HmTJoe2CH5z', '2026-07-01 18:57:23'),
(11, 11, 'jorgemedico@gmail.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Pg3H1ByswTuDeghq3kXyOxTi9G5CJDO1xDZaJARd', '2026-07-01 18:57:29'),
(12, 11, 'jorgemedico@gmail.com', 'LOGOUT', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'Pg3H1ByswTuDeghq3kXyOxTi9G5CJDO1xDZaJARd', '2026-07-01 18:57:44'),
(13, 1, 'admin@clinica.com', 'LOGIN_OK', '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', 'R7NvE1wqi7hH7YGQAYGTpIDxpHsfnu2n011qiXY8', '2026-07-01 18:57:53');

-- --------------------------------------------------------

--
-- Table structure for table `citas`
--

CREATE TABLE `citas` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `paciente_especial_id` bigint UNSIGNED DEFAULT NULL,
  `representante_id` bigint UNSIGNED DEFAULT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED DEFAULT NULL,
  `fecha_cita` date NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  `duracion_minutos` smallint DEFAULT NULL,
  `tarifa` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tarifa_extra` decimal(10,2) NOT NULL DEFAULT '0.00',
  `tipo_consulta` enum('Presencial','Online','Domicilio','Consultorio') COLLATE utf8mb4_unicode_ci DEFAULT 'Presencial',
  `direccion_domicilio` text COLLATE utf8mb4_unicode_ci,
  `estado_cita` enum('Programada','Confirmada','En Progreso','Completada','Cancelada','No Asistió') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Programada',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `motivo` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `citas`
--

INSERT INTO `citas` (`id`, `paciente_id`, `paciente_especial_id`, `representante_id`, `medico_id`, `especialidad_id`, `consultorio_id`, `fecha_cita`, `hora_inicio`, `hora_fin`, `duracion_minutos`, `tarifa`, `tarifa_extra`, `tipo_consulta`, `direccion_domicilio`, `estado_cita`, `observaciones`, `motivo`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, NULL, 7, 3, 4, '2026-07-20', '11:00:00', '11:30:00', 30, 66.27, 0.00, 'Online', NULL, 'Programada', 'Nihil quia ut eligendi voluptas vel qui eveniet iusto.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, NULL, NULL, 5, 19, 3, '2026-08-29', '16:00:00', '16:30:00', 30, 76.37, 0.00, 'Presencial', NULL, 'Confirmada', 'Consectetur et aut esse.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 1, NULL, NULL, 6, 8, 7, '2026-07-10', '09:00:00', '09:30:00', 30, 96.55, 0.00, 'Presencial', NULL, 'Confirmada', 'Vero sed necessitatibus aliquid quis rerum ab beatae.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 2, NULL, NULL, 5, 12, 4, '2026-06-11', '15:00:00', '15:30:00', 30, 70.95, 0.00, 'Online', NULL, 'Completada', 'Autem eos aut voluptas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 2, NULL, NULL, 6, 14, 5, '2026-08-07', '14:00:00', '14:30:00', 30, 54.16, 0.00, 'Online', NULL, 'Confirmada', 'Dicta ut error voluptatem minus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 2, NULL, NULL, 6, 9, 3, '2026-08-15', '16:00:00', '16:30:00', 30, 47.77, 0.00, 'Presencial', NULL, 'Programada', 'Et saepe accusamus iste porro.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(7, 1, NULL, NULL, 7, 7, 4, '2026-08-06', '14:00:00', '14:30:00', 30, 29.19, 0.00, 'Online', NULL, 'Programada', 'Enim perferendis vel vitae qui exercitationem optio.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(8, 1, NULL, NULL, 6, 15, 4, '2026-06-29', '11:00:00', '11:30:00', 30, 28.98, 0.00, 'Online', NULL, 'No Asistió', 'Excepturi ipsam rem necessitatibus ad eum necessitatibus cupiditate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(9, 2, NULL, NULL, 7, 4, 3, '2026-06-16', '08:00:00', '08:30:00', 30, 59.21, 0.00, 'Presencial', NULL, 'Cancelada', 'Occaecati occaecati dolorem omnis praesentium error ratione consequatur corrupti.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(10, 1, NULL, NULL, 7, 8, 8, '2026-06-27', '10:00:00', '10:30:00', 30, 77.88, 0.00, 'Presencial', NULL, 'Cancelada', 'Labore id minus molestiae aut vero ea.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(11, 2, NULL, NULL, 6, 15, 1, '2026-05-15', '15:00:00', '15:30:00', 30, 72.02, 0.00, 'Online', NULL, 'Completada', 'Omnis numquam ullam omnis non saepe.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(12, 1, NULL, NULL, 7, 19, 4, '2026-05-18', '16:00:00', '16:30:00', 30, 80.28, 0.00, 'Online', NULL, 'No Asistió', 'Sint omnis est voluptatem in in qui.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(13, 2, NULL, NULL, 7, 8, 3, '2026-07-30', '11:00:00', '11:30:00', 30, 42.22, 0.00, 'Online', NULL, 'Programada', 'Est ad nobis harum autem omnis.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(14, 2, NULL, NULL, 6, 16, 3, '2026-08-18', '09:00:00', '09:30:00', 30, 93.17, 0.00, 'Presencial', NULL, 'Programada', 'Autem odit cupiditate tenetur et sit voluptatum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(15, 3, NULL, NULL, 7, 13, 7, '2026-08-02', '09:00:00', '09:30:00', 30, 67.37, 0.00, 'Online', NULL, 'Confirmada', 'Itaque sapiente sed ab sed voluptate quisquam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(16, 1, NULL, NULL, 6, 7, 2, '2026-06-13', '08:00:00', '08:30:00', 30, 28.48, 0.00, 'Online', NULL, 'Completada', 'Eos consequatur iste distinctio non quaerat quas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(17, 1, NULL, NULL, 7, 9, 5, '2026-07-26', '16:00:00', '16:30:00', 30, 26.57, 0.00, 'Online', NULL, 'Programada', 'Natus omnis reiciendis ipsa.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(18, 1, NULL, NULL, 7, 5, 3, '2026-08-16', '09:00:00', '09:30:00', 30, 88.80, 0.00, 'Online', NULL, 'Confirmada', 'Id corrupti ullam dicta alias aliquid doloribus voluptas consequatur.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(19, 3, NULL, NULL, 7, 16, 4, '2026-06-23', '09:00:00', '09:30:00', 30, 78.81, 0.00, 'Presencial', NULL, 'Completada', 'Repudiandae est rerum odit facilis est fugit accusantium.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(20, 2, NULL, NULL, 6, 18, 6, '2026-07-09', '08:00:00', '08:30:00', 30, 97.89, 0.00, 'Presencial', NULL, 'Programada', 'Quo totam voluptates illo praesentium laudantium nihil deleniti.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(21, 1, NULL, NULL, 6, 17, 3, '2026-07-01', '08:00:00', '08:30:00', 30, 33.42, 0.00, 'Presencial', NULL, 'Programada', 'Sunt pariatur molestiae possimus pariatur.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(22, 2, NULL, NULL, 7, 5, 7, '2026-05-27', '15:00:00', '15:30:00', 30, 93.66, 0.00, 'Presencial', NULL, 'No Asistió', 'Laboriosam ipsa sint commodi.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(23, 3, NULL, NULL, 7, 3, 1, '2026-06-08', '10:00:00', '10:30:00', 30, 49.38, 0.00, 'Presencial', NULL, 'Completada', 'Animi molestiae non dolorum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(24, 1, NULL, NULL, 6, 8, 8, '2026-06-01', '09:00:00', '09:30:00', 30, 33.50, 0.00, 'Online', NULL, 'Cancelada', 'Asperiores sed suscipit et occaecati.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(25, 2, NULL, NULL, 5, 6, 8, '2026-07-07', '16:00:00', '16:30:00', 30, 67.15, 0.00, 'Online', NULL, 'Programada', 'Autem reprehenderit sit fugiat sunt quia non iusto.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(26, 3, NULL, NULL, 6, 10, 8, '2026-07-20', '08:00:00', '08:30:00', 30, 47.38, 0.00, 'Presencial', NULL, 'Confirmada', 'Quod tempora nesciunt nihil minus aut quod sequi.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(27, 1, NULL, NULL, 6, 7, 8, '2026-05-21', '16:00:00', '16:30:00', 30, 39.80, 0.00, 'Presencial', NULL, 'Completada', 'Aspernatur molestiae doloremque quia accusantium consectetur voluptas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(28, 1, NULL, NULL, 5, 9, 4, '2026-05-12', '11:00:00', '11:30:00', 30, 97.70, 0.00, 'Online', NULL, 'Cancelada', 'Consequatur necessitatibus suscipit quia nihil veniam asperiores omnis.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(29, 1, NULL, NULL, 6, 18, 1, '2026-05-22', '11:00:00', '11:30:00', 30, 66.84, 0.00, 'Online', NULL, 'No Asistió', 'Rerum impedit ratione possimus ad eum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(30, 1, NULL, NULL, 5, 15, 7, '2026-08-16', '16:00:00', '16:30:00', 30, 28.99, 0.00, 'Presencial', NULL, 'Programada', 'Aliquam ratione ipsam expedita dolore.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(31, 2, NULL, NULL, 6, 5, 6, '2026-07-13', '08:00:00', '08:30:00', 30, 40.48, 0.00, 'Online', NULL, 'Confirmada', 'Nam sunt non iusto qui laudantium.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(32, 1, NULL, NULL, 7, 6, 4, '2026-08-11', '09:00:00', '09:30:00', 30, 97.19, 0.00, 'Online', NULL, 'Programada', 'Quasi accusantium saepe nihil nihil.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(33, 1, NULL, NULL, 6, 3, 1, '2026-08-27', '15:00:00', '15:30:00', 30, 58.42, 0.00, 'Online', NULL, 'Confirmada', 'Sint accusamus quasi tempora accusamus velit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(34, 2, NULL, NULL, 7, 4, 5, '2026-07-25', '08:00:00', '08:30:00', 30, 72.73, 0.00, 'Online', NULL, 'Programada', 'Ipsam eos omnis quia ut possimus ratione et.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(35, 2, NULL, NULL, 7, 18, 6, '2026-06-11', '10:00:00', '10:30:00', 30, 28.32, 0.00, 'Online', NULL, 'Cancelada', 'Ea soluta nam rem provident omnis unde et repellat.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(36, 1, NULL, NULL, 6, 13, 5, '2026-07-16', '15:00:00', '15:30:00', 30, 68.13, 0.00, 'Presencial', NULL, 'Programada', 'Error corporis odit qui eaque expedita minus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(37, 2, NULL, NULL, 6, 11, 2, '2026-07-04', '08:00:00', '08:30:00', 30, 46.97, 0.00, 'Online', NULL, 'Programada', 'Reiciendis dolorem debitis velit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(38, 1, NULL, NULL, 7, 10, 4, '2026-06-27', '16:00:00', '16:30:00', 30, 41.79, 0.00, 'Presencial', NULL, 'No Asistió', 'Commodi minima debitis molestiae ut iure officia.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(39, 2, NULL, NULL, 6, 15, 3, '2026-06-10', '15:00:00', '15:30:00', 30, 57.12, 0.00, 'Presencial', NULL, 'No Asistió', 'Quibusdam perferendis porro inventore repudiandae dolorem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(40, 1, NULL, NULL, 7, 13, 3, '2026-07-20', '16:00:00', '16:30:00', 30, 88.68, 0.00, 'Online', NULL, 'Confirmada', 'Et sequi est itaque suscipit officia.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(41, 2, NULL, NULL, 5, 9, 2, '2026-05-05', '09:00:00', '09:30:00', 30, 67.95, 0.00, 'Presencial', NULL, 'Cancelada', 'Qui et accusantium suscipit velit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(42, 2, NULL, NULL, 5, 12, 4, '2026-08-18', '11:00:00', '11:30:00', 30, 58.49, 0.00, 'Presencial', NULL, 'Confirmada', 'Nisi enim velit ratione mollitia voluptas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(43, 3, NULL, NULL, 7, 7, 3, '2026-05-18', '08:00:00', '08:30:00', 30, 51.91, 0.00, 'Online', NULL, 'Completada', 'Voluptatem nihil nam in quis ducimus quae.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(44, 1, NULL, NULL, 6, 7, 7, '2026-06-19', '08:00:00', '08:30:00', 30, 21.92, 0.00, 'Presencial', NULL, 'Cancelada', 'Inventore autem aut alias quaerat facere et accusantium.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(45, 2, NULL, NULL, 5, 13, 6, '2026-08-20', '14:00:00', '14:30:00', 30, 66.38, 0.00, 'Online', NULL, 'Confirmada', 'Adipisci explicabo dolor asperiores omnis sed excepturi numquam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(46, 3, NULL, NULL, 7, 15, 6, '2026-08-22', '11:00:00', '11:30:00', 30, 75.05, 0.00, 'Online', NULL, 'Confirmada', 'Saepe voluptas sapiente id officiis odio error.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(47, 1, NULL, NULL, 7, 1, 5, '2026-05-20', '10:00:00', '10:30:00', 30, 76.95, 0.00, 'Online', NULL, 'Cancelada', 'Ducimus sunt ea et odit et sit cum cupiditate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(48, 3, NULL, NULL, 6, 15, 8, '2026-05-27', '16:00:00', '16:30:00', 30, 99.49, 0.00, 'Online', NULL, 'No Asistió', 'Nesciunt ut qui sed sit fugit amet voluptas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(49, 3, NULL, NULL, 5, 5, 6, '2026-08-23', '14:00:00', '14:30:00', 30, 90.63, 0.00, 'Online', NULL, 'Confirmada', 'Nihil minima ut ut ad.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(50, 2, NULL, NULL, 6, 10, 3, '2026-08-18', '09:00:00', '09:30:00', 30, 79.93, 0.00, 'Presencial', NULL, 'Confirmada', 'Velit quisquam mollitia et cupiditate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(51, 1, NULL, NULL, 5, 4, 6, '2026-06-18', '10:00:00', '10:30:00', 30, 74.17, 0.00, 'Presencial', NULL, 'No Asistió', 'Nulla blanditiis sit saepe odit ipsam expedita amet quibusdam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(52, 2, NULL, NULL, 5, 12, 1, '2026-08-22', '08:00:00', '08:30:00', 30, 61.95, 0.00, 'Online', NULL, 'Programada', 'Quisquam enim hic ea sit rerum exercitationem inventore eligendi.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(53, 1, NULL, NULL, 6, 2, 7, '2026-05-15', '08:00:00', '08:30:00', 30, 26.11, 0.00, 'Online', NULL, 'Completada', 'Odio ullam omnis illum expedita illum sint sit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(54, 2, NULL, NULL, 6, 8, 3, '2026-08-12', '15:00:00', '15:30:00', 30, 38.89, 0.00, 'Online', NULL, 'Confirmada', 'Non dolor natus distinctio officiis ipsum recusandae ducimus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(55, 3, NULL, NULL, 5, 20, 2, '2026-08-20', '11:00:00', '11:30:00', 30, 57.77, 0.00, 'Online', NULL, 'Confirmada', 'Enim deleniti minus velit ullam quis illum fugit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(56, 1, NULL, NULL, 5, 3, 8, '2026-08-18', '11:00:00', '11:30:00', 30, 24.82, 0.00, 'Online', NULL, 'Confirmada', 'Deserunt voluptatibus deleniti hic odio esse id ut voluptatem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(57, 2, NULL, NULL, 7, 2, 6, '2026-06-02', '15:00:00', '15:30:00', 30, 63.43, 0.00, 'Online', NULL, 'No Asistió', 'Quos in amet et.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(58, 2, NULL, NULL, 7, 16, 2, '2026-05-25', '15:00:00', '15:30:00', 30, 22.85, 0.00, 'Online', NULL, 'Cancelada', 'Mollitia ullam possimus sit nihil atque.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(59, 1, NULL, NULL, 6, 10, 3, '2026-05-21', '10:00:00', '10:30:00', 30, 35.76, 0.00, 'Online', NULL, 'Completada', 'Occaecati dolores omnis praesentium recusandae provident quidem enim.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(60, 2, NULL, NULL, 7, 6, 6, '2026-06-08', '15:00:00', '15:30:00', 30, 27.12, 0.00, 'Online', NULL, 'Cancelada', 'Quibusdam officia cumque qui aut totam quae ut.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(61, 3, NULL, NULL, 7, 3, 7, '2026-06-26', '14:00:00', '14:30:00', 30, 32.35, 0.00, 'Online', NULL, 'Cancelada', 'Delectus corrupti optio odit in optio quae cupiditate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(62, 2, NULL, NULL, 5, 16, 6, '2026-06-03', '16:00:00', '16:30:00', 30, 33.95, 0.00, 'Presencial', NULL, 'Cancelada', 'Cumque blanditiis aut unde occaecati officiis ipsa ut.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(63, 2, NULL, NULL, 6, 15, 4, '2026-08-25', '14:00:00', '14:30:00', 30, 33.42, 0.00, 'Online', NULL, 'Confirmada', 'Placeat soluta dolorum ipsam optio asperiores.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(64, 2, NULL, NULL, 5, 5, 4, '2026-05-29', '10:00:00', '10:30:00', 30, 91.81, 0.00, 'Online', NULL, 'Cancelada', 'Sed facilis consequuntur perspiciatis molestiae assumenda sit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(65, 3, NULL, NULL, 5, 15, 1, '2026-07-29', '10:00:00', '10:30:00', 30, 86.58, 0.00, 'Presencial', NULL, 'Programada', 'Voluptatum modi rem nemo ut est.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(66, 1, NULL, NULL, 6, 17, 6, '2026-06-24', '09:00:00', '09:30:00', 30, 77.60, 0.00, 'Presencial', NULL, 'No Asistió', 'Incidunt et at et minima quas alias.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(67, 2, NULL, NULL, 6, 19, 6, '2026-06-25', '15:00:00', '15:30:00', 30, 40.72, 0.00, 'Presencial', NULL, 'No Asistió', 'Nobis quos rerum dolore quo necessitatibus id maiores.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(68, 2, NULL, NULL, 5, 10, 5, '2026-07-30', '10:00:00', '10:30:00', 30, 53.80, 0.00, 'Presencial', NULL, 'Programada', 'Deserunt animi qui laudantium voluptate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(69, 2, NULL, NULL, 7, 7, 2, '2026-08-29', '14:00:00', '14:30:00', 30, 46.80, 0.00, 'Presencial', NULL, 'Programada', 'Omnis molestiae quidem necessitatibus voluptatem molestiae est repudiandae voluptatem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(70, 2, NULL, NULL, 6, 20, 3, '2026-05-12', '14:00:00', '14:30:00', 30, 48.38, 0.00, 'Presencial', NULL, 'Cancelada', 'Id et eos temporibus occaecati.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(71, 1, NULL, NULL, 5, 19, 7, '2026-08-20', '14:00:00', '14:30:00', 30, 66.90, 0.00, 'Online', NULL, 'Confirmada', 'Facilis tempore et qui.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(72, 3, NULL, NULL, 6, 3, 1, '2026-05-16', '09:00:00', '09:30:00', 30, 77.41, 0.00, 'Presencial', NULL, 'No Asistió', 'Omnis et non sed vel.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(73, 1, NULL, NULL, 6, 7, 6, '2026-08-29', '14:00:00', '14:30:00', 30, 35.59, 0.00, 'Presencial', NULL, 'Programada', 'Qui odio omnis sequi ipsam harum doloribus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(74, 2, NULL, NULL, 7, 11, 2, '2026-08-01', '14:00:00', '14:30:00', 30, 32.54, 0.00, 'Online', NULL, 'Programada', 'Asperiores hic repudiandae voluptatum quisquam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(75, 1, NULL, NULL, 5, 1, 2, '2026-07-23', '10:00:00', '10:30:00', 30, 29.56, 0.00, 'Online', NULL, 'Programada', 'Accusantium veritatis consequatur fugiat voluptatem modi repudiandae similique maxime.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(76, 1, NULL, NULL, 7, 1, 2, '2026-06-16', '15:00:00', '15:30:00', 30, 50.35, 0.00, 'Online', NULL, 'Cancelada', 'Repellat maxime et voluptas aut.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(77, 3, NULL, NULL, 7, 1, 1, '2026-05-28', '09:00:00', '09:30:00', 30, 41.70, 0.00, 'Presencial', NULL, 'Cancelada', 'Quos labore aut dignissimos vitae possimus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(78, 3, NULL, NULL, 5, 13, 5, '2026-05-12', '09:00:00', '09:30:00', 30, 79.92, 0.00, 'Online', NULL, 'Completada', 'Autem repellat velit temporibus quas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(79, 2, NULL, NULL, 5, 16, 2, '2026-05-01', '10:00:00', '10:30:00', 30, 57.95, 0.00, 'Presencial', NULL, 'Cancelada', 'Est magnam inventore aut iusto id tempora dicta.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(80, 2, NULL, NULL, 7, 14, 3, '2026-07-23', '14:00:00', '14:30:00', 30, 96.41, 0.00, 'Presencial', NULL, 'Programada', 'Ipsam dolores non aut neque.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(81, 1, NULL, NULL, 7, 5, 4, '2026-06-25', '09:00:00', '09:30:00', 30, 62.30, 0.00, 'Online', NULL, 'Completada', 'Voluptas aspernatur officia eos inventore id sit repellendus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(82, 2, NULL, NULL, 6, 19, 7, '2026-08-17', '16:00:00', '16:30:00', 30, 79.19, 0.00, 'Online', NULL, 'Programada', 'Dicta quidem voluptatem neque minus labore nostrum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(83, 1, NULL, NULL, 6, 18, 8, '2026-07-01', '16:00:00', '16:30:00', 30, 65.43, 0.00, 'Presencial', NULL, 'Confirmada', 'Autem laudantium similique incidunt ut quas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(84, 3, NULL, NULL, 7, 7, 8, '2026-06-28', '09:00:00', '09:30:00', 30, 89.07, 0.00, 'Presencial', NULL, 'Cancelada', 'Earum neque nam voluptatem nihil omnis veritatis.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(85, 3, NULL, NULL, 7, 11, 3, '2026-07-09', '08:00:00', '08:30:00', 30, 75.51, 0.00, 'Presencial', NULL, 'Confirmada', 'Error provident distinctio quo hic molestias.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(86, 1, NULL, NULL, 7, 11, 1, '2026-05-12', '10:00:00', '10:30:00', 30, 51.39, 0.00, 'Presencial', NULL, 'Completada', 'Molestiae rem expedita consectetur rerum autem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(87, 2, NULL, NULL, 7, 17, 8, '2026-07-07', '11:00:00', '11:30:00', 30, 93.97, 0.00, 'Presencial', NULL, 'Programada', 'Quaerat necessitatibus aperiam sapiente tempora.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(88, 1, NULL, NULL, 5, 9, 7, '2026-08-15', '15:00:00', '15:30:00', 30, 41.44, 0.00, 'Presencial', NULL, 'Confirmada', 'Ut blanditiis quasi et ut ullam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(89, 1, NULL, NULL, 5, 18, 7, '2026-08-10', '09:00:00', '09:30:00', 30, 45.13, 0.00, 'Online', NULL, 'Programada', 'Ipsam inventore molestiae qui pariatur et voluptatem occaecati.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(90, 2, NULL, NULL, 6, 1, 6, '2026-07-24', '15:00:00', '15:30:00', 30, 39.91, 0.00, 'Online', NULL, 'Programada', 'Incidunt maiores dolorum quia voluptatem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(91, 3, NULL, NULL, 6, 12, 5, '2026-08-21', '14:00:00', '14:30:00', 30, 95.90, 0.00, 'Presencial', NULL, 'Confirmada', 'Dignissimos molestiae explicabo voluptatem rerum et soluta.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(92, 1, NULL, NULL, 7, 10, 1, '2026-08-24', '09:00:00', '09:30:00', 30, 82.45, 0.00, 'Online', NULL, 'Programada', 'Beatae nisi incidunt odit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(93, 1, NULL, NULL, 6, 4, 5, '2026-08-18', '09:00:00', '09:30:00', 30, 52.47, 0.00, 'Online', NULL, 'Confirmada', 'Eaque debitis officiis in qui repellendus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(94, 2, NULL, NULL, 6, 2, 4, '2026-07-08', '16:00:00', '16:30:00', 30, 80.48, 0.00, 'Presencial', NULL, 'Programada', 'Voluptatem distinctio ut dolorem veniam accusantium veritatis aperiam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(95, 3, NULL, NULL, 7, 15, 1, '2026-07-20', '16:00:00', '16:30:00', 30, 71.04, 0.00, 'Online', NULL, 'Confirmada', 'Sint odit rerum dignissimos amet et.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(96, 1, NULL, NULL, 7, 4, 1, '2026-05-07', '08:00:00', '08:30:00', 30, 51.31, 0.00, 'Presencial', NULL, 'Completada', 'Quis a ut ipsa praesentium.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(97, 1, NULL, NULL, 7, 14, 6, '2026-07-04', '15:00:00', '15:30:00', 30, 96.19, 0.00, 'Online', NULL, 'Programada', 'Temporibus sequi molestiae aut ea doloremque unde.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(98, 1, NULL, NULL, 7, 7, 5, '2026-06-05', '08:00:00', '08:30:00', 30, 92.45, 0.00, 'Presencial', NULL, 'Cancelada', 'Expedita voluptas quia temporibus dolorum quae deserunt quam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(99, 3, NULL, NULL, 7, 18, 6, '2026-07-01', '14:00:00', '14:30:00', 30, 96.08, 0.00, 'Presencial', NULL, 'Confirmada', 'Quia ea molestias aliquam rem qui non reiciendis.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(100, 1, NULL, NULL, 5, 17, 6, '2026-08-04', '11:00:00', '11:30:00', 30, 58.87, 0.00, 'Presencial', NULL, 'Programada', 'Expedita aut sequi harum et earum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(101, 2, NULL, NULL, 5, 5, 3, '2026-08-14', '11:00:00', '11:30:00', 30, 21.46, 0.00, 'Online', NULL, 'Confirmada', 'Vero voluptatem ab possimus voluptatum et voluptates.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(102, 2, NULL, NULL, 5, 14, 2, '2026-06-16', '11:00:00', '11:30:00', 30, 45.45, 0.00, 'Presencial', NULL, 'Cancelada', 'Quae veritatis officiis voluptatem occaecati cum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(103, 2, NULL, NULL, 5, 12, 7, '2026-06-28', '14:00:00', '14:30:00', 30, 34.70, 0.00, 'Online', NULL, 'Cancelada', 'Autem possimus dolor at.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(104, 3, NULL, NULL, 6, 7, 6, '2026-06-15', '14:00:00', '14:30:00', 30, 43.28, 0.00, 'Online', NULL, 'Cancelada', 'Dolore laborum est impedit veniam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(105, 1, NULL, NULL, 7, 14, 3, '2026-08-15', '08:00:00', '08:30:00', 30, 53.30, 0.00, 'Online', NULL, 'Confirmada', 'Impedit voluptatem autem expedita beatae eos.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(106, 1, NULL, NULL, 7, 2, 3, '2026-07-17', '10:00:00', '10:30:00', 30, 78.58, 0.00, 'Online', NULL, 'Confirmada', 'Officia ut dicta ut dolorem velit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(107, 2, NULL, NULL, 5, 19, 7, '2026-05-14', '16:00:00', '16:30:00', 30, 38.10, 0.00, 'Presencial', NULL, 'Completada', 'Suscipit dolore dolores rerum similique et quo.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(108, 3, NULL, NULL, 5, 19, 4, '2026-07-11', '15:00:00', '15:30:00', 30, 42.37, 0.00, 'Online', NULL, 'Programada', 'Sit enim omnis aut quia harum minus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(109, 2, NULL, NULL, 7, 13, 5, '2026-06-27', '09:00:00', '09:30:00', 30, 93.11, 0.00, 'Online', NULL, 'No Asistió', 'Qui sint nihil praesentium.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(110, 2, NULL, NULL, 6, 16, 7, '2026-05-30', '11:00:00', '11:30:00', 30, 34.01, 0.00, 'Online', NULL, 'Cancelada', 'Nulla nesciunt blanditiis aut dolor dolorum amet vitae.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(111, 1, NULL, NULL, 5, 12, 3, '2026-06-17', '10:00:00', '10:30:00', 30, 85.96, 0.00, 'Presencial', NULL, 'Completada', 'Ea velit rerum aperiam ex.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(112, 2, NULL, NULL, 6, 16, 5, '2026-07-13', '15:00:00', '15:30:00', 30, 68.31, 0.00, 'Presencial', NULL, 'Programada', 'Ad eum qui ut magnam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(113, 1, NULL, NULL, 5, 20, 8, '2026-06-07', '15:00:00', '15:30:00', 30, 60.06, 0.00, 'Presencial', NULL, 'Completada', 'Eos quisquam velit et tempore voluptatum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(114, 3, NULL, NULL, 5, 17, 7, '2026-08-23', '15:00:00', '15:30:00', 30, 46.33, 0.00, 'Presencial', NULL, 'Confirmada', 'Blanditiis sint saepe aliquid nisi consequuntur.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(115, 2, NULL, NULL, 7, 11, 5, '2026-08-01', '10:00:00', '10:30:00', 30, 33.29, 0.00, 'Presencial', NULL, 'Confirmada', 'Temporibus aut aperiam cum repellendus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(116, 3, NULL, NULL, 5, 3, 7, '2026-08-09', '15:00:00', '15:30:00', 30, 99.42, 0.00, 'Online', NULL, 'Programada', 'Nostrum dolor est mollitia.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(117, 1, NULL, NULL, 7, 13, 5, '2026-05-07', '09:00:00', '09:30:00', 30, 45.93, 0.00, 'Online', NULL, 'Completada', 'Occaecati rerum officia est maiores sed impedit deserunt sunt.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(118, 3, NULL, NULL, 6, 18, 7, '2026-06-06', '16:00:00', '16:30:00', 30, 53.96, 0.00, 'Online', NULL, 'Completada', 'Voluptatem labore id ea et debitis dolores.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(119, 2, NULL, NULL, 6, 2, 5, '2026-06-01', '11:00:00', '11:30:00', 30, 29.34, 0.00, 'Online', NULL, 'No Asistió', 'Et excepturi dolore pariatur qui sint sit.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(120, 3, NULL, NULL, 5, 3, 7, '2026-06-22', '16:00:00', '16:30:00', 30, 38.89, 0.00, 'Presencial', NULL, 'Cancelada', 'Commodi doloremque eveniet fuga fugit nulla inventore.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(121, 1, NULL, NULL, 7, 17, 2, '2026-05-31', '14:00:00', '14:30:00', 30, 50.71, 0.00, 'Presencial', NULL, 'No Asistió', 'Libero quidem ea neque blanditiis voluptates cupiditate.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(122, 2, NULL, NULL, 7, 20, 2, '2026-05-15', '15:00:00', '15:30:00', 30, 98.56, 0.00, 'Online', NULL, 'Completada', 'Officiis incidunt magnam perferendis voluptas tempore libero.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(123, 2, NULL, NULL, 7, 17, 6, '2026-06-21', '14:00:00', '14:30:00', 30, 70.78, 0.00, 'Online', NULL, 'Cancelada', 'Repellat odit dolorum quas ullam deserunt ut soluta.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(124, 1, NULL, NULL, 6, 3, 6, '2026-08-12', '09:00:00', '09:30:00', 30, 98.28, 0.00, 'Online', NULL, 'Programada', 'Ea enim non quis molestiae ut eum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(125, 2, NULL, NULL, 6, 9, 1, '2026-05-06', '14:00:00', '14:30:00', 30, 35.25, 0.00, 'Presencial', NULL, 'Cancelada', 'Rerum quo quia tenetur consequuntur eaque perspiciatis inventore.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(126, 3, NULL, NULL, 6, 18, 4, '2026-07-24', '09:00:00', '09:30:00', 30, 47.41, 0.00, 'Online', NULL, 'Confirmada', 'Est et cupiditate qui.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(127, 2, NULL, NULL, 5, 20, 1, '2026-07-22', '15:00:00', '15:30:00', 30, 60.50, 0.00, 'Presencial', NULL, 'Confirmada', 'Tempora labore et fugiat possimus atque molestias molestiae.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(128, 1, NULL, NULL, 6, 15, 8, '2026-05-27', '16:00:00', '16:30:00', 30, 62.52, 0.00, 'Online', NULL, 'No Asistió', 'Sint reprehenderit ut delectus voluptas rerum.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(129, 2, NULL, NULL, 7, 17, 2, '2026-08-14', '08:00:00', '08:30:00', 30, 42.21, 0.00, 'Online', NULL, 'Confirmada', 'Consequatur et molestiae tempore doloribus magnam dolor.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(130, 2, NULL, NULL, 5, 1, 7, '2026-08-30', '15:00:00', '15:30:00', 30, 73.25, 0.00, 'Online', NULL, 'Programada', 'Assumenda error qui qui laudantium amet.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(131, 2, NULL, NULL, 7, 16, 6, '2026-07-07', '10:00:00', '10:30:00', 30, 44.11, 0.00, 'Presencial', NULL, 'Confirmada', 'Error omnis cumque quas.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(132, 2, NULL, NULL, 7, 7, 5, '2026-06-15', '10:00:00', '10:30:00', 30, 77.49, 0.00, 'Presencial', NULL, 'Cancelada', 'Debitis consequuntur dolor rerum quod ducimus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(133, 2, NULL, NULL, 5, 16, 4, '2026-05-22', '10:00:00', '10:30:00', 30, 71.76, 0.00, 'Presencial', NULL, 'No Asistió', 'Autem sunt itaque ut quia.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(134, 3, NULL, NULL, 6, 14, 8, '2026-06-17', '09:00:00', '09:30:00', 30, 96.24, 0.00, 'Online', NULL, 'Cancelada', 'Cupiditate suscipit autem quis vero sunt.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(135, 1, NULL, NULL, 5, 6, 4, '2026-07-16', '09:00:00', '09:30:00', 30, 25.74, 0.00, 'Online', NULL, 'Programada', 'Nemo beatae quae omnis voluptas quo deserunt.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(136, 1, NULL, NULL, 5, 5, 1, '2026-07-07', '08:00:00', '08:30:00', 30, 76.76, 0.00, 'Presencial', NULL, 'Programada', 'Et laboriosam et ducimus possimus quas voluptatem.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(137, 3, NULL, NULL, 6, 9, 3, '2026-05-11', '11:00:00', '11:30:00', 30, 57.10, 0.00, 'Online', NULL, 'Cancelada', 'Qui rerum praesentium est.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(138, 1, NULL, NULL, 6, 7, 8, '2026-06-21', '09:00:00', '09:30:00', 30, 27.29, 0.00, 'Presencial', NULL, 'Cancelada', 'Vel qui molestiae voluptatibus in iure vel.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(139, 1, NULL, NULL, 5, 17, 8, '2026-07-15', '09:00:00', '09:30:00', 30, 79.43, 0.00, 'Online', NULL, 'Confirmada', 'Velit aut eligendi assumenda.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(140, 3, NULL, NULL, 5, 17, 5, '2026-08-22', '08:00:00', '08:30:00', 30, 34.94, 0.00, 'Online', NULL, 'Confirmada', 'Magni qui et quia ea.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(141, 3, NULL, NULL, 5, 20, 6, '2026-07-16', '10:00:00', '10:30:00', 30, 74.99, 0.00, 'Online', NULL, 'Confirmada', 'Totam aliquid adipisci quod.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(142, 2, NULL, NULL, 6, 1, 1, '2026-05-09', '16:00:00', '16:30:00', 30, 41.35, 0.00, 'Online', NULL, 'Completada', 'Commodi harum ratione optio sit aut.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(143, 1, NULL, NULL, 5, 4, 6, '2026-08-10', '14:00:00', '14:30:00', 30, 45.45, 0.00, 'Online', NULL, 'Confirmada', 'Sit ea dicta ea nisi ad.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(144, 1, NULL, NULL, 7, 17, 7, '2026-07-27', '15:00:00', '15:30:00', 30, 41.61, 0.00, 'Presencial', NULL, 'Programada', 'Nemo fugiat veniam architecto velit enim veniam.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(145, 3, NULL, NULL, 5, 14, 1, '2026-08-08', '09:00:00', '09:30:00', 30, 54.15, 0.00, 'Online', NULL, 'Programada', 'Nihil quis vel perferendis unde quia tenetur neque.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(146, 1, NULL, NULL, 6, 16, 1, '2026-08-20', '11:00:00', '11:30:00', 30, 30.59, 0.00, 'Online', NULL, 'Confirmada', 'Doloremque illum ipsum exercitationem esse.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(147, 2, NULL, NULL, 5, 18, 2, '2026-08-16', '11:00:00', '11:30:00', 30, 31.82, 0.00, 'Online', NULL, 'Programada', 'Quam repellat et dolores occaecati quibusdam facere deserunt.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(148, 2, NULL, NULL, 6, 15, 4, '2026-06-22', '09:00:00', '09:30:00', 30, 30.00, 0.00, 'Presencial', NULL, 'Completada', 'Et molestias voluptates est architecto molestiae enim.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(149, 1, NULL, NULL, 6, 1, 1, '2026-05-27', '11:00:00', '11:30:00', 30, 69.09, 0.00, 'Online', NULL, 'No Asistió', 'Eos exercitationem sed debitis omnis ea.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(150, 1, NULL, NULL, 6, 3, 1, '2026-07-24', '09:00:00', '09:30:00', 30, 68.78, 0.00, 'Presencial', NULL, 'Confirmada', 'Aut vitae molestiae quia ad minus.', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(151, 4, NULL, NULL, 8, 2, 5, '2026-07-06', '14:00:00', '14:30:00', 30, 50.00, 0.00, 'Presencial', 'cabudare', 'Completada', NULL, 'ninguno', 1, '2026-06-30 15:49:01', '2026-06-30 17:02:44'),
(152, 4, NULL, NULL, 8, 2, 5, '2026-07-13', '14:00:00', '14:30:00', 30, 50.00, 0.00, 'Presencial', 'cabudare', 'Cancelada', 'SOLICITUD CANCELACIÓN [30/06/2026 12:17]: \nMotivo: Transporte\nDetalle: no hay gasolina\n', 'aa', 1, '2026-06-30 16:12:00', '2026-06-30 16:17:15');

-- --------------------------------------------------------

--
-- Table structure for table `ciudades`
--

CREATE TABLE `ciudades` (
  `id_ciudad` bigint UNSIGNED NOT NULL,
  `id_estado` bigint UNSIGNED NOT NULL,
  `ciudad` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `capital` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ciudades`
--

INSERT INTO `ciudades` (`id_ciudad`, `id_estado`, `ciudad`, `capital`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Caracas', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 2, 'Puerto Ayacucho', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 3, 'Barcelona', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 3, 'Puerto La Cruz', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 3, 'El Tigre', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 4, 'San Fernando de Apure', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 5, 'Maracay', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 5, 'Turmero', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 5, 'La Victoria', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 6, 'Barinas', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 7, 'Ciudad Bolívar', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(12, 7, 'Puerto Ordaz', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(13, 7, 'San Félix', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(14, 8, 'Valencia', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(15, 8, 'Puerto Cabello', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(16, 8, 'Guacara', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(17, 9, 'San Carlos', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(18, 10, 'Tucupita', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(19, 11, 'Coro', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(20, 11, 'Punto Fijo', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(21, 12, 'San Juan de los Morros', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(22, 12, 'Calabozo', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(23, 13, 'Barquisimeto', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(24, 13, 'Carora', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(25, 14, 'Mérida', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(26, 14, 'El Vigía', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(27, 15, 'Los Teques', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(28, 15, 'Guarenas', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(29, 15, 'Guatire', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(30, 16, 'Maturín', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(31, 17, 'Porlamar', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(32, 17, 'La Asunción', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(33, 18, 'Guanare', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(34, 18, 'Acarigua', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(35, 19, 'Cumaná', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(36, 19, 'Carúpano', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(37, 20, 'San Cristóbal', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(38, 20, 'San Antonio del Táchira', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(39, 21, 'Trujillo', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(40, 21, 'Valera', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(41, 22, 'La Guaira', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(42, 22, 'Catia La Mar', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(43, 23, 'San Felipe', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(44, 24, 'Maracaibo', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(45, 24, 'Cabimas', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(46, 24, 'Ciudad Ojeda', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` bigint UNSIGNED NOT NULL,
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `configuracion_global`
--

CREATE TABLE `configuracion_global` (
  `id` bigint UNSIGNED NOT NULL,
  `clave` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `valor` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `tipo` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'string',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configuracion_global`
--

INSERT INTO `configuracion_global` (`id`, `clave`, `valor`, `descripcion`, `tipo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'reparto_medico_default', '70', 'Porcentaje por defecto para el médico en el reparto de facturas', 'number', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 'reparto_consultorio_default', '20', 'Porcentaje por defecto para el consultorio en el reparto de facturas', 'number', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 'reparto_sistema_default', '10', 'Porcentaje por defecto para el sistema en el reparto de facturas', 'number', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 'liquidacion_tipo_periodo_default', 'Quincenal', 'Tipo de período por defecto para liquidaciones', 'string', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 'liquidacion_generar_automatico', 'false', 'Generar liquidaciones automáticamente al finalizar el período', 'boolean', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 'factura_dias_vencimiento_default', '7', 'Días por defecto para vencimiento de facturas', 'number', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 'factura_impuesto_iva', '0', 'Porcentaje de IVA aplicado a las facturas', 'number', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 'pago_metodos_habilitados', '[\"Transferencia\",\"Zelle\",\"Efectivo\",\"Pago Movil\"]', 'Métodos de pago habilitados en el sistema', 'json', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `configuracion_reparto`
--

CREATE TABLE `configuracion_reparto` (
  `id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED DEFAULT NULL,
  `porcentaje_medico` decimal(5,2) NOT NULL,
  `porcentaje_consultorio` decimal(5,2) NOT NULL,
  `porcentaje_sistema` decimal(5,2) NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `configuracion_reparto`
--

INSERT INTO `configuracion_reparto` (`id`, `medico_id`, `consultorio_id`, `porcentaje_medico`, `porcentaje_consultorio`, `porcentaje_sistema`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 70.00, 20.00, 10.00, 'Configuración estándar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 6, 1, 70.00, 20.00, 10.00, 'Configuración estándar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 7, 1, 70.00, 20.00, 10.00, 'Configuración estándar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `consultorios`
--

CREATE TABLE `consultorios` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `estado_id` bigint UNSIGNED NOT NULL,
  `ciudad_id` bigint UNSIGNED NOT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `telefono` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `horario_inicio` time DEFAULT NULL,
  `horario_fin` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `consultorios`
--

INSERT INTO `consultorios` (`id`, `nombre`, `descripcion`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `telefono`, `email`, `horario_inicio`, `horario_fin`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Consultorio Central Caracas', 'Consultorio principal en zona céntrica', 1, 1, 1, 1, 'Av. Principal de El Rosal, Edificio Médico, Piso 3', '(0212) 555-1234', 'info@consultoriocentral.com', '08:00:00', '18:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 'Clínica Los Teques', 'Atención especializada en Miranda', 2, 2, 2, 3, 'Centro Comercial Los Altos, Local 15', '(0212) 555-5678', 'clinicateques@email.com', '07:30:00', '17:30:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 'Centro Médico Maracay', 'Especialistas en Aragua', 3, 3, 4, 4, 'Av. Las Delicias, Torre Empresarial', '(0243) 555-9012', 'maracay@centromedico.com', '08:00:00', '19:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 'Unidad Salud Valencia', 'Tecnología de punta en Carabobo', 4, 4, 5, 5, 'Urb. El Viñedo, Calle 139', '(0241) 555-3456', 'valencia@salud.com', '07:00:00', '20:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 'Consultorios del Este', 'Atención VIP en Caracas', 1, 1, 2, 2, 'La Castellana, Edif. Premium', '(0212) 555-7890', 'este@consultorios.com', '09:00:00', '18:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 'Centro Pediátrico Infantil', 'Especializado en niños', 1, 1, 1, 1, 'San Bernardino, Av. Panteón', '(0212) 555-1122', 'pediatria@centro.com', '08:00:00', '16:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(7, 'Unidad de Cardiología Integral', 'Salud cardiovascular', 2, 2, 2, 3, 'San Antonio de los Altos, Pueblo', '(0212) 555-3344', 'cardio@unidad.com', '07:30:00', '17:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(8, 'Centro de Diagnóstico Rápido', 'Laboratorio y consultas express', 1, 1, 3, 6, 'Chacao, Centro San Ignacio', '(0212) 555-5566', 'diagnostico@rapido.com', '07:00:00', '21:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `datos_pago_medico`
--

CREATE TABLE `datos_pago_medico` (
  `id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `banco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_cuenta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titular` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cedula` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metodo_pago_id` bigint UNSIGNED DEFAULT NULL,
  `prefijo_tlf` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_tlf` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Estado activo/inactivo del método de pago',
  `metodo_preferido` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `datos_pago_medico`
--

INSERT INTO `datos_pago_medico` (`id`, `medico_id`, `banco`, `tipo_cuenta`, `numero_cuenta`, `titular`, `cedula`, `metodo_pago_id`, `prefijo_tlf`, `numero_tlf`, `status`, `metodo_preferido`, `created_at`, `updated_at`) VALUES
(1, 5, 'Banco Nacional de Crédito', 'Ahorro', '0134-0001-234567890', 'Carlos Vargas', '18000005', 1, '+58', '4141234567', 1, NULL, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 6, 'Banco Nacional de Crédito', 'Ahorro', '0134-0001-234567890', 'Elena Gómez', '19000006', 1, '+58', '4141234567', 1, NULL, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 7, 'Banco Nacional de Crédito', 'Ahorro', '0134-0001-234567890', 'Roberto Díaz', '20000007', 1, '+58', '4141234567', 1, NULL, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `especialidades`
--

CREATE TABLE `especialidades` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `duracion_cita_default` int NOT NULL DEFAULT '30',
  `color` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'medical',
  `icono` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'heart-pulse',
  `prioridad` int NOT NULL DEFAULT '2',
  `requisitos` text COLLATE utf8mb4_unicode_ci,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `especialidades`
--

INSERT INTO `especialidades` (`id`, `nombre`, `codigo`, `descripcion`, `duracion_cita_default`, `color`, `icono`, `prioridad`, `requisitos`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Cardiología', NULL, 'Especialidad en enfermedades del corazón', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 'Pediatría', NULL, 'Medicina para niños y adolescentes', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 'Dermatología', NULL, 'Especialidad en enfermedades de la piel', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 'Ginecología', NULL, 'Salud femenina y sistema reproductivo', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 'Traumatología', NULL, 'Especialidad en huesos y músculos', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 'Oftalmología', NULL, 'Especialidad en ojos y visión', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 'Medicina General', NULL, 'Atención primaria y general', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 'Neurología', NULL, 'Especialidad en el sistema nervioso', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 'Psiquiatría', NULL, 'Salud mental y trastornos', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 'Gastroenterología', NULL, 'Sistema digestivo', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 'Urología', NULL, 'Sistema urinario y aparato reproductor masculino', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(12, 'Otorrinolaringología', NULL, 'Oído, nariz y garganta', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(13, 'Neumología', NULL, 'Enfermedades respiratorias y pulmones', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(14, 'Endocrinología', NULL, 'Sistema endocrino y hormonas', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(15, 'Reumatología', NULL, 'Enfermedades musculoesqueléticas y autoinmunes', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(16, 'Nefrología', NULL, 'Enfermedades de los riñones', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(17, 'Oncología', NULL, 'Diagnóstico y tratamiento del cáncer', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(18, 'Hematología', NULL, 'Enfermedades de la sangre', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(19, 'Medicina Interna', NULL, 'Atención integral del adulto', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(20, 'Anestesiología', NULL, 'Cuidado perioperatorio y manejo del dolor', 30, 'medical', 'heart-pulse', 2, NULL, NULL, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `especialidad_consultorio`
--

CREATE TABLE `especialidad_consultorio` (
  `id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `especialidad_consultorio`
--

INSERT INTO `especialidad_consultorio` (`id`, `especialidad_id`, `consultorio_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 9, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 14, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 3, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 5, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 16, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 12, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(7, 17, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(8, 5, 2, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(9, 11, 2, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(10, 1, 2, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(11, 20, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(12, 5, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(13, 12, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(14, 10, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(15, 13, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(16, 15, 4, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(17, 13, 4, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(18, 12, 4, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(19, 18, 4, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(20, 18, 5, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(21, 4, 5, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(22, 2, 5, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(23, 16, 5, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(24, 5, 5, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(25, 6, 6, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(26, 12, 6, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(27, 9, 6, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(28, 13, 6, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(29, 13, 7, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(30, 11, 7, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(31, 1, 7, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(32, 4, 7, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(33, 16, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(34, 18, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(35, 2, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(36, 14, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(37, 10, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(38, 17, 8, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(39, 1, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(40, 2, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(41, 7, 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `estados`
--

CREATE TABLE `estados` (
  `id_estado` bigint UNSIGNED NOT NULL,
  `estado` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `iso_3166_2` varchar(4) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `estados`
--

INSERT INTO `estados` (`id_estado`, `estado`, `iso_3166_2`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Distrito Capital', 'CCS', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 'Amazonas', 'AMA', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 'Anzoátegui', 'ANZ', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 'Apure', 'APU', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 'Aragua', 'ARA', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 'Barinas', 'BAR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 'Bolívar', 'BOL', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 'Carabobo', 'CAR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 'Cojedes', 'COJ', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 'Delta Amacuro', 'DEL', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 'Falcón', 'FAL', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(12, 'Guárico', 'GUA', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(13, 'Lara', 'LAR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(14, 'Mérida', 'MER', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(15, 'Miranda', 'MIR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(16, 'Monagas', 'MON', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(17, 'Nueva Esparta', 'NUE', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(18, 'Portuguesa', 'POR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(19, 'Sucre', 'SUC', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(20, 'Táchira', 'TAC', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(21, 'Trujillo', 'TRU', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(22, 'Vargas', 'VAR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(23, 'Yaracuy', 'YAR', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(24, 'Zulia', 'ZUL', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `evolucion_clinica`
--

CREATE TABLE `evolucion_clinica` (
  `id` bigint UNSIGNED NOT NULL,
  `cita_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `peso_kg` decimal(5,2) DEFAULT NULL,
  `talla_cm` decimal(5,2) DEFAULT NULL,
  `imc` decimal(5,2) DEFAULT NULL,
  `tension_sistolica` int DEFAULT NULL,
  `tension_diastolica` int DEFAULT NULL,
  `frecuencia_cardiaca` int DEFAULT NULL,
  `temperatura_c` decimal(4,2) DEFAULT NULL,
  `frecuencia_respiratoria` int DEFAULT NULL,
  `saturacion_oxigeno` decimal(5,2) DEFAULT NULL,
  `motivo_consulta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `enfermedad_actual` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `examen_fisico` text COLLATE utf8mb4_unicode_ci,
  `diagnostico` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tratamiento` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `recomendaciones` text COLLATE utf8mb4_unicode_ci,
  `notas_adicionales` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `evolucion_clinica`
--

INSERT INTO `evolucion_clinica` (`id`, `cita_id`, `paciente_id`, `medico_id`, `peso_kg`, `talla_cm`, `imc`, `tension_sistolica`, `tension_diastolica`, `frecuencia_cardiaca`, `temperatura_c`, `frecuencia_respiratoria`, `saturacion_oxigeno`, `motivo_consulta`, `enfermedad_actual`, `examen_fisico`, `diagnostico`, `tratamiento`, `recomendaciones`, `notas_adicionales`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 7, 65.50, 165.00, 24.10, 120, 80, 72, 36.50, 16, 98.00, 'Control pediátrico anual', 'Paciente asintomática, realiza control de rutina', 'Paciente en buen estado general, mucosas húmedas, buena hidratación', 'Control de niño sano', 'Continuar con hábitos saludables, control en 6 meses', 'Mantener dieta balanceada y ejercicio regular', 'Paciente muy colaboradora', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 151, 4, 8, 65.00, 172.00, 21.97, 120, 80, 70, 35.00, 20, 98.00, 'ninguno', 'nada', NULL, 'talento', 'vacaciones', 'como timon y pumpa', NULL, 1, '2026-06-30 16:02:36', '2026-06-30 16:02:36');

-- --------------------------------------------------------

--
-- Table structure for table `facturas_pacientes`
--

CREATE TABLE `facturas_pacientes` (
  `id` bigint UNSIGNED NOT NULL,
  `cita_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `monto_usd` decimal(10,2) NOT NULL,
  `tasa_id` bigint UNSIGNED NOT NULL,
  `monto_bs` decimal(20,2) NOT NULL,
  `fecha_emision` date NOT NULL,
  `fecha_vencimiento` date DEFAULT NULL,
  `numero_factura` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_factura` enum('Emitida','Pagada','Anulada','Vencida') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Emitida',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `facturas_pacientes`
--

INSERT INTO `facturas_pacientes` (`id`, `cita_id`, `paciente_id`, `medico_id`, `monto_usd`, `tasa_id`, `monto_bs`, `fecha_emision`, `fecha_vencimiento`, `numero_factura`, `status_factura`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 7, 40.00, 1, 1420.00, '2026-06-30', '2026-07-15', 'FAC-20260630-001', 'Emitida', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 151, 4, 8, 50.00, 61, 2174.00, '2026-06-30', NULL, 'FACT-2026-000151', 'Pagada', 1, '2026-06-30 15:50:41', '2026-06-30 15:54:12'),
(3, 152, 4, 8, 50.00, 61, 2174.00, '2026-06-30', NULL, 'FACT-2026-000152', 'Emitida', 1, '2026-06-30 16:12:29', '2026-06-30 16:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `factura_cabecera`
--

CREATE TABLE `factura_cabecera` (
  `id` bigint UNSIGNED NOT NULL,
  `cita_id` bigint UNSIGNED NOT NULL,
  `nro_control` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `tasa_id` bigint UNSIGNED NOT NULL,
  `fecha_emision` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factura_cabecera`
--

INSERT INTO `factura_cabecera` (`id`, `cita_id`, `nro_control`, `paciente_id`, `medico_id`, `tasa_id`, `fecha_emision`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'CTL-20260630-001', 2, 7, 1, '2026-06-30 15:23:44', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 151, 'FACT-2026-000002', 4, 8, 61, '2026-06-30 17:02:44', 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `factura_detalles`
--

CREATE TABLE `factura_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `cabecera_id` bigint UNSIGNED NOT NULL,
  `entidad_tipo` enum('Paciente','Medico','Consultorio','Sistema') COLLATE utf8mb4_unicode_ci NOT NULL,
  `entidad_id` bigint UNSIGNED DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `precio_unitario_usd` decimal(15,2) NOT NULL,
  `subtotal_usd` decimal(15,2) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factura_detalles`
--

INSERT INTO `factura_detalles` (`id`, `cabecera_id`, `entidad_tipo`, `entidad_id`, `descripcion`, `cantidad`, `precio_unitario_usd`, `subtotal_usd`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paciente', 2, 'Consulta de Pediatría - Dra. González', 1, 40.00, 40.00, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 1, 'Medico', 2, 'Honorarios Médicos (70%)', 1, 28.00, 28.00, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 1, 'Consultorio', 1, 'Alquiler Consultorio (20%)', 1, 8.00, 8.00, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 1, 'Sistema', NULL, 'Comisión Sistema (10%)', 1, 4.00, 4.00, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 2, 'Medico', 8, 'Honorarios médicos (70%)', 1, 35.00, 35.00, 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44'),
(6, 2, 'Consultorio', 5, 'Uso de consultorio (20%)', 1, 10.00, 10.00, 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44'),
(7, 2, 'Sistema', NULL, 'Comisión del sistema (10%)', 1, 5.00, 5.00, 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `factura_totales`
--

CREATE TABLE `factura_totales` (
  `id` bigint UNSIGNED NOT NULL,
  `cabecera_id` bigint UNSIGNED NOT NULL,
  `entidad_tipo` enum('Paciente','Medico','Consultorio','Sistema') COLLATE utf8mb4_unicode_ci NOT NULL,
  `entidad_id` bigint UNSIGNED DEFAULT NULL,
  `base_imponible_usd` decimal(15,2) NOT NULL,
  `impuestos_usd` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_final_usd` decimal(15,2) NOT NULL,
  `total_final_bs` decimal(20,2) NOT NULL,
  `estado_liquidacion` enum('Pendiente','Liquidado','Retenido','No Aplica') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `factura_totales`
--

INSERT INTO `factura_totales` (`id`, `cabecera_id`, `entidad_tipo`, `entidad_id`, `base_imponible_usd`, `impuestos_usd`, `total_final_usd`, `total_final_bs`, `estado_liquidacion`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Paciente', 2, 40.00, 0.00, 40.00, 1420.00, 'Liquidado', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 1, 'Medico', 2, 28.00, 0.00, 28.00, 994.00, 'Pendiente', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 1, 'Consultorio', 1, 8.00, 0.00, 8.00, 284.00, 'Pendiente', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 1, 'Sistema', NULL, 4.00, 0.00, 4.00, 142.00, 'No Aplica', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 2, 'Medico', 8, 35.00, 0.00, 35.00, 1521.80, 'Pendiente', 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44'),
(6, 2, 'Consultorio', 5, 10.00, 0.00, 10.00, 434.80, 'Pendiente', 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44'),
(7, 2, 'Sistema', NULL, 5.00, 0.00, 5.00, 217.40, 'Pendiente', 1, '2026-06-30 17:02:44', '2026-06-30 17:02:44');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `fecha_indisponible`
--

CREATE TABLE `fecha_indisponible` (
  `id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED DEFAULT NULL,
  `fecha` date NOT NULL,
  `motivo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `todo_el_dia` tinyint(1) NOT NULL DEFAULT '1',
  `hora_inicio` time DEFAULT NULL,
  `hora_fin` time DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fecha_indisponible`
--

INSERT INTO `fecha_indisponible` (`id`, `medico_id`, `consultorio_id`, `fecha`, `motivo`, `todo_el_dia`, `hora_inicio`, `hora_fin`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, NULL, '2026-07-10', 'Indisponibilidad de prueba', 1, NULL, NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `historial_password`
--

CREATE TABLE `historial_password` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `password_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `historial_password`
--

INSERT INTO `historial_password` (`id`, `user_id`, `password_hash`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '0c909a141f1f2c0a1cb602b0b2d7d050', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 'd5cf712d56db2f95e8d618735008f02c', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 3, 'd5cf712d56db2f95e8d618735008f02c', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 4, '9916abe2b811b77d02481f46ea66b37e', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 5, '9916abe2b811b77d02481f46ea66b37e', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 12, '31d91532a0aed3f595649f850f3a58ed', 1, '2026-06-30 15:48:12', '2026-06-30 15:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `historia_clinica_base`
--

CREATE TABLE `historia_clinica_base` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `tipo_sangre` enum('A+','A-','B+','B-','AB+','AB-','O+','O-','No Especificado') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `alergias` text COLLATE utf8mb4_unicode_ci,
  `alergias_medicamentos` text COLLATE utf8mb4_unicode_ci,
  `antecedentes_familiares` text COLLATE utf8mb4_unicode_ci,
  `antecedentes_personales` text COLLATE utf8mb4_unicode_ci,
  `enfermedades_cronicas` text COLLATE utf8mb4_unicode_ci,
  `medicamentos_actuales` text COLLATE utf8mb4_unicode_ci,
  `cirugias_previas` text COLLATE utf8mb4_unicode_ci,
  `habitos` text COLLATE utf8mb4_unicode_ci,
  `habito_tabaco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `habito_alcohol` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actividad_fisica` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dieta` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `historia_clinica_base`
--

INSERT INTO `historia_clinica_base` (`id`, `paciente_id`, `tipo_sangre`, `alergias`, `alergias_medicamentos`, `antecedentes_familiares`, `antecedentes_personales`, `enfermedades_cronicas`, `medicamentos_actuales`, `cirugias_previas`, `habitos`, `habito_tabaco`, `habito_alcohol`, `actividad_fisica`, `dieta`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'A+', 'Ninguna conocida', 'Ninguna conocida', 'Ninguno relevante', 'Temporibus incidunt quam quia.', 'Ninguna', 'Ninguno', 'Ninguna', 'Sedentario', NULL, NULL, NULL, NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 'B-', 'Ninguna conocida', 'Ninguna conocida', 'Ninguno relevante', 'Non totam vero consequatur quia.', 'Diabetes Tipo 2', 'Metformina', 'Ninguna', 'Bebedor social', NULL, NULL, NULL, NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 3, 'A-', 'Ninguna conocida', 'Ninguna conocida', 'Cáncer', 'Explicabo aperiam et vero inventore quos non.', 'Ninguna', 'Ninguno', 'Ninguna', 'Bebedor social', NULL, NULL, NULL, NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 4, 'O+', NULL, NULL, 'Cardiaco', 'Ninguna', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2026-06-30 16:00:35', '2026-06-30 16:00:35');

-- --------------------------------------------------------

--
-- Table structure for table `known_devices`
--

CREATE TABLE `known_devices` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `last_login_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `known_devices`
--

INSERT INTO `known_devices` (`id`, `user_id`, `ip_address`, `user_agent`, `last_login_at`, `created_at`, `updated_at`) VALUES
(1, 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-07-01 18:57:53', '2026-06-30 15:33:45', '2026-07-01 18:57:53'),
(2, 11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-07-01 18:57:29', '2026-06-30 15:43:44', '2026-07-01 18:57:29'),
(3, 11, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36', '2026-06-30 15:58:44', '2026-06-30 15:58:44', '2026-06-30 15:58:44'),
(4, 12, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/149.0.0.0 Safari/537.36 Edg/149.0.0.0', '2026-07-01 18:57:07', '2026-06-30 15:59:23', '2026-07-01 18:57:07');

-- --------------------------------------------------------

--
-- Table structure for table `liquidaciones`
--

CREATE TABLE `liquidaciones` (
  `id` bigint UNSIGNED NOT NULL,
  `entidad_tipo` enum('Medico','Consultorio') COLLATE utf8mb4_unicode_ci NOT NULL,
  `entidad_id` bigint UNSIGNED NOT NULL,
  `periodo_tipo` enum('quincenal','mensual','manual') COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tipo de período de liquidación',
  `fecha_inicio_periodo` date DEFAULT NULL COMMENT 'Fecha de inicio del período liquidado',
  `fecha_fin_periodo` date DEFAULT NULL COMMENT 'Fecha de fin del período liquidado',
  `monto_total_usd` decimal(15,2) NOT NULL,
  `monto_total_bs` decimal(20,2) NOT NULL,
  `metodo_pago` enum('Transferencia','Zelle','Efectivo','Pago Movil','Otro') COLLATE utf8mb4_unicode_ci NOT NULL,
  `referencia` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fecha_pago` date NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquidaciones`
--

INSERT INTO `liquidaciones` (`id`, `entidad_tipo`, `entidad_id`, `periodo_tipo`, `fecha_inicio_periodo`, `fecha_fin_periodo`, `monto_total_usd`, `monto_total_bs`, `metodo_pago`, `referencia`, `fecha_pago`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Medico', 2, NULL, NULL, NULL, 28.00, 994.00, 'Transferencia', 'TXN-20260630-001', '2026-07-30', 'Liquidación mensual de honorarios - Dra. González', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 'Consultorio', 1, NULL, NULL, NULL, 8.00, 284.00, 'Transferencia', 'TXN-20260630-002', '2026-07-30', 'Pago por alquiler de consultorio', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `liquidacion_detalles`
--

CREATE TABLE `liquidacion_detalles` (
  `id` bigint UNSIGNED NOT NULL,
  `liquidacion_id` bigint UNSIGNED NOT NULL,
  `factura_total_id` bigint UNSIGNED NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `liquidacion_detalles`
--

INSERT INTO `liquidacion_detalles` (`id`, `liquidacion_id`, `factura_total_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 3, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `medicos`
--

CREATE TABLE `medicos` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `primer_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_documento` enum('V','E','P','J') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `estado_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `prefijo_tlf` enum('+58','+57','+1','+34') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_tlf` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nro_colegiatura` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formacion_academica` text COLLATE utf8mb4_unicode_ci,
  `experiencia_profesional` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_color` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tema_dinamico` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medicos`
--

INSERT INTO `medicos` (`id`, `user_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `fecha_nac`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `prefijo_tlf`, `numero_tlf`, `genero`, `nro_colegiatura`, `formacion_academica`, `experiencia_profesional`, `status`, `foto_perfil`, `banner_perfil`, `banner_color`, `tema_dinamico`, `created_at`, `updated_at`) VALUES
(5, 5, 'Carlos', NULL, 'Vargas', NULL, 'V', '18000005', '1980-08-20', 1, 1, 1, 1, 'Consultorio Central', '+58', '4140000005', 'Masculino', 'MP-12345', 'Médico Cirujano - UCV\\nEspecialista en Cardiología', '15 años de experiencia en cardiología clínica.', 1, NULL, NULL, NULL, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 6, 'Elena', NULL, 'Gómez', NULL, 'V', '19000006', '1985-04-12', 1, 1, 1, 1, 'Urb. El Parque', '+58', '4240000006', 'Femenino', 'MP-67890', 'Médico Cirujano - UCLA\\nPediatría y Puericultura', '10 años cuidando la salud de los niños.', 1, NULL, NULL, NULL, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(7, 7, 'Roberto', NULL, 'Díaz', NULL, 'V', '20000007', '1978-11-05', 1, 1, 1, 1, 'Av. Los Próceres', '+58', '4120000007', 'Masculino', 'MP-55555', 'Médico Cirujano - ULA\\nTraumatología y Ortopedia', 'Especialista en lesiones deportivas.', 1, NULL, NULL, NULL, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(8, 11, 'JorgeMedico', 'Luis', 'Mdeoca', 'gzla', 'V', '30004558', '1998-07-15', 1, 1, 1, 2, 'Times Square Manhattan, NY 10036 united states', '+58', '4245845707', 'Masculino', '1234569', 'universidad y cursos de IA', 'soy medico', 1, NULL, NULL, NULL, 0, '2026-06-30 15:43:18', '2026-06-30 15:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `medico_consultorio`
--

CREATE TABLE `medico_consultorio` (
  `id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `consultorio_id` bigint UNSIGNED NOT NULL,
  `dia_semana` enum('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `turno` enum('mañana','tarde','noche','completo') COLLATE utf8mb4_unicode_ci NOT NULL,
  `horario_inicio` time NOT NULL,
  `horario_fin` time NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `especialidad_id` bigint UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medico_consultorio`
--

INSERT INTO `medico_consultorio` (`id`, `medico_id`, `consultorio_id`, `dia_semana`, `turno`, `horario_inicio`, `horario_fin`, `status`, `created_at`, `updated_at`, `especialidad_id`) VALUES
(1, 5, 8, 'Viernes', 'mañana', '08:00:00', '12:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(2, 5, 5, 'Martes', 'tarde', '13:00:00', '17:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(3, 6, 7, 'Miércoles', 'mañana', '08:00:00', '12:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(4, 6, 4, 'Martes', 'mañana', '08:00:00', '12:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(5, 6, 4, 'Lunes', 'tarde', '13:00:00', '17:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(6, 7, 7, 'Lunes', 'tarde', '13:00:00', '17:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(7, 7, 8, 'Miércoles', 'completo', '08:00:00', '16:00:00', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44', NULL),
(8, 8, 5, 'Lunes', 'mañana', '09:00:00', '12:00:00', 1, '2026-06-30 15:44:52', '2026-06-30 15:44:52', 2),
(9, 8, 5, 'Lunes', 'tarde', '14:00:00', '18:00:00', 1, '2026-06-30 15:44:52', '2026-06-30 15:44:52', 2);

-- --------------------------------------------------------

--
-- Table structure for table `medico_especialidad`
--

CREATE TABLE `medico_especialidad` (
  `id` bigint UNSIGNED NOT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED NOT NULL,
  `tarifa` decimal(10,2) NOT NULL DEFAULT '0.00',
  `atiende_domicilio` tinyint(1) NOT NULL DEFAULT '0',
  `tarifa_extra_domicilio` decimal(10,2) NOT NULL DEFAULT '0.00',
  `anos_experiencia` int DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `medico_especialidad`
--

INSERT INTO `medico_especialidad` (`id`, `medico_id`, `especialidad_id`, `tarifa`, `atiende_domicilio`, `tarifa_extra_domicilio`, `anos_experiencia`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 1, 50.00, 0, 0.00, 15, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 5, 7, 30.00, 0, 0.00, 18, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 6, 2, 45.00, 0, 0.00, 10, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 7, 5, 60.00, 0, 0.00, 12, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 8, 2, 50.00, 0, 0.00, 5, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `metodo_pago`
--

CREATE TABLE `metodo_pago` (
  `id_metodo` bigint UNSIGNED NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `codigo` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requiere_confirmacion` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `metodo_pago`
--

INSERT INTO `metodo_pago` (`id_metodo`, `nombre`, `descripcion`, `codigo`, `requiere_confirmacion`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Transferencia Bancaria', 'Transferencia Bancaria', 'TRANSF', 1, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 'Zelle', 'Zelle', 'ZELLE', 1, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 'Efectivo', 'Efectivo', 'EFECT', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 'Pago Móvil', 'Pago Móvil', 'PAGOMOVIL', 1, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 'Tarjeta de Crédito', 'Tarjeta de Crédito', 'TARJETA', 0, 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2026_01_06_171652_create_roles_table', 1),
(6, '2026_01_06_171655_create_estados_table', 1),
(7, '2026_01_06_171656_create_ciudades_table', 1),
(8, '2026_01_06_171657_create_municipios_table', 1),
(9, '2026_01_06_171657_create_parroquias_table', 1),
(10, '2026_01_06_171658_create_preguntas_catalogo_table', 1),
(11, '2026_01_06_171658_create_usuarios_table', 1),
(12, '2026_01_06_171659_create_respuestas_seguridad_table', 1),
(13, '2026_01_06_171700_create_administradores_table', 1),
(14, '2026_01_06_171700_create_historial_password_table', 1),
(15, '2026_01_06_171701_create_medicos_table', 1),
(16, '2026_01_06_171702_create_pacientes_table', 1),
(17, '2026_01_06_171702_create_representantes_table', 1),
(18, '2026_01_06_171703_create_pacientes_especiales_table', 1),
(19, '2026_01_06_171703_create_representante_paciente_especial_table', 1),
(20, '2026_01_06_171704_create_consultorios_table', 1),
(21, '2026_01_06_171704_create_especialidades_table', 1),
(22, '2026_01_06_171705_create_especialidad_consultorio_table', 1),
(23, '2026_01_06_171705_create_medico_especialidad_table', 1),
(24, '2026_01_06_171706_create_medico_consultorio_table', 1),
(25, '2026_01_06_171707_create_citas_table', 1),
(26, '2026_01_06_171707_create_historia_clinica_base_table', 1),
(27, '2026_01_06_171708_create_evolucion_clinica_table', 1),
(28, '2026_01_06_171709_create_ordenes_medicas_table', 1),
(29, '2026_01_06_171710_create_tasas_dolar_table', 1),
(30, '2026_01_06_171711_create_metodo_pago_table', 1),
(31, '2026_01_06_171712_create_facturas_pacientes_table', 1),
(32, '2026_01_06_171712_create_pago_table', 1),
(33, '2026_01_06_171714_create_factura_cabecera_table', 1),
(34, '2026_01_06_171714_create_factura_detalles_table', 1),
(35, '2026_01_06_171715_create_factura_totales_table', 1),
(36, '2026_01_06_171716_create_configuracion_reparto_table', 1),
(37, '2026_01_06_171717_create_liquidaciones_table', 1),
(38, '2026_01_06_171718_create_liquidacion_detalles_table', 1),
(39, '2026_01_06_171718_create_notificaciones_table', 1),
(40, '2026_01_06_171719_create_fecha_indisponible_table', 1),
(41, '2026_01_06_171719_create_solicitudes_historial_table', 1),
(42, '2026_01_09_200000_add_domicilio_fields_to_medico_especialidad', 1),
(43, '2026_01_09_210000_create_configuraciones_table', 1),
(44, '2026_01_09_220000_update_pacientes_especiales_and_citas', 1),
(45, '2026_01_10_175805_add_especialidad_to_medico_consultorio', 1),
(46, '2026_01_10_200000_add_fields_to_especialidades_table', 1),
(47, '2026_01_12_191916_add_direccion_domicilio_to_citas_table', 1),
(48, '2026_01_13_131000_add_nombre_to_metodo_pago_table', 1),
(49, '2026_01_13_153500_add_comprobante_to_pago_table', 1),
(50, '2026_01_14_020001_add_foto_perfil_to_pacientes_table', 1),
(51, '2026_01_14_022501_add_banner_perfil_to_pacientes_table', 1),
(52, '2026_01_14_023001_add_banner_color_to_pacientes_table', 1),
(53, '2026_01_14_024001_add_tema_dinamico_to_pacientes_table', 1),
(54, '2026_01_14_030001_add_customization_to_administradores_table', 1),
(55, '2026_01_14_040001_add_customization_to_medicos_table', 1),
(56, '2026_01_14_160000_create_administrador_consultorio_table', 1),
(57, '2026_01_14_191800_create_auditorias_historia_base_table', 1),
(58, '2026_01_14_195300_add_no_especificado_to_tipo_sangre', 1),
(59, '2026_01_14_210000_add_habitos_fields_to_historia_clinica_base', 1),
(60, '2026_01_14_210500_add_evolucion_id_to_solicitudes_historial', 1),
(61, '2026_01_14_222000_create_notifications_table', 1),
(62, '2026_01_16_010000_add_fields_to_ordenes_medicas_table', 1),
(63, '2026_01_16_010100_create_orden_medicamentos_table', 1),
(64, '2026_01_16_010200_create_orden_examenes_table', 1),
(65, '2026_01_16_010300_create_orden_imagenes_table', 1),
(66, '2026_01_16_010400_create_orden_referencias_table', 1),
(67, '2026_01_16_010500_create_solicitudes_orden_table', 1),
(68, '2026_01_16_111600_add_mixta_and_representante_to_ordenes_medicas', 1),
(69, '2026_01_16_114100_fix_orden_examenes_tipo_examen_enum', 1),
(70, '2026_01_16_144500_modify_orden_imagenes_enum', 1),
(71, '2026_01_16_170000_add_es_especial_and_link_representantes', 1),
(72, '2026_01_17_162900_add_account_lockout_fields_to_usuarios_table', 1),
(73, '2026_01_18_194859_add_index_to_medico_consultorio', 1),
(74, '2026_02_04_202638_create_datos_pago_medico_table', 1),
(75, '2026_02_04_202640_create_configuracion_global_table', 1),
(76, '2026_02_04_221218_add_periodo_fields_to_liquidaciones_table', 1),
(77, '2026_02_04_224409_simplify_datos_pago_medico_table', 1),
(78, '2026_02_04_233000_add_status_to_datos_pago_medico_table', 1),
(79, '2026_02_04_234000_create_known_devices_table', 1),
(80, '2026_06_30_121103_make_cita_id_nullable_on_ordenes_medicas_table', 2),
(85, '2026_07_02_000001_create_audit_logs_table', 3),
(86, '2026_07_02_000002_create_auth_logs_table', 3),
(87, '2026_07_02_000003_create_read_audit_logs_table', 3),
(88, '2026_07_02_000004_add_failed_login_fields_to_usuarios', 3);

-- --------------------------------------------------------

--
-- Table structure for table `municipios`
--

CREATE TABLE `municipios` (
  `id_municipio` bigint UNSIGNED NOT NULL,
  `id_estado` bigint UNSIGNED NOT NULL,
  `municipio` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `municipios`
--

INSERT INTO `municipios` (`id_municipio`, `id_estado`, `municipio`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Libertador', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 2, 'Atures', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 2, 'Alto Orinoco', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 3, 'Bolívar', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 3, 'Guanta', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 3, 'Simón Rodríguez', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 4, 'San Fernando', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 4, 'Achaguas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 5, 'Girardot', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 5, 'Santiago Mariño', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 5, 'José Félix Ribas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(12, 6, 'Barinas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(13, 6, 'Pedraza', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(14, 7, 'Heres', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(15, 7, 'Caroní', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(16, 7, 'Piar', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(17, 8, 'Valencia', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(18, 8, 'Puerto Cabello', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(19, 8, 'Guacara', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(20, 8, 'Naguanagua', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(21, 9, 'Ezequiel Zamora', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(22, 9, 'Tinaquillo', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(23, 10, 'Tucupita', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(24, 10, 'Antonio Díaz', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(25, 11, 'Miranda', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(26, 11, 'Carirubana', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(27, 11, 'Falcón', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(28, 12, 'Juan Germán Roscio', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(29, 12, 'Francisco de Miranda', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(30, 13, 'Iribarren', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(31, 13, 'Torres', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(32, 13, 'Palavecino', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(33, 14, 'Libertador', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(34, 14, 'Alberto Adriani', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(35, 14, 'Santos Marquina', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(36, 15, 'Guaicaipuro', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(37, 15, 'Plaza', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(38, 15, 'Zamora', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(39, 15, 'Sucre', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(40, 16, 'Maturín', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(41, 16, 'Piar', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(42, 17, 'Mariño', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(43, 17, 'Arismendi', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(44, 18, 'Guanare', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(45, 18, 'Páez', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(46, 18, 'Araure', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(47, 19, 'Sucre', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(48, 19, 'Bermúdez', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(49, 19, 'Ribero', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(50, 20, 'San Cristóbal', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(51, 20, 'Cárdenas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(52, 20, 'Torbes', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(53, 21, 'Trujillo', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(54, 21, 'Valera', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(55, 21, 'Boconó', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(56, 22, 'Vargas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(57, 23, 'San Felipe', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(58, 23, 'Nirgua', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(59, 24, 'Maracaibo', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(60, 24, 'Cabimas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(61, 24, 'Lagunillas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(62, 24, 'Mara', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `notificaciones`
--

CREATE TABLE `notificaciones` (
  `id` bigint UNSIGNED NOT NULL,
  `receptor_id` bigint UNSIGNED NOT NULL,
  `receptor_rol` enum('Paciente','Medico','Admin','Root') COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('Recordatorio_Cita','Pago_Aprobado','Pago_Rechazado','Cancelacion','Alerta_Adm','Sistema') COLLATE utf8mb4_unicode_ci NOT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mensaje` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `via` enum('Correo','Sistema','WhatsApp','SMS','Multiple') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_envio` enum('Pendiente','Enviado','Fallido','Leido') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `error_detalle` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notificaciones`
--

INSERT INTO `notificaciones` (`id`, `receptor_id`, `receptor_rol`, `tipo`, `titulo`, `mensaje`, `via`, `estado_envio`, `error_detalle`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Medico', 'Recordatorio_Cita', 'Recordatorio de Cita Programada', 'Tiene una cita programada para mañana a las 15:00 con el paciente Juan Martínez', 'Sistema', 'Enviado', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 4, 'Paciente', 'Recordatorio_Cita', 'Recordatorio de Su Cita', 'Su cita con el Dr. Pérez está programada para el 05/07/2026 a las 09:00', 'Sistema', 'Pendiente', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 5, 'Paciente', 'Pago_Aprobado', 'Pago Confirmado', 'Su pago por la consulta ha sido confirmado. Gracias por su preferencia.', 'Correo', 'Enviado', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 1, 'Admin', 'Alerta_Adm', 'Nueva Factura Emitida', 'Se ha emitido una nueva factura para la paciente Laura Hernández', 'Sistema', 'Leido', NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `notifiable_id` bigint UNSIGNED NOT NULL,
  `data` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('0de74871-9a7a-431d-a420-211bfe83041a', 'App\\Notifications\\Admin\\NuevoMedicoRegistrado', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Nuevo M\\u00e9dico Registrado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca fue registrado en el sistema\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\",\"medico_id\":8}', '2026-06-30 16:17:49', '2026-06-30 15:43:18', '2026-06-30 16:17:49'),
('20a42bf8-b46a-4f77-9746-ffa7248c9d4c', 'App\\Notifications\\Admin\\MedicoHorarioActualizado', 'App\\Models\\Administrador', 4, '{\"titulo\":\"Horario Actualizado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca actualiz\\u00f3 sus horarios de atenci\\u00f3n\",\"tipo\":\"info\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\\/horarios\",\"medico_id\":8}', NULL, '2026-06-30 15:44:52', '2026-06-30 15:44:52'),
('309f5e1a-73c4-4594-9446-fcba640229fe', 'App\\Notifications\\PagoRechazado', 'App\\Models\\Paciente', 4, '{\"titulo\":\"Pago Rechazado\",\"mensaje\":\"Tu pago de Bs. 2,174.00 fue rechazado. Motivo: por que si\",\"tipo\":\"danger\",\"pago_id\":null,\"cita_id\":152,\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/paciente\\/pagos\\/registrar\\/152\",\"monto\":\"2174.00\",\"referencia\":\"7687686\",\"motivo\":\"por que si\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-13\"}', NULL, '2026-06-30 16:14:47', '2026-06-30 16:14:47'),
('43c84d61-b52e-48ef-8485-d5a9b1f65357', 'App\\Notifications\\HistoriaClinicaActualizada', 'App\\Models\\Paciente', 4, '{\"titulo\":\"Historia Cl\\u00ednica Actualizada\",\"mensaje\":\"El Dr. Mdeoca agreg\\u00f3 una nueva evoluci\\u00f3n.\",\"tipo\":\"success\",\"evolucion_id\":2,\"cita_id\":\"151\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/historia-clinica\\/evoluciones\\/cita\\/151\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha\":\"30\\/06\\/2026\"}', NULL, '2026-06-30 16:02:36', '2026-06-30 16:02:36'),
('5bcb57d8-48b5-4ec6-897c-4059da7cb035', 'App\\Notifications\\Admin\\NuevoPagoRegistrado', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Nuevo Pago Registrado\",\"mensaje\":\"JorgePaciente montes de oca registr\\u00f3 un pago de Bs. 2,174.00\",\"tipo\":\"info\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/pagos\",\"pago_id\":null,\"cita_id\":151,\"monto\":\"2174\",\"referencia\":\"45678\",\"consultorio_nombre\":\"Consultorios del Este\",\"consultorio_id\":5,\"paciente_nombre\":\"JorgePaciente montes de oca\",\"paciente_documento\":\"V-30004549\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-06\",\"acciones\":[{\"texto\":\"Ver Comprobante\",\"icono\":\"file-earmark-text\",\"tipo\":\"secondary\",\"url\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/pagos\"},{\"texto\":\"Confirmar\",\"icono\":\"check-circle\",\"tipo\":\"success\",\"accion\":\"confirmar-pago\",\"data\":{\"pago_id\":null}}]}', '2026-06-30 16:17:49', '2026-06-30 15:50:41', '2026-06-30 16:17:49'),
('68014385-73ac-4b77-a064-a3108b1b649a', 'App\\Notifications\\PagoConfirmado', 'App\\Models\\Paciente', 4, '{\"titulo\":\"Pago Confirmado\",\"mensaje\":\"Tu pago de Bs. 2,174.00 fue confirmado exitosamente.\",\"tipo\":\"success\",\"pago_id\":null,\"cita_id\":151,\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/paciente\\/pagos\",\"monto\":\"2174.00\",\"referencia\":\"45678\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-06\"}', '2026-06-30 15:59:46', '2026-06-30 15:54:12', '2026-06-30 15:59:46'),
('8821e1ae-8789-456f-aa24-98d1c20ab217', 'App\\Notifications\\Admin\\NuevoMedicoRegistrado', 'App\\Models\\Administrador', 2, '{\"titulo\":\"Nuevo M\\u00e9dico Registrado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca fue registrado en el sistema\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\",\"medico_id\":8}', NULL, '2026-06-30 15:43:18', '2026-06-30 15:43:18'),
('a1d1bbfd-9a98-4c56-b6bb-82853b7dfc4e', 'App\\Notifications\\Admin\\CitaCanceladaAdmin', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Cita Cancelada\",\"mensaje\":\"JorgePaciente montes de oca cancel\\u00f3 su cita del 2026-07-13\",\"tipo\":\"warning\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/citas\\/152\",\"cita_id\":152,\"motivo\":\"Transporte: no hay gasolina\",\"es_paciente_especial\":false,\"consultorio_nombre\":\"Consultorios del Este\",\"consultorio_id\":5,\"paciente_nombre\":\"JorgePaciente montes de oca\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-13\"}', '2026-06-30 16:17:49', '2026-06-30 16:17:15', '2026-06-30 16:17:49'),
('a247fa45-fbf4-469a-a534-3e2e6a5d6671', 'App\\Notifications\\Admin\\NuevaCitaAgendada', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Nueva Cita Agendada\",\"mensaje\":\"JorgePaciente montes de oca agend\\u00f3 una cita para el 2026-07-06\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/citas\\/151\",\"cita_id\":151,\"es_paciente_especial\":false,\"consultorio_nombre\":\"Consultorios del Este\",\"consultorio_id\":\"5\",\"paciente_nombre\":\"JorgePaciente montes de oca\",\"paciente_documento\":\"V-30004549\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-06\",\"hora_inicio\":\"14:00\"}', '2026-06-30 16:17:49', '2026-06-30 15:49:01', '2026-06-30 16:17:49'),
('a9c75362-3c59-4eac-ba1d-99107cd4f273', 'App\\Notifications\\Admin\\MedicoHorarioActualizado', 'App\\Models\\Administrador', 2, '{\"titulo\":\"Horario Actualizado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca actualiz\\u00f3 sus horarios de atenci\\u00f3n\",\"tipo\":\"info\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\\/horarios\",\"medico_id\":8}', NULL, '2026-06-30 15:44:52', '2026-06-30 15:44:52'),
('b5b04180-b4e7-444b-9ac9-5956648ea8e6', 'App\\Notifications\\Admin\\MedicoHorarioActualizado', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Horario Actualizado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca actualiz\\u00f3 sus horarios de atenci\\u00f3n\",\"tipo\":\"info\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\\/horarios\",\"medico_id\":8}', '2026-06-30 16:17:49', '2026-06-30 15:44:52', '2026-06-30 16:17:49'),
('c900cef2-b6e0-4880-87d1-ff2b44456335', 'App\\Notifications\\Admin\\NuevoMedicoRegistrado', 'App\\Models\\Administrador', 4, '{\"titulo\":\"Nuevo M\\u00e9dico Registrado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca fue registrado en el sistema\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\",\"medico_id\":8}', NULL, '2026-06-30 15:43:18', '2026-06-30 15:43:18'),
('d2aadecc-4877-4405-beef-07711fe31ec3', 'App\\Notifications\\Admin\\NuevaCitaAgendada', 'App\\Models\\Administrador', 1, '{\"titulo\":\"Nueva Cita Agendada\",\"mensaje\":\"JorgePaciente montes de oca agend\\u00f3 una cita para el 2026-07-13\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/citas\\/152\",\"cita_id\":152,\"es_paciente_especial\":false,\"consultorio_nombre\":\"Consultorios del Este\",\"consultorio_id\":\"5\",\"paciente_nombre\":\"JorgePaciente montes de oca\",\"paciente_documento\":\"V-30004549\",\"medico_nombre\":\"Dr. JorgeMedico Mdeoca\",\"fecha_cita\":\"2026-07-13\",\"hora_inicio\":\"14:00\"}', '2026-06-30 16:17:49', '2026-06-30 16:12:00', '2026-06-30 16:17:49'),
('d4cdac02-5302-44b3-861d-bc311c6d10d5', 'App\\Notifications\\Admin\\NuevoMedicoRegistrado', 'App\\Models\\Administrador', 3, '{\"titulo\":\"Nuevo M\\u00e9dico Registrado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca fue registrado en el sistema\",\"tipo\":\"success\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\",\"medico_id\":8}', NULL, '2026-06-30 15:43:18', '2026-06-30 15:43:18'),
('db11bcc4-d771-4b41-a4c2-ef8a0547f135', 'App\\Notifications\\Medico\\NuevaCitaAsignada', 'App\\Models\\Medico', 8, '{\"titulo\":\"Nueva Cita Asignada\",\"mensaje\":\"Cita con JorgePaciente montes de oca el 13\\/07\\/2026 a las 14:00\",\"tipo\":\"info\",\"cita_id\":152,\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/citas\\/152\",\"paciente_nombre\":\"JorgePaciente montes de oca\",\"fecha_cita\":\"2026-07-13\",\"hora_inicio\":\"14:00\",\"hora_fin\":\"14:30\",\"especialidad\":\"Pediatr\\u00eda\",\"consultorio\":\"Consultorios del Este\"}', NULL, '2026-06-30 16:12:00', '2026-06-30 16:12:00'),
('e2c841f6-cc19-4729-8e57-e4703d0ccd98', 'App\\Notifications\\Admin\\MedicoHorarioActualizado', 'App\\Models\\Administrador', 3, '{\"titulo\":\"Horario Actualizado\",\"mensaje\":\"Dr. JorgeMedico Mdeoca actualiz\\u00f3 sus horarios de atenci\\u00f3n\",\"tipo\":\"info\",\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/medicos\\/8\\/horarios\",\"medico_id\":8}', NULL, '2026-06-30 15:44:52', '2026-06-30 15:44:52'),
('ef8caf06-a2e4-44ef-aa60-480f6152bcb7', 'App\\Notifications\\Medico\\NuevaCitaAsignada', 'App\\Models\\Medico', 8, '{\"titulo\":\"Nueva Cita Asignada\",\"mensaje\":\"Cita con JorgePaciente montes de oca el 06\\/07\\/2026 a las 14:00\",\"tipo\":\"info\",\"cita_id\":151,\"link\":\"http:\\/\\/localhost\\/reservamedica\\/public\\/citas\\/151\",\"paciente_nombre\":\"JorgePaciente montes de oca\",\"fecha_cita\":\"2026-07-06\",\"hora_inicio\":\"14:00\",\"hora_fin\":\"14:30\",\"especialidad\":\"Pediatr\\u00eda\",\"consultorio\":\"Consultorios del Este\"}', NULL, '2026-06-30 15:49:01', '2026-06-30 15:49:01');

-- --------------------------------------------------------

--
-- Table structure for table `ordenes_medicas`
--

CREATE TABLE `ordenes_medicas` (
  `id` bigint UNSIGNED NOT NULL,
  `codigo_orden` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cita_id` bigint UNSIGNED DEFAULT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `paciente_especial_id` bigint UNSIGNED DEFAULT NULL,
  `representante_id` bigint UNSIGNED DEFAULT NULL,
  `medico_id` bigint UNSIGNED NOT NULL,
  `especialidad_id` bigint UNSIGNED DEFAULT NULL,
  `tipo_orden` enum('Receta','Laboratorio','Imagenologia','Referencia','Interconsulta','Procedimiento','Mixta') COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion_detallada` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `indicaciones` text COLLATE utf8mb4_unicode_ci,
  `resultados` text COLLATE utf8mb4_unicode_ci,
  `fecha_emision` date NOT NULL,
  `fecha_vigencia` date DEFAULT NULL,
  `estado_orden` enum('Emitida','Parcialmente Procesada','Procesada','Cancelada') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Emitida',
  `diagnostico_principal` text COLLATE utf8mb4_unicode_ci,
  `firma_digital` text COLLATE utf8mb4_unicode_ci,
  `fecha_procesamiento` datetime DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ordenes_medicas`
--

INSERT INTO `ordenes_medicas` (`id`, `codigo_orden`, `cita_id`, `paciente_id`, `paciente_especial_id`, `representante_id`, `medico_id`, `especialidad_id`, `tipo_orden`, `descripcion_detallada`, `indicaciones`, `resultados`, `fecha_emision`, `fecha_vigencia`, `estado_orden`, `diagnostico_principal`, `firma_digital`, `fecha_procesamiento`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 1, 2, NULL, NULL, 7, NULL, 'Laboratorio', 'Hemograma completo, perfil lipídico, glicemia', 'Ayuno de 8 horas previas', NULL, '2026-06-30', '2026-07-30', 'Emitida', NULL, NULL, NULL, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 'ORD-2026-0001', 151, 4, NULL, NULL, 8, 2, 'Mixta', 'Ver items detallados', NULL, NULL, '2026-06-30', '2026-09-30', 'Emitida', NULL, NULL, NULL, 1, '2026-06-30 16:31:35', '2026-06-30 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `orden_examenes`
--

CREATE TABLE `orden_examenes` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `tipo_examen` enum('Hematológico','Bioquímica','Orina','Heces','Serología','Hormonal','Microbiología','Otro','Inmunología','Marcadores Tumorales','Coagulación','Gases Arteriales') COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_examen` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `urgente` tinyint(1) NOT NULL DEFAULT '0',
  `indicacion_clinica` text COLLATE utf8mb4_unicode_ci,
  `resultado` text COLLATE utf8mb4_unicode_ci,
  `fecha_resultado` date DEFAULT NULL,
  `laboratorio` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orden_imagenes`
--

CREATE TABLE `orden_imagenes` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `tipo_estudio` enum('Rayos X','Ecografia','TAC','Resonancia Magnetica','Mamografia','Densitometria','Gammagrafia','PET','Angiografia','Fluoroscopia','Otro','Electrocardiograma') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `region_anatomica` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `proyecciones` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contraste` tinyint(1) NOT NULL DEFAULT '0',
  `urgente` tinyint(1) NOT NULL DEFAULT '0',
  `indicacion_clinica` text COLLATE utf8mb4_unicode_ci,
  `resultado` text COLLATE utf8mb4_unicode_ci,
  `archivo_imagen` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_resultado` date DEFAULT NULL,
  `centro_imagenes` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orden_imagenes`
--

INSERT INTO `orden_imagenes` (`id`, `orden_id`, `tipo_estudio`, `region_anatomica`, `proyecciones`, `contraste`, `urgente`, `indicacion_clinica`, `resultado`, `archivo_imagen`, `fecha_resultado`, `centro_imagenes`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'Rayos X', 'rodilla', 'ap', 1, 0, '', NULL, NULL, NULL, NULL, NULL, 1, '2026-06-30 16:31:35', '2026-06-30 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `orden_medicamentos`
--

CREATE TABLE `orden_medicamentos` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `medicamento` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `presentacion` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cantidad` int NOT NULL DEFAULT '1',
  `dosis` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `via_administracion` enum('Oral','Sublingual','Intravenosa','Intramuscular','Subcutanea','Topica','Oftalmica','Otica','Nasal','Inhalatoria','Rectal','Vaginal','Otra') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Oral',
  `duracion_dias` int DEFAULT NULL,
  `indicaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orden_medicamentos`
--

INSERT INTO `orden_medicamentos` (`id`, `orden_id`, `medicamento`, `presentacion`, `cantidad`, `dosis`, `via_administracion`, `duracion_dias`, `indicaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 'atamel ', '777mg', 1, 'c/19h', 'Oral', 7, '', 1, '2026-06-30 16:31:35', '2026-06-30 16:31:35');

-- --------------------------------------------------------

--
-- Table structure for table `orden_referencias`
--

CREATE TABLE `orden_referencias` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `especialidad_destino` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `medico_referido_id` bigint UNSIGNED DEFAULT NULL,
  `motivo_referencia` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `resumen_clinico` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `prioridad` enum('Normal','Preferente','Urgente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Normal',
  `respuesta` text COLLATE utf8mb4_unicode_ci,
  `fecha_atencion` date DEFAULT NULL,
  `recomendaciones_especialista` text COLLATE utf8mb4_unicode_ci,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pacientes`
--

CREATE TABLE `pacientes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `primer_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_documento` enum('V','E','P','J') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `estado_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `prefijo_tlf` enum('+58','+57','+1','+34') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_tlf` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ocupacion` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estado_civil` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `foto_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_perfil` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `banner_color` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tema_dinamico` tinyint(1) NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `es_especial` tinyint DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pacientes`
--

INSERT INTO `pacientes` (`id`, `user_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `fecha_nac`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `prefijo_tlf`, `numero_tlf`, `genero`, `ocupacion`, `estado_civil`, `foto_perfil`, `banner_perfil`, `banner_color`, `tema_dinamico`, `status`, `es_especial`, `created_at`, `updated_at`) VALUES
(1, 8, 'José', NULL, 'Méndez', NULL, 'V', '21000008', '1995-02-14', 1, 1, 1, 1, 'Barrio 5 de Julio', '+58', '4240000008', 'Masculino', 'Estudiante', 'Soltero', NULL, NULL, NULL, 0, 1, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 9, 'Carmen', NULL, 'Ortiz', NULL, 'V', '22000009', '1960-09-30', 1, 1, 1, 1, 'Residencias Los Andes', '+58', '4160000009', 'Femenino', 'Jubilada', 'Viuda', NULL, NULL, NULL, 0, 1, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 10, 'Luis', NULL, 'Silva', NULL, 'V', '23000010', '2010-06-01', 1, 1, 1, 1, 'Urbanización del Este', '+58', '4120000010', 'Masculino', 'Estudiante', 'Soltero', NULL, NULL, NULL, 0, 1, 0, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 12, 'JorgePaciente', 'luis', 'montes de oca', 'gonzalez', 'V', '30004549', '2000-10-26', 13, 23, 30, 44, 'cabudare', '+58', '4245845707', 'Masculino', NULL, NULL, NULL, NULL, NULL, 0, 1, 0, '2026-06-30 15:48:12', '2026-06-30 15:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `pacientes_especiales`
--

CREATE TABLE `pacientes_especiales` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED DEFAULT NULL,
  `primer_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `segundo_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_documento` enum('V','E','P','J') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `tiene_documento` tinyint(1) NOT NULL DEFAULT '1',
  `estado_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `tipo` enum('Menor de Edad','Discapacitado','Anciano','Incapacitado') COLLATE utf8mb4_unicode_ci NOT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pacientes_especiales`
--

INSERT INTO `pacientes_especiales` (`id`, `paciente_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `fecha_nac`, `tiene_documento`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `tipo`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, 'Menor de Edad', 'Paciente menor de edad, requiere representante legal', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `pago`
--

CREATE TABLE `pago` (
  `id_pago` bigint UNSIGNED NOT NULL,
  `id_factura_paciente` bigint UNSIGNED NOT NULL,
  `id_metodo` bigint UNSIGNED NOT NULL,
  `fecha_pago` date NOT NULL,
  `monto_pagado_bs` decimal(20,2) NOT NULL,
  `monto_equivalente_usd` decimal(10,2) NOT NULL,
  `tasa_aplicada_id` bigint UNSIGNED DEFAULT NULL,
  `referencia` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comprobante` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `comentarios` text COLLATE utf8mb4_unicode_ci,
  `estado` enum('Pendiente','Confirmado','Rechazado','Reembolsado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `confirmado_por` bigint UNSIGNED DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pago`
--

INSERT INTO `pago` (`id_pago`, `id_factura_paciente`, `id_metodo`, `fecha_pago`, `monto_pagado_bs`, `monto_equivalente_usd`, `tasa_aplicada_id`, `referencia`, `comprobante`, `comentarios`, `estado`, `confirmado_por`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 4, '2026-06-30', 1420.00, 40.00, 1, 'PM123456789', NULL, 'Pago realizado mediante Pago Móvil', 'Confirmado', 1, 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 4, '2026-06-30', 2174.00, 50.00, 61, '45678', 'comprobantes_pagos/sViPPYIHodUFX1BFIlNgTHE6jncWsx8fsQX5V7ir.png', NULL, 'Confirmado', 1, 1, '2026-06-30 15:50:41', '2026-06-30 15:54:12'),
(3, 3, 1, '2026-06-30', 2174.00, 50.00, 61, '7687686', 'comprobantes_pagos/69jjmL9RdzVg0WZRycCqFPSTXqaeBClaZ5i7akGJ.png', 'por que si - ', 'Rechazado', NULL, 1, '2026-06-30 16:12:29', '2026-06-30 16:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `parroquias`
--

CREATE TABLE `parroquias` (
  `id_parroquia` bigint UNSIGNED NOT NULL,
  `id_municipio` bigint UNSIGNED NOT NULL,
  `parroquia` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parroquias`
--

INSERT INTO `parroquias` (`id_parroquia`, `id_municipio`, `parroquia`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Catedral', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 1, 'La Candelaria', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 1, 'San José', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 1, 'El Recreo', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 1, 'La Pastora', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 1, '23 de Enero', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 2, 'Fernando Girón Tovar', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 2, 'Luis Alberto Gómez', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 4, 'Barcelona', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 4, 'El Carmen', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 5, 'Guanta', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(12, 5, 'Chorreron', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(13, 7, 'San Fernando', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(14, 7, 'El Recreo', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(15, 9, 'Las Delicias', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(16, 9, 'Madre María', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(17, 9, 'San José', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(18, 10, 'Turmero', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(19, 10, 'Saman de Güere', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(20, 12, 'Barinas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(21, 12, 'El Carmen', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(22, 14, 'Catedral', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(23, 14, 'Vista Hermosa', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(24, 15, 'Unare', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(25, 15, 'Cachamay', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(26, 15, 'Universidad', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(27, 17, 'Catedral', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(28, 17, 'San Blas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(29, 17, 'Rafael Urdaneta', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(30, 17, 'Candelaria', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(31, 18, 'Puerto Cabello', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(32, 18, 'Borburata', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(33, 21, 'San Carlos de Austria', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(34, 23, 'San Rafael', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(35, 23, 'Virgen del Valle', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(36, 25, 'Coro', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(37, 25, 'San Antonio', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(38, 26, 'Norte', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(39, 26, 'Carirubana', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(40, 28, 'San Juan de los Morros', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(41, 29, 'Calabozo', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(42, 30, 'Catedral', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(43, 30, 'Concepción', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(44, 30, 'Santa Rosa', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(45, 30, 'Juan de Villegas', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(46, 31, 'Carora', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(47, 33, 'El Llano', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(48, 33, 'Domingo Peña', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(49, 33, 'Milla', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(50, 34, 'El Vigía', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(51, 36, 'Los Teques', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(52, 36, 'Cecilio Acosta', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(53, 37, 'Guarenas', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(54, 38, 'Guatire', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(55, 39, 'Petare', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(56, 39, 'Leoncio Martínez', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(57, 40, 'San Simón', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(58, 40, 'Alto de Los Godos', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(59, 42, 'Porlamar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(60, 43, 'La Asunción', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(61, 44, 'Guanare', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(62, 45, 'Acarigua', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(63, 47, 'Altagracia', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(64, 47, 'Santa Inés', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(65, 48, 'Carúpano', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(66, 50, 'San Juan Bautista', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(67, 50, 'Pedro María Morantes', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(68, 51, 'Táriba', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(69, 53, 'Trujillo', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(70, 54, 'Mercedes Díaz', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(71, 56, 'Maiquetía', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(72, 56, 'Catia La Mar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(73, 56, 'La Guaira', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(74, 57, 'San Felipe', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(75, 59, 'Bolívar', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(76, 59, 'Coquivacoa', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(77, 59, 'Cristo de Aranza', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(78, 59, 'Santa Lucía', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(79, 60, 'Ambrosio', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(80, 60, 'Carmen Herrera', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `preguntas_catalogo`
--

CREATE TABLE `preguntas_catalogo` (
  `id` bigint UNSIGNED NOT NULL,
  `pregunta` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `preguntas_catalogo`
--

INSERT INTO `preguntas_catalogo` (`id`, `pregunta`, `status`, `created_at`, `updated_at`) VALUES
(1, '¿Cuál es el nombre de tu primera mascota?', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, '¿En qué ciudad naciste?', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, '¿Cuál es tu color favorito?', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, '¿Nombre de tu escuela primaria?', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, '¿Cuál es tu comida favorita?', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `read_audit_logs`
--

CREATE TABLE `read_audit_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `reader_id` bigint UNSIGNED NOT NULL,
  `reader_nombre` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reader_rol` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resource_type` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `resource_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED DEFAULT NULL,
  `paciente_nombre` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ruta_accedida` varchar(200) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `representantes`
--

CREATE TABLE `representantes` (
  `id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED DEFAULT NULL,
  `primer_nombre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_nombre` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primer_apellido` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `segundo_apellido` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tipo_documento` enum('V','E','P','J') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_documento` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fecha_nac` date DEFAULT NULL,
  `estado_id` bigint UNSIGNED DEFAULT NULL,
  `ciudad_id` bigint UNSIGNED DEFAULT NULL,
  `municipio_id` bigint UNSIGNED DEFAULT NULL,
  `parroquia_id` bigint UNSIGNED DEFAULT NULL,
  `direccion_detallada` text COLLATE utf8mb4_unicode_ci,
  `prefijo_tlf` enum('+58','+57','+1','+34') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero_tlf` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `genero` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parentesco` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `representantes`
--

INSERT INTO `representantes` (`id`, `paciente_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento`, `numero_documento`, `fecha_nac`, `estado_id`, `ciudad_id`, `municipio_id`, `parroquia_id`, `direccion_detallada`, `prefijo_tlf`, `numero_tlf`, `genero`, `parentesco`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, 'Roberto', NULL, 'Martínez', NULL, 'V', '44555666', '1965-03-15', 1, 1, 1, 1, 'Mismo domicilio del paciente', '+58', '4125551122', 'Masculino', 'Padre', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `representante_paciente_especial`
--

CREATE TABLE `representante_paciente_especial` (
  `id` bigint UNSIGNED NOT NULL,
  `representante_id` bigint UNSIGNED NOT NULL,
  `paciente_especial_id` bigint UNSIGNED NOT NULL,
  `tipo_responsabilidad` enum('Principal','Suplente','Emergencia') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Principal',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `representante_paciente_especial`
--

INSERT INTO `representante_paciente_especial` (`id`, `representante_id`, `paciente_especial_id`, `tipo_responsabilidad`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Principal', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `respuestas_seguridad`
--

CREATE TABLE `respuestas_seguridad` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `pregunta_id` bigint UNSIGNED NOT NULL,
  `respuesta_hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `respuestas_seguridad`
--

INSERT INTO `respuestas_seguridad` (`id`, `user_id`, `pregunta_id`, `respuesta_hash`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'a3d6cb4f5d042aeb1162c434f2569c6b', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 2, 2, '8d2724db8082df9682932dc695fba1dc', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 3, 3, 'c03f03b8b3e4a00a4d37921b3fe022ac', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 4, 4, '00762a7f98d30455114cefbd32cc96fa', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 5, 5, 'eecfa7edad3dd024f7ace1c69320f066', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 12, 1, 'dcbae703e24bb656496844d2db48c79b', 1, '2026-06-30 15:48:12', '2026-06-30 15:48:12'),
(7, 12, 2, '6fcdb2b264ffe49dd570e2d79c2b7c42', 1, '2026-06-30 15:48:12', '2026-06-30 15:48:12'),
(8, 12, 3, '878a4ae7138a6a3170e56d9504c0d754', 1, '2026-06-30 15:48:12', '2026-06-30 15:48:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint UNSIGNED NOT NULL,
  `nombre` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `nombre`, `descripcion`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Administrador', 'Acceso completo al sistema', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 'Médico', 'Acceso médico para citas y pacientes', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 'Paciente', 'Acceso paciente para solicitar citas', 1, '2026-06-30 15:23:43', '2026-06-30 15:23:43');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes_historial`
--

CREATE TABLE `solicitudes_historial` (
  `id` bigint UNSIGNED NOT NULL,
  `cita_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `evolucion_id` bigint UNSIGNED DEFAULT NULL,
  `medico_solicitante_id` bigint UNSIGNED NOT NULL,
  `medico_propietario_id` bigint UNSIGNED NOT NULL,
  `token_validacion` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_expira_at` datetime NOT NULL,
  `intentos_fallidos` tinyint NOT NULL DEFAULT '0',
  `motivo_solicitud` enum('Interconsulta','Emergencia','Segunda Opinion','Referencia') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_permiso` enum('Pendiente','Aprobado','Rechazado','Expirado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `acceso_valido_hasta` datetime DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `solicitudes_historial`
--

INSERT INTO `solicitudes_historial` (`id`, `cita_id`, `paciente_id`, `evolucion_id`, `medico_solicitante_id`, `medico_propietario_id`, `token_validacion`, `token_expira_at`, `intentos_fallidos`, `motivo_solicitud`, `estado_permiso`, `acceso_valido_hasta`, `observaciones`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 2, NULL, 5, 7, 'TOKEN123', '2026-07-01 11:23:44', 0, 'Interconsulta', 'Pendiente', NULL, 'Solicitud de prueba', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `solicitudes_orden`
--

CREATE TABLE `solicitudes_orden` (
  `id` bigint UNSIGNED NOT NULL,
  `orden_id` bigint UNSIGNED NOT NULL,
  `paciente_id` bigint UNSIGNED NOT NULL,
  `medico_solicitante_id` bigint UNSIGNED NOT NULL,
  `medico_propietario_id` bigint UNSIGNED NOT NULL,
  `token_validacion` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token_expira_at` datetime NOT NULL,
  `intentos_fallidos` tinyint NOT NULL DEFAULT '0',
  `motivo_solicitud` enum('Interconsulta','Emergencia','Segunda Opinion','Referencia','Continuidad Tratamiento') COLLATE utf8mb4_unicode_ci NOT NULL,
  `estado_permiso` enum('Pendiente','Aprobado','Rechazado','Expirado') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Pendiente',
  `acceso_valido_hasta` datetime DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tasas_dolar`
--

CREATE TABLE `tasas_dolar` (
  `id` bigint UNSIGNED NOT NULL,
  `fuente` enum('BCV','MonitorDolar','Paralelo','Oficial') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BCV',
  `valor` decimal(12,4) NOT NULL,
  `fecha_tasa` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tasas_dolar`
--

INSERT INTO `tasas_dolar` (`id`, `fuente`, `valor`, `fecha_tasa`, `status`, `created_at`, `updated_at`) VALUES
(1, 'BCV', 35.6800, '2026-05-01', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(2, 'BCV', 36.1700, '2026-05-02', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(3, 'BCV', 36.2700, '2026-05-03', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(4, 'BCV', 35.9400, '2026-05-04', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(5, 'BCV', 36.6400, '2026-05-05', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(6, 'BCV', 36.6400, '2026-05-06', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(7, 'BCV', 37.1600, '2026-05-07', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(8, 'BCV', 36.9500, '2026-05-08', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(9, 'BCV', 36.6000, '2026-05-09', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(10, 'BCV', 37.3900, '2026-05-10', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(11, 'BCV', 37.9100, '2026-05-11', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(12, 'BCV', 37.5700, '2026-05-12', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(13, 'BCV', 37.1300, '2026-05-13', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(14, 'BCV', 37.6900, '2026-05-14', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(15, 'BCV', 38.4100, '2026-05-15', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(16, 'BCV', 38.9100, '2026-05-16', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(17, 'BCV', 38.6100, '2026-05-17', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(18, 'BCV', 38.9900, '2026-05-18', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(19, 'BCV', 39.4200, '2026-05-19', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(20, 'BCV', 39.6500, '2026-05-20', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(21, 'BCV', 39.3400, '2026-05-21', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(22, 'BCV', 39.5000, '2026-05-22', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(23, 'BCV', 39.3400, '2026-05-23', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(24, 'BCV', 39.1700, '2026-05-24', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(25, 'BCV', 39.7400, '2026-05-25', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(26, 'BCV', 40.2800, '2026-05-26', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(27, 'BCV', 40.5100, '2026-05-27', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(28, 'BCV', 40.6900, '2026-05-28', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(29, 'BCV', 40.9100, '2026-05-29', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(30, 'BCV', 40.8800, '2026-05-30', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(31, 'BCV', 40.9600, '2026-05-31', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(32, 'BCV', 40.5400, '2026-06-01', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(33, 'BCV', 40.3100, '2026-06-02', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(34, 'BCV', 40.1500, '2026-06-03', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(35, 'BCV', 40.2200, '2026-06-04', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(36, 'BCV', 39.9600, '2026-06-05', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(37, 'BCV', 40.2100, '2026-06-06', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(38, 'BCV', 40.3600, '2026-06-07', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(39, 'BCV', 41.1100, '2026-06-08', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(40, 'BCV', 41.0000, '2026-06-09', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(41, 'BCV', 41.5800, '2026-06-10', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(42, 'BCV', 41.4300, '2026-06-11', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(43, 'BCV', 42.1600, '2026-06-12', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(44, 'BCV', 41.8600, '2026-06-13', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(45, 'BCV', 41.4200, '2026-06-14', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(46, 'BCV', 41.6000, '2026-06-15', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(47, 'BCV', 42.3600, '2026-06-16', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(48, 'BCV', 42.0200, '2026-06-17', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(49, 'BCV', 42.0700, '2026-06-18', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(50, 'BCV', 42.2300, '2026-06-19', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(51, 'BCV', 42.2700, '2026-06-20', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(52, 'BCV', 42.9200, '2026-06-21', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(53, 'BCV', 42.8700, '2026-06-22', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(54, 'BCV', 43.0400, '2026-06-23', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(55, 'BCV', 42.9300, '2026-06-24', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(56, 'BCV', 43.1700, '2026-06-25', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(57, 'BCV', 42.7800, '2026-06-26', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(58, 'BCV', 43.3500, '2026-06-27', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(59, 'BCV', 42.9800, '2026-06-28', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(60, 'BCV', 43.3300, '2026-06-29', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44'),
(61, 'BCV', 43.4800, '2026-06-30', 1, '2026-06-30 15:23:44', '2026-06-30 15:23:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint UNSIGNED NOT NULL,
  `rol_id` bigint UNSIGNED NOT NULL,
  `correo` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint NOT NULL DEFAULT '1',
  `blocked_until` timestamp NULL DEFAULT NULL,
  `lock_reason` text COLLATE utf8mb4_unicode_ci,
  `failed_login_count` tinyint NOT NULL DEFAULT '0',
  `last_failed_at` timestamp NULL DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `rol_id`, `correo`, `password`, `status`, `blocked_until`, `lock_reason`, `failed_login_count`, `last_failed_at`, `email_verified_at`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'admin@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', 'aYQiJYP3KRXeMuY3kIrlpHWioxhKDCupwe0sUfYpoEKJV7GmCB0VsdCL8Phn', '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(2, 1, 'admin1@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(3, 1, 'admin2@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(4, 1, 'admin3@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(5, 2, 'medico1@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(6, 2, 'medico2@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(7, 2, 'medico3@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(8, 3, 'paciente1@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(9, 3, 'paciente2@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(10, 3, 'paciente3@clinica.com', '550e1bafe077ff0b0b67f4e32f29d751', 1, NULL, NULL, 0, NULL, '2026-06-30 15:23:43', NULL, '2026-06-30 15:23:43', '2026-06-30 15:23:43'),
(11, 2, 'jorgemedico@gmail.com', '31d91532a0aed3f595649f850f3a58ed', 1, NULL, NULL, 0, NULL, NULL, NULL, '2026-06-30 15:43:18', '2026-06-30 15:43:18'),
(12, 3, 'jorgepaciente@gmail.com', '31d91532a0aed3f595649f850f3a58ed', 1, NULL, NULL, 0, NULL, NULL, NULL, '2026-06-30 15:48:12', '2026-06-30 15:48:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `administradores`
--
ALTER TABLE `administradores`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administradores_user_id_unique` (`user_id`),
  ADD KEY `administradores_estado_id_foreign` (`estado_id`),
  ADD KEY `administradores_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `administradores_municipio_id_foreign` (`municipio_id`),
  ADD KEY `administradores_parroquia_id_foreign` (`parroquia_id`);

--
-- Indexes for table `administrador_consultorio`
--
ALTER TABLE `administrador_consultorio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `administrador_consultorio_administrador_id_consultorio_id_unique` (`administrador_id`,`consultorio_id`),
  ADD KEY `administrador_consultorio_consultorio_id_foreign` (`consultorio_id`);

--
-- Indexes for table `auditorias_historia_base`
--
ALTER TABLE `auditorias_historia_base`
  ADD PRIMARY KEY (`id`),
  ADD KEY `aud_hist_base_created_idx` (`historia_clinica_base_id`,`created_at`),
  ADD KEY `aud_hist_medico_idx` (`medico_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_auditable` (`auditable_type`,`auditable_id`,`created_at`),
  ADD KEY `idx_causer` (`causer_id`,`created_at`),
  ADD KEY `idx_modulo_event` (`modulo`,`event`,`created_at`),
  ADD KEY `idx_ip` (`ip_address`,`created_at`);

--
-- Indexes for table `auth_logs`
--
ALTER TABLE `auth_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_auth_user` (`user_id`,`created_at`),
  ADD KEY `idx_auth_ip_event` (`ip_address`,`event_type`,`created_at`),
  ADD KEY `idx_auth_event` (`event_type`,`created_at`),
  ADD KEY `idx_auth_correo` (`correo_intentado`,`created_at`);

--
-- Indexes for table `citas`
--
ALTER TABLE `citas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `citas_medico_id_foreign` (`medico_id`),
  ADD KEY `citas_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `citas_consultorio_id_foreign` (`consultorio_id`),
  ADD KEY `citas_fecha_cita_medico_id_index` (`fecha_cita`,`medico_id`),
  ADD KEY `citas_paciente_id_fecha_cita_index` (`paciente_id`,`fecha_cita`),
  ADD KEY `citas_paciente_especial_id_foreign` (`paciente_especial_id`),
  ADD KEY `citas_representante_id_foreign` (`representante_id`);

--
-- Indexes for table `ciudades`
--
ALTER TABLE `ciudades`
  ADD PRIMARY KEY (`id_ciudad`),
  ADD UNIQUE KEY `ciudades_ciudad_id_estado_unique` (`ciudad`,`id_estado`),
  ADD KEY `ciudades_id_estado_foreign` (`id_estado`);

--
-- Indexes for table `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configuraciones_key_unique` (`key`);

--
-- Indexes for table `configuracion_global`
--
ALTER TABLE `configuracion_global`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configuracion_global_clave_unique` (`clave`),
  ADD KEY `configuracion_global_clave_index` (`clave`);

--
-- Indexes for table `configuracion_reparto`
--
ALTER TABLE `configuracion_reparto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `configuracion_reparto_medico_id_consultorio_id_unique` (`medico_id`,`consultorio_id`),
  ADD KEY `configuracion_reparto_consultorio_id_foreign` (`consultorio_id`);

--
-- Indexes for table `consultorios`
--
ALTER TABLE `consultorios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `consultorios_nombre_unique` (`nombre`),
  ADD KEY `consultorios_estado_id_foreign` (`estado_id`),
  ADD KEY `consultorios_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `consultorios_municipio_id_foreign` (`municipio_id`),
  ADD KEY `consultorios_parroquia_id_foreign` (`parroquia_id`);

--
-- Indexes for table `datos_pago_medico`
--
ALTER TABLE `datos_pago_medico`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `datos_pago_medico_medico_id_unique` (`medico_id`),
  ADD KEY `datos_pago_medico_metodo_pago_id_foreign` (`metodo_pago_id`);

--
-- Indexes for table `especialidades`
--
ALTER TABLE `especialidades`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `especialidades_nombre_unique` (`nombre`);

--
-- Indexes for table `especialidad_consultorio`
--
ALTER TABLE `especialidad_consultorio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `especialidad_consultorio_especialidad_id_consultorio_id_unique` (`especialidad_id`,`consultorio_id`),
  ADD KEY `especialidad_consultorio_consultorio_id_foreign` (`consultorio_id`);

--
-- Indexes for table `estados`
--
ALTER TABLE `estados`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indexes for table `evolucion_clinica`
--
ALTER TABLE `evolucion_clinica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `evolucion_clinica_cita_id_foreign` (`cita_id`),
  ADD KEY `evolucion_clinica_medico_id_foreign` (`medico_id`),
  ADD KEY `evolucion_clinica_paciente_id_created_at_index` (`paciente_id`,`created_at`);

--
-- Indexes for table `facturas_pacientes`
--
ALTER TABLE `facturas_pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `facturas_pacientes_cita_id_unique` (`cita_id`),
  ADD UNIQUE KEY `facturas_pacientes_numero_factura_unique` (`numero_factura`),
  ADD KEY `facturas_pacientes_paciente_id_foreign` (`paciente_id`),
  ADD KEY `facturas_pacientes_medico_id_foreign` (`medico_id`),
  ADD KEY `facturas_pacientes_tasa_id_foreign` (`tasa_id`),
  ADD KEY `facturas_pacientes_fecha_emision_index` (`fecha_emision`),
  ADD KEY `facturas_pacientes_numero_factura_index` (`numero_factura`);

--
-- Indexes for table `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `factura_cabecera_nro_control_unique` (`nro_control`),
  ADD KEY `factura_cabecera_cita_id_foreign` (`cita_id`),
  ADD KEY `factura_cabecera_paciente_id_foreign` (`paciente_id`),
  ADD KEY `factura_cabecera_medico_id_foreign` (`medico_id`),
  ADD KEY `factura_cabecera_tasa_id_foreign` (`tasa_id`),
  ADD KEY `factura_cabecera_fecha_emision_index` (`fecha_emision`);

--
-- Indexes for table `factura_detalles`
--
ALTER TABLE `factura_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_detalles_cabecera_id_foreign` (`cabecera_id`);

--
-- Indexes for table `factura_totales`
--
ALTER TABLE `factura_totales`
  ADD PRIMARY KEY (`id`),
  ADD KEY `factura_totales_cabecera_id_foreign` (`cabecera_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `fecha_indisponible`
--
ALTER TABLE `fecha_indisponible`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fecha_indisponible_consultorio_id_foreign` (`consultorio_id`),
  ADD KEY `fecha_indisponible_medico_id_fecha_index` (`medico_id`,`fecha`);

--
-- Indexes for table `historial_password`
--
ALTER TABLE `historial_password`
  ADD PRIMARY KEY (`id`),
  ADD KEY `historial_password_user_id_index` (`user_id`);

--
-- Indexes for table `historia_clinica_base`
--
ALTER TABLE `historia_clinica_base`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `historia_clinica_base_paciente_id_unique` (`paciente_id`);

--
-- Indexes for table `known_devices`
--
ALTER TABLE `known_devices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `known_devices_user_id_ip_address_index` (`user_id`,`ip_address`);

--
-- Indexes for table `liquidaciones`
--
ALTER TABLE `liquidaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidaciones_fecha_pago_index` (`fecha_pago`),
  ADD KEY `idx_entidad_periodo` (`entidad_tipo`,`entidad_id`,`periodo_tipo`),
  ADD KEY `idx_fechas_periodo` (`fecha_inicio_periodo`,`fecha_fin_periodo`);

--
-- Indexes for table `liquidacion_detalles`
--
ALTER TABLE `liquidacion_detalles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `liquidacion_detalles_liquidacion_id_foreign` (`liquidacion_id`),
  ADD KEY `liquidacion_detalles_factura_total_id_foreign` (`factura_total_id`);

--
-- Indexes for table `medicos`
--
ALTER TABLE `medicos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medicos_user_id_unique` (`user_id`),
  ADD KEY `medicos_estado_id_foreign` (`estado_id`),
  ADD KEY `medicos_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `medicos_municipio_id_foreign` (`municipio_id`),
  ADD KEY `medicos_parroquia_id_foreign` (`parroquia_id`);

--
-- Indexes for table `medico_consultorio`
--
ALTER TABLE `medico_consultorio`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medico_horario_turno_unique` (`medico_id`,`dia_semana`,`turno`),
  ADD KEY `medico_consultorio_consultorio_id_foreign` (`consultorio_id`),
  ADD KEY `medico_consultorio_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `idx_medico_status` (`medico_id`,`status`);

--
-- Indexes for table `medico_especialidad`
--
ALTER TABLE `medico_especialidad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `medico_especialidad_medico_id_especialidad_id_unique` (`medico_id`,`especialidad_id`),
  ADD KEY `medico_especialidad_especialidad_id_foreign` (`especialidad_id`);

--
-- Indexes for table `metodo_pago`
--
ALTER TABLE `metodo_pago`
  ADD PRIMARY KEY (`id_metodo`),
  ADD UNIQUE KEY `metodo_pago_descripcion_unique` (`descripcion`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipios`
--
ALTER TABLE `municipios`
  ADD PRIMARY KEY (`id_municipio`),
  ADD UNIQUE KEY `municipios_municipio_id_estado_unique` (`municipio`,`id_estado`),
  ADD KEY `municipios_id_estado_foreign` (`id_estado`);

--
-- Indexes for table `notificaciones`
--
ALTER TABLE `notificaciones`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notificaciones_receptor_id_receptor_rol_estado_envio_index` (`receptor_id`,`receptor_rol`,`estado_envio`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `ordenes_medicas`
--
ALTER TABLE `ordenes_medicas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ordenes_medicas_codigo_orden_unique` (`codigo_orden`),
  ADD KEY `ordenes_medicas_cita_id_foreign` (`cita_id`),
  ADD KEY `ordenes_medicas_medico_id_foreign` (`medico_id`),
  ADD KEY `ordenes_medicas_paciente_id_index` (`paciente_id`),
  ADD KEY `ordenes_medicas_paciente_especial_id_foreign` (`paciente_especial_id`),
  ADD KEY `ordenes_medicas_especialidad_id_foreign` (`especialidad_id`),
  ADD KEY `ordenes_medicas_codigo_orden_index` (`codigo_orden`),
  ADD KEY `ordenes_medicas_estado_orden_index` (`estado_orden`),
  ADD KEY `ordenes_medicas_representante_id_index` (`representante_id`);

--
-- Indexes for table `orden_examenes`
--
ALTER TABLE `orden_examenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_examenes_orden_id_index` (`orden_id`),
  ADD KEY `orden_examenes_tipo_examen_index` (`tipo_examen`);

--
-- Indexes for table `orden_imagenes`
--
ALTER TABLE `orden_imagenes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_imagenes_orden_id_index` (`orden_id`),
  ADD KEY `orden_imagenes_tipo_estudio_index` (`tipo_estudio`);

--
-- Indexes for table `orden_medicamentos`
--
ALTER TABLE `orden_medicamentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_medicamentos_orden_id_index` (`orden_id`);

--
-- Indexes for table `orden_referencias`
--
ALTER TABLE `orden_referencias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orden_referencias_medico_referido_id_foreign` (`medico_referido_id`),
  ADD KEY `orden_referencias_orden_id_index` (`orden_id`),
  ADD KEY `orden_referencias_especialidad_destino_index` (`especialidad_destino`);

--
-- Indexes for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pacientes_user_id_unique` (`user_id`),
  ADD KEY `pacientes_estado_id_foreign` (`estado_id`),
  ADD KEY `pacientes_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `pacientes_municipio_id_foreign` (`municipio_id`),
  ADD KEY `pacientes_parroquia_id_foreign` (`parroquia_id`);

--
-- Indexes for table `pacientes_especiales`
--
ALTER TABLE `pacientes_especiales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pacientes_especiales_paciente_id_tipo_unique` (`paciente_id`,`tipo`),
  ADD KEY `pacientes_especiales_estado_id_foreign` (`estado_id`),
  ADD KEY `pacientes_especiales_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `pacientes_especiales_municipio_id_foreign` (`municipio_id`),
  ADD KEY `pacientes_especiales_parroquia_id_foreign` (`parroquia_id`);

--
-- Indexes for table `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD KEY `pago_id_factura_paciente_foreign` (`id_factura_paciente`),
  ADD KEY `pago_tasa_aplicada_id_foreign` (`tasa_aplicada_id`),
  ADD KEY `pago_confirmado_por_foreign` (`confirmado_por`),
  ADD KEY `pago_id_metodo_foreign` (`id_metodo`),
  ADD KEY `pago_fecha_pago_index` (`fecha_pago`);

--
-- Indexes for table `parroquias`
--
ALTER TABLE `parroquias`
  ADD PRIMARY KEY (`id_parroquia`),
  ADD UNIQUE KEY `parroquias_parroquia_id_municipio_unique` (`parroquia`,`id_municipio`),
  ADD KEY `parroquias_id_municipio_foreign` (`id_municipio`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Indexes for table `preguntas_catalogo`
--
ALTER TABLE `preguntas_catalogo`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `preguntas_catalogo_pregunta_unique` (`pregunta`);

--
-- Indexes for table `read_audit_logs`
--
ALTER TABLE `read_audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_read_reader` (`reader_id`,`created_at`),
  ADD KEY `idx_read_paciente` (`paciente_id`,`created_at`),
  ADD KEY `idx_read_resource` (`resource_type`,`resource_id`);

--
-- Indexes for table `representantes`
--
ALTER TABLE `representantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `representantes_estado_id_foreign` (`estado_id`),
  ADD KEY `representantes_ciudad_id_foreign` (`ciudad_id`),
  ADD KEY `representantes_municipio_id_foreign` (`municipio_id`),
  ADD KEY `representantes_parroquia_id_foreign` (`parroquia_id`),
  ADD KEY `representantes_paciente_id_foreign` (`paciente_id`);

--
-- Indexes for table `representante_paciente_especial`
--
ALTER TABLE `representante_paciente_especial`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `rep_pac_unique` (`representante_id`,`paciente_especial_id`),
  ADD KEY `representante_paciente_especial_paciente_especial_id_foreign` (`paciente_especial_id`);

--
-- Indexes for table `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `respuestas_seguridad_user_id_pregunta_id_unique` (`user_id`,`pregunta_id`),
  ADD KEY `respuestas_seguridad_pregunta_id_foreign` (`pregunta_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_nombre_unique` (`nombre`);

--
-- Indexes for table `solicitudes_historial`
--
ALTER TABLE `solicitudes_historial`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitudes_historial_cita_id_foreign` (`cita_id`),
  ADD KEY `solicitudes_historial_paciente_id_foreign` (`paciente_id`),
  ADD KEY `solicitudes_historial_medico_solicitante_id_foreign` (`medico_solicitante_id`),
  ADD KEY `solicitudes_historial_medico_propietario_id_foreign` (`medico_propietario_id`),
  ADD KEY `solicitudes_historial_token_validacion_estado_permiso_index` (`token_validacion`,`estado_permiso`),
  ADD KEY `solicitudes_historial_evolucion_id_foreign` (`evolucion_id`);

--
-- Indexes for table `solicitudes_orden`
--
ALTER TABLE `solicitudes_orden`
  ADD PRIMARY KEY (`id`),
  ADD KEY `solicitudes_orden_paciente_id_foreign` (`paciente_id`),
  ADD KEY `solicitudes_orden_medico_propietario_id_foreign` (`medico_propietario_id`),
  ADD KEY `solicitudes_orden_orden_id_estado_permiso_index` (`orden_id`,`estado_permiso`),
  ADD KEY `solicitudes_orden_medico_solicitante_id_estado_permiso_index` (`medico_solicitante_id`,`estado_permiso`),
  ADD KEY `solicitudes_orden_token_validacion_index` (`token_validacion`);

--
-- Indexes for table `tasas_dolar`
--
ALTER TABLE `tasas_dolar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tasas_dolar_fecha_tasa_index` (`fecha_tasa`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuarios_correo_unique` (`correo`),
  ADD KEY `usuarios_rol_id_foreign` (`rol_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `administradores`
--
ALTER TABLE `administradores`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `administrador_consultorio`
--
ALTER TABLE `administrador_consultorio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auditorias_historia_base`
--
ALTER TABLE `auditorias_historia_base`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `auth_logs`
--
ALTER TABLE `auth_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `citas`
--
ALTER TABLE `citas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `ciudades`
--
ALTER TABLE `ciudades`
  MODIFY `id_ciudad` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `configuracion_global`
--
ALTER TABLE `configuracion_global`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `configuracion_reparto`
--
ALTER TABLE `configuracion_reparto`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `consultorios`
--
ALTER TABLE `consultorios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `datos_pago_medico`
--
ALTER TABLE `datos_pago_medico`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `especialidades`
--
ALTER TABLE `especialidades`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `especialidad_consultorio`
--
ALTER TABLE `especialidad_consultorio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `estados`
--
ALTER TABLE `estados`
  MODIFY `id_estado` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `evolucion_clinica`
--
ALTER TABLE `evolucion_clinica`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `facturas_pacientes`
--
ALTER TABLE `facturas_pacientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `factura_detalles`
--
ALTER TABLE `factura_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `factura_totales`
--
ALTER TABLE `factura_totales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `fecha_indisponible`
--
ALTER TABLE `fecha_indisponible`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `historial_password`
--
ALTER TABLE `historial_password`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `historia_clinica_base`
--
ALTER TABLE `historia_clinica_base`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `known_devices`
--
ALTER TABLE `known_devices`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `liquidaciones`
--
ALTER TABLE `liquidaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `liquidacion_detalles`
--
ALTER TABLE `liquidacion_detalles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `medicos`
--
ALTER TABLE `medicos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `medico_consultorio`
--
ALTER TABLE `medico_consultorio`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `medico_especialidad`
--
ALTER TABLE `medico_especialidad`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `metodo_pago`
--
ALTER TABLE `metodo_pago`
  MODIFY `id_metodo` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `municipios`
--
ALTER TABLE `municipios`
  MODIFY `id_municipio` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `notificaciones`
--
ALTER TABLE `notificaciones`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ordenes_medicas`
--
ALTER TABLE `ordenes_medicas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orden_examenes`
--
ALTER TABLE `orden_examenes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orden_imagenes`
--
ALTER TABLE `orden_imagenes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orden_medicamentos`
--
ALTER TABLE `orden_medicamentos`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orden_referencias`
--
ALTER TABLE `orden_referencias`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pacientes`
--
ALTER TABLE `pacientes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `pacientes_especiales`
--
ALTER TABLE `pacientes_especiales`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `parroquias`
--
ALTER TABLE `parroquias`
  MODIFY `id_parroquia` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preguntas_catalogo`
--
ALTER TABLE `preguntas_catalogo`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `read_audit_logs`
--
ALTER TABLE `read_audit_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `representantes`
--
ALTER TABLE `representantes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `representante_paciente_especial`
--
ALTER TABLE `representante_paciente_especial`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `solicitudes_historial`
--
ALTER TABLE `solicitudes_historial`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `solicitudes_orden`
--
ALTER TABLE `solicitudes_orden`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tasas_dolar`
--
ALTER TABLE `tasas_dolar`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `administradores`
--
ALTER TABLE `administradores`
  ADD CONSTRAINT `administradores_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL,
  ADD CONSTRAINT `administradores_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL,
  ADD CONSTRAINT `administradores_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `administradores_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL,
  ADD CONSTRAINT `administradores_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `administrador_consultorio`
--
ALTER TABLE `administrador_consultorio`
  ADD CONSTRAINT `administrador_consultorio_administrador_id_foreign` FOREIGN KEY (`administrador_id`) REFERENCES `administradores` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `administrador_consultorio_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `auditorias_historia_base`
--
ALTER TABLE `auditorias_historia_base`
  ADD CONSTRAINT `auditorias_historia_base_historia_clinica_base_id_foreign` FOREIGN KEY (`historia_clinica_base_id`) REFERENCES `historia_clinica_base` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `auditorias_historia_base_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`);

--
-- Constraints for table `citas`
--
ALTER TABLE `citas`
  ADD CONSTRAINT `citas_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_paciente_especial_id_foreign` FOREIGN KEY (`paciente_especial_id`) REFERENCES `pacientes_especiales` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `citas_representante_id_foreign` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `ciudades`
--
ALTER TABLE `ciudades`
  ADD CONSTRAINT `ciudades_id_estado_foreign` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `configuracion_reparto`
--
ALTER TABLE `configuracion_reparto`
  ADD CONSTRAINT `configuracion_reparto_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`),
  ADD CONSTRAINT `configuracion_reparto_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`);

--
-- Constraints for table `consultorios`
--
ALTER TABLE `consultorios`
  ADD CONSTRAINT `consultorios_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE RESTRICT,
  ADD CONSTRAINT `consultorios_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE RESTRICT,
  ADD CONSTRAINT `consultorios_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `consultorios_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL;

--
-- Constraints for table `datos_pago_medico`
--
ALTER TABLE `datos_pago_medico`
  ADD CONSTRAINT `datos_pago_medico_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `datos_pago_medico_metodo_pago_id_foreign` FOREIGN KEY (`metodo_pago_id`) REFERENCES `metodo_pago` (`id_metodo`) ON DELETE SET NULL;

--
-- Constraints for table `especialidad_consultorio`
--
ALTER TABLE `especialidad_consultorio`
  ADD CONSTRAINT `especialidad_consultorio_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `especialidad_consultorio_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `evolucion_clinica`
--
ALTER TABLE `evolucion_clinica`
  ADD CONSTRAINT `evolucion_clinica_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`),
  ADD CONSTRAINT `evolucion_clinica_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `evolucion_clinica_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Constraints for table `facturas_pacientes`
--
ALTER TABLE `facturas_pacientes`
  ADD CONSTRAINT `facturas_pacientes_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `facturas_pacientes_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `facturas_pacientes_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `facturas_pacientes_tasa_id_foreign` FOREIGN KEY (`tasa_id`) REFERENCES `tasas_dolar` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `factura_cabecera`
--
ALTER TABLE `factura_cabecera`
  ADD CONSTRAINT `factura_cabecera_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`),
  ADD CONSTRAINT `factura_cabecera_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `factura_cabecera_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `factura_cabecera_tasa_id_foreign` FOREIGN KEY (`tasa_id`) REFERENCES `tasas_dolar` (`id`);

--
-- Constraints for table `factura_detalles`
--
ALTER TABLE `factura_detalles`
  ADD CONSTRAINT `factura_detalles_cabecera_id_foreign` FOREIGN KEY (`cabecera_id`) REFERENCES `factura_cabecera` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `factura_totales`
--
ALTER TABLE `factura_totales`
  ADD CONSTRAINT `factura_totales_cabecera_id_foreign` FOREIGN KEY (`cabecera_id`) REFERENCES `factura_cabecera` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `fecha_indisponible`
--
ALTER TABLE `fecha_indisponible`
  ADD CONSTRAINT `fecha_indisponible_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`),
  ADD CONSTRAINT `fecha_indisponible_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`);

--
-- Constraints for table `historial_password`
--
ALTER TABLE `historial_password`
  ADD CONSTRAINT `historial_password_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `historia_clinica_base`
--
ALTER TABLE `historia_clinica_base`
  ADD CONSTRAINT `historia_clinica_base_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `known_devices`
--
ALTER TABLE `known_devices`
  ADD CONSTRAINT `known_devices_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `liquidacion_detalles`
--
ALTER TABLE `liquidacion_detalles`
  ADD CONSTRAINT `liquidacion_detalles_factura_total_id_foreign` FOREIGN KEY (`factura_total_id`) REFERENCES `factura_totales` (`id`),
  ADD CONSTRAINT `liquidacion_detalles_liquidacion_id_foreign` FOREIGN KEY (`liquidacion_id`) REFERENCES `liquidaciones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medicos`
--
ALTER TABLE `medicos`
  ADD CONSTRAINT `medicos_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicos_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicos_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicos_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL,
  ADD CONSTRAINT `medicos_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `medico_consultorio`
--
ALTER TABLE `medico_consultorio`
  ADD CONSTRAINT `medico_consultorio_consultorio_id_foreign` FOREIGN KEY (`consultorio_id`) REFERENCES `consultorios` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `medico_consultorio_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `medico_consultorio_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `medico_especialidad`
--
ALTER TABLE `medico_especialidad`
  ADD CONSTRAINT `medico_especialidad_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `medico_especialidad_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `municipios`
--
ALTER TABLE `municipios`
  ADD CONSTRAINT `municipios_id_estado_foreign` FOREIGN KEY (`id_estado`) REFERENCES `estados` (`id_estado`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `ordenes_medicas`
--
ALTER TABLE `ordenes_medicas`
  ADD CONSTRAINT `ordenes_medicas_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`),
  ADD CONSTRAINT `ordenes_medicas_especialidad_id_foreign` FOREIGN KEY (`especialidad_id`) REFERENCES `especialidades` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ordenes_medicas_medico_id_foreign` FOREIGN KEY (`medico_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `ordenes_medicas_paciente_especial_id_foreign` FOREIGN KEY (`paciente_especial_id`) REFERENCES `pacientes_especiales` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `ordenes_medicas_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`),
  ADD CONSTRAINT `ordenes_medicas_representante_id_foreign` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orden_examenes`
--
ALTER TABLE `orden_examenes`
  ADD CONSTRAINT `orden_examenes_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_medicas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orden_imagenes`
--
ALTER TABLE `orden_imagenes`
  ADD CONSTRAINT `orden_imagenes_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_medicas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orden_medicamentos`
--
ALTER TABLE `orden_medicamentos`
  ADD CONSTRAINT `orden_medicamentos_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_medicas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orden_referencias`
--
ALTER TABLE `orden_referencias`
  ADD CONSTRAINT `orden_referencias_medico_referido_id_foreign` FOREIGN KEY (`medico_referido_id`) REFERENCES `medicos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orden_referencias_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_medicas` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pacientes`
--
ALTER TABLE `pacientes`
  ADD CONSTRAINT `pacientes_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pacientes_especiales`
--
ALTER TABLE `pacientes_especiales`
  ADD CONSTRAINT `pacientes_especiales_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_especiales_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_especiales_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `pacientes_especiales_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `pacientes_especiales_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL;

--
-- Constraints for table `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_confirmado_por_foreign` FOREIGN KEY (`confirmado_por`) REFERENCES `administradores` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_id_factura_paciente_foreign` FOREIGN KEY (`id_factura_paciente`) REFERENCES `facturas_pacientes` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_id_metodo_foreign` FOREIGN KEY (`id_metodo`) REFERENCES `metodo_pago` (`id_metodo`) ON UPDATE CASCADE,
  ADD CONSTRAINT `pago_tasa_aplicada_id_foreign` FOREIGN KEY (`tasa_aplicada_id`) REFERENCES `tasas_dolar` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `parroquias`
--
ALTER TABLE `parroquias`
  ADD CONSTRAINT `parroquias_id_municipio_foreign` FOREIGN KEY (`id_municipio`) REFERENCES `municipios` (`id_municipio`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `representantes`
--
ALTER TABLE `representantes`
  ADD CONSTRAINT `representantes_ciudad_id_foreign` FOREIGN KEY (`ciudad_id`) REFERENCES `ciudades` (`id_ciudad`) ON DELETE SET NULL,
  ADD CONSTRAINT `representantes_estado_id_foreign` FOREIGN KEY (`estado_id`) REFERENCES `estados` (`id_estado`) ON DELETE SET NULL,
  ADD CONSTRAINT `representantes_municipio_id_foreign` FOREIGN KEY (`municipio_id`) REFERENCES `municipios` (`id_municipio`) ON DELETE SET NULL,
  ADD CONSTRAINT `representantes_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `representantes_parroquia_id_foreign` FOREIGN KEY (`parroquia_id`) REFERENCES `parroquias` (`id_parroquia`) ON DELETE SET NULL;

--
-- Constraints for table `representante_paciente_especial`
--
ALTER TABLE `representante_paciente_especial`
  ADD CONSTRAINT `representante_paciente_especial_paciente_especial_id_foreign` FOREIGN KEY (`paciente_especial_id`) REFERENCES `pacientes_especiales` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `representante_paciente_especial_representante_id_foreign` FOREIGN KEY (`representante_id`) REFERENCES `representantes` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `respuestas_seguridad`
--
ALTER TABLE `respuestas_seguridad`
  ADD CONSTRAINT `respuestas_seguridad_pregunta_id_foreign` FOREIGN KEY (`pregunta_id`) REFERENCES `preguntas_catalogo` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE,
  ADD CONSTRAINT `respuestas_seguridad_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `solicitudes_historial`
--
ALTER TABLE `solicitudes_historial`
  ADD CONSTRAINT `solicitudes_historial_cita_id_foreign` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`),
  ADD CONSTRAINT `solicitudes_historial_evolucion_id_foreign` FOREIGN KEY (`evolucion_id`) REFERENCES `evolucion_clinica` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitudes_historial_medico_propietario_id_foreign` FOREIGN KEY (`medico_propietario_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `solicitudes_historial_medico_solicitante_id_foreign` FOREIGN KEY (`medico_solicitante_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `solicitudes_historial_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Constraints for table `solicitudes_orden`
--
ALTER TABLE `solicitudes_orden`
  ADD CONSTRAINT `solicitudes_orden_medico_propietario_id_foreign` FOREIGN KEY (`medico_propietario_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `solicitudes_orden_medico_solicitante_id_foreign` FOREIGN KEY (`medico_solicitante_id`) REFERENCES `medicos` (`id`),
  ADD CONSTRAINT `solicitudes_orden_orden_id_foreign` FOREIGN KEY (`orden_id`) REFERENCES `ordenes_medicas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `solicitudes_orden_paciente_id_foreign` FOREIGN KEY (`paciente_id`) REFERENCES `pacientes` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_rol_id_foreign` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
