-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-12-2021 a las 02:56:16
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
-- Volcado de datos para la tabla `pasos_receta`
--

INSERT INTO `pasos_receta` (`id`, `receta_id`, `orden`, `texto`, `media_assets`) VALUES
(1, 1, 1, 'Ponemos agua a hervir a fuego medio con un poco de aceite.', NULL),
(2, 1, 2, 'Al hervir el agua le echamos el arroz y cocemos durante 20 minutos.', NULL),
(6, 1, 3, 'Escurrir y servir.', NULL),
(7, 2, 1, 'Pelamos y troceamos los ingredientes.', NULL),
(8, 2, 2, 'Añadimos a la TMX todos los ingredientes excepto el agua.', NULL),
(9, 2, 3, 'Programamos 1 minuto y vamos subiendo la velocidad hasta 10.', NULL),
(10, 2, 4, 'Bajamos lo acumulado en el vaso, abrimos la cubeta y programamos velociadad 3. Vamos añadiendo el agua poco a poco.', NULL),
(11, 2, 5, 'Probar y corregir la sal. Conservar en frio.', NULL),
(12, 3, 1, 'Pelamos y troceamos los ingredientes.', NULL),
(13, 3, 2, 'Añadimos a la TMX todos los ingredientes excepto el agua.', NULL),
(14, 3, 3, 'Programamos 1 minuto y vamos subiendo la velocidad hasta 10.', NULL),
(15, 3, 4, 'Bajamos lo acumulado en el vaso, abrimos la cubeta y programamos velociadad 3. Vamos añadiendo el agua poco a poco.', NULL),
(16, 3, 5, 'Probar y corregir la sal. Conservar en frio.', NULL),
(17, 4, 1, 'Pelamos las patatas y la cebolla. Troceamos en láminas de medio dedo de grosor.', NULL),
(18, 4, 2, 'Rellenamos el recipiente con capas de patata y cebolla a las que vamos añadiendo aceite de oliva y sal.', NULL),
(19, 4, 3, 'Metemos el recipiente cerrado en el microondas y programamos 5 minutos a potencia máxima.', NULL),
(20, 4, 4, 'Removemos todo y añadimos un poco más de aceite si hace falta. Repetimos el paso anterior hasta completar 15 minutos en total.', NULL),
(21, 4, 5, 'Dejamos reposar y enfriar unos minutos las patatas, mientras tanto batimos los huevos en una fuente. Después mezclamos y añadimos una pizca de sal.', NULL),
(22, 4, 6, 'Calentamos aceite en una sartén a fuego medio-alto y echamos la mezcla. Movemos la sartén para que la mezcla cree una superficie plana.', NULL),
(23, 4, 7, 'Cocinar de 7-10 minutos y voltear, usando plato o sartén doble, cocinar otros 10 minutos.', NULL),
(24, 5, 1, 'Poner las lentejas en remojo 24 horas (2 horas si son lentejas pardinas)', NULL),
(25, 5, 2, 'Pelar y trocear todo.', NULL),
(26, 5, 3, 'Usaremos una olla rápida. Ponemos las lentejas al fondo, con las verduras y el pollo encima.', NULL),
(27, 5, 4, 'Poner agua hasta casi cubrir todo. Añadimos el aceite, la sal y las especias.', NULL),
(28, 5, 5, 'Ponemos la olla a fuego fuerte hasta que suba la presión. Mantenemos a fuego mínimo 25 minutos.', NULL),
(29, 6, 1, 'Pelar y trocear todo.', NULL),
(30, 6, 2, 'Poner en la Thermomix el aceite, cebolla, pimiento y zanahoria. 5seg Temp.0 Vel.5', NULL),
(31, 6, 3, 'Programar 10 min Temp.120 Vel.1', NULL),
(32, 6, 4, 'Poner en la Thermomix el caldo, el resto de las verduras, la sal y las especias. Programamos 25 min Temp.120 Vel.1', NULL),
(33, 6, 5, 'Pasar al gusto. (Aprox 1 min Vel.10 para conseguir textura de crema)', NULL),
(34, 7, 1, 'Poner la paisana en la thermomix y programar 5 seg Vel.4', NULL),
(35, 7, 2, 'Programar 8 min Temp.120 Vel.1', NULL),
(36, 7, 3, 'Poner en la thermomix el resto de verduras y programar 5 seg Vel.4', NULL),
(37, 7, 4, 'Poner el caldo en la thermomix y programar 25 min Temp.120  Vel.1', NULL),
(38, 7, 5, 'Poner especias y programar velocidad alta para pasar al gusto.', NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
