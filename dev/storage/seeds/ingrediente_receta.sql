-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2021 a las 01:31:19
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
-- Volcado de datos para la tabla `ingrediente_receta`
--

INSERT INTO `ingrediente_receta` (`ingrediente_id`, `receta_id`, `cantidad`, `unidad_medida`) VALUES
(1, 1, 1000, 'gr'),
(1, 2, 800, 'gr'),
(1, 3, 500, 'gr'),
(659, 3, 100, 'gr'),
(740, 1, 200, 'gr'),
(956, 1, 10, 'gr'),
(956, 2, 20, 'gr'),
(956, 3, 50, 'gr'),
(974, 1, 1, 'pizca'),
(974, 2, 5, 'gr'),
(1040, 2, 1, 'al gusto'),
(1079, 2, 2, 'dientes'),
(1079, 3, 2, 'dientes'),
(1202, 2, 75, 'gr'),
(1202, 3, 75, 'gr'),
(1204, 2, 75, 'gr'),
(1204, 3, 75, 'gr'),
(1218, 2, 1000, 'gr'),
(1218, 3, 1000, 'gr'),
(1228, 2, 100, 'gr'),
(1228, 3, 100, 'gr'),
(601, 4, 3, 'unidades'),
(956, 4, 40, 'gr'),
(974, 4, 1, 'al gusto'),
(1111, 4, 150, 'gr'),
(1189, 4, 500, 'gr'),
(206, 5, 300, 'gr'),
(445, 5, 400, 'gr'),
(956, 5, 25, 'gr'),
(974, 5, 1, 'al gusto'),
(975, 5, 1, 'al gusto'),
(1079, 5, 4, 'dientes'),
(1111, 5, 100, 'gr'),
(1186, 5, 175, 'gr'),
(1202, 5, 100, 'gr'),
(1204, 5, 100, 'gr'),
(1206, 5, 100, 'gr'),
(1228, 5, 100, 'gr');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
