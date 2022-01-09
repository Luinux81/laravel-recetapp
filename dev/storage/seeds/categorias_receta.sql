-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 09-01-2022 a las 18:22:08
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
-- Volcado de datos para la tabla `categorias_receta`
--

INSERT INTO `categorias_receta` (`id`, `user_id`, `catParent_id`, `nombre`, `descripcion`, `publicado`, `deleted_at`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Sopas', 'Sopas', 1, NULL, NULL, NULL),
(2, 1, NULL, 'Arroces', 'Arroces', 1, NULL, NULL, NULL),
(3, 1, NULL, 'Carnes', 'Carnes', 1, NULL, NULL, NULL),
(4, 1, 3, 'Pollo', 'Pollo', 1, NULL, NULL, NULL),
(5, 1, 3, 'Cerdo', 'Cerdo', 1, NULL, NULL, NULL),
(6, 1, NULL, 'Pescados', 'Pescados', 1, NULL, NULL, NULL),
(7, 1, NULL, 'Postres', 'Postres', 1, NULL, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
