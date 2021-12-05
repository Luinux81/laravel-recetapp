-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 05-12-2021 a las 22:51:42
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

INSERT INTO `recetas` (`id`, `user_id`, `cat_id`, `nombre`, `descripcion`, `raciones`, `tiempo`, `imagen`, `calorias`, `fat_total`, `fat_saturadas`, `fat_poliinsaturadas`, `fat_monoinsaturadas`, `fat_trans`, `colesterol`, `sodio`, `potasio`, `fibra`, `carb_total`, `carb_azucar`, `proteina`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Arroz blanco cocido', 'Arroz blanco cocido.', 4, '20 min', NULL, 500, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 10:46:41'),
(2, 1, NULL, 'Gazpacho (Thermomix)', 'Gazpacho clásico andaluz con Thermomix', 6, '15 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 11:02:29', '2021-12-05 11:02:29'),
(3, 1, 1, 'Salmorejo (Thermomix)', 'Salmorejo clásico andaluz con Thermomix', 6, '15 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 11:19:09', '2021-12-05 11:19:09'),
(4, 1, NULL, 'Tortilla de patatas', 'Con cebolla, que está más rica. Al microondas si tienes los recipientes adecuados.', 4, '25 min', '', 300, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2021-12-05 11:30:28', '2021-12-05 11:42:31');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
