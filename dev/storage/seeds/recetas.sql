-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-01-2022 a las 16:03:39
-- Versión del servidor: 10.4.21-MariaDB
-- Versión de PHP: 8.0.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `laravel_recetapp`
--

--
-- Volcado de datos para la tabla `recetas`
--

INSERT INTO `recetas` (`id`, `user_id`, `cat_id`, `nombre`, `descripcion`, `raciones`, `tiempo`, `imagen`, `calorias`, `fat_total`, `fat_saturadas`, `fat_poliinsaturadas`, `fat_monoinsaturadas`, `fat_trans`, `colesterol`, `sodio`, `potasio`, `fibra`, `carb_total`, `carb_azucar`, `proteina`, `publicado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Arroz blanco cocido', 'Arroz blanco cocido.', 4, '20 min', NULL, 500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, '2021-12-05 10:46:41'),
(2, 1, NULL, 'Gazpacho (Thermomix)', 'Gazpacho clásico andaluz con Thermomix', 6, '15 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-12-05 11:02:29', '2021-12-05 11:02:29'),
(3, 1, 1, 'Salmorejo (Thermomix)', 'Salmorejo clásico andaluz con Thermomix', 6, '15 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-12-05 11:19:09', '2021-12-05 11:19:09'),
(4, NULL, NULL, 'Tortilla de patatas', 'Con cebolla, que está más rica. Al microondas si tienes los recipientes adecuados.', 4, '25 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-12-05 12:30:28', '2021-12-05 12:42:31'),
(5, NULL, NULL, 'Lentejas con verduras y pollo', 'Además de los ingredientes hay que añadir 1 hoja de laurel, comino al gusto y avecrem (opcional).', 4, '30 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-12-11 23:08:32', '2021-12-11 23:52:36'),
(6, NULL, 1, 'Puré de verduras (Thermomix)', 'Puré de verduras (Calabaza, puerro, nabo, zanahoria, cebolla, pimiento) con Themomix', 4, '30 min', NULL, 200, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, '2021-12-27 18:28:40', '2021-12-27 18:28:40');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
