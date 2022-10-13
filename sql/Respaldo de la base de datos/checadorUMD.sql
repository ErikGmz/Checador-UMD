-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2022 a las 23:20:56
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `checadorumd`
--
CREATE DATABASE IF NOT EXISTS `checadorumd` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `checadorumd`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `carrera`
--

INSERT INTO `carrera` (`ID`, `nombre`) VALUES
(10, 'Licenciatura en Letras Hispánicas'),
(12, 'Licenciatura en Artes Cinematográficas y Audiovisuales'),
(13, 'Licenciado en Sociología'),
(14, 'Licenciatura en Docencia de Francés y Español como Lenguas Extranjeras'),
(15, 'Licenciatura en Trabajo Social'),
(17, 'Licenciatura en Derecho'),
(18, 'Licenciatura en Comunicación e Información'),
(20, 'Licenciatura en Asesoría Psicopedagógica'),
(21, 'Arquitectura'),
(22, 'Ingeniería Civil'),
(23, 'Licenciatura en Urbanismo'),
(25, 'Licenciatura en Diseño de Moda en Indumentaria y Textiles'),
(27, 'Licenciatura en Diseño Gráfico'),
(28, 'Licenciatura en Diseño de Interiores'),
(29, 'Licenciatura en Diseño Industrial'),
(31, 'Médico Cirujano'),
(32, 'Médico Estomatólogo'),
(33, 'Médico Veterinario Zootecnista'),
(35, 'Licenciatura en Biología'),
(36, 'Licenciatura en Optometría'),
(38, 'Licenciatura en Enfermería'),
(39, 'Licenciatura en Nutrición'),
(40, 'Licenciatura en Cultura Física y Deporte'),
(41, 'Ingeniería en Agronomía'),
(44, 'Ingeniería en Energías Renovables'),
(45, 'Ingeniería en Diseño Mecánico'),
(46, 'Ingeniería Automotriz'),
(47, 'Ingeniería Biomédica'),
(48, 'Ingeniería en Robótica'),
(49, 'Ingeniería en Manufactura y Automatización Industrial'),
(51, 'Contador Público'),
(52, 'Licenciatura en Administración de Empresas'),
(53, 'Licenciatura en Comercio Internacional'),
(54, 'Licenciatura en Administración de la Producción y Servicios'),
(55, 'Licenciatura en Administración Financiera'),
(56, 'Licenciatura en Relaciones Industriales'),
(57, 'Licenciatura en Economía'),
(59, 'Licenciatura en Mercadotecnia'),
(60, 'Ingeniería en Bioquímica'),
(61, 'Ingeniería en Sistemas Computacionales'),
(62, 'Licenciatura en Matemáticas Aplicadas'),
(64, 'Químico Farmacéutico Biólogo'),
(66, 'Ingeniería en Computación Inteligente'),
(67, 'Ingeniería en Electrónica'),
(69, 'Ingeniero Industrial Estadístico'),
(70, 'Licenciatura en Historia'),
(71, 'Licenciatura en Psicología'),
(72, 'Licenciatura en Filosofía'),
(73, 'Licenciatura en Docencia del Idioma Inglés'),
(74, 'Licenciatura en Ciencias Políticas y Administración Pública'),
(77, 'Licenciatura en Gestión Turística'),
(78, 'Licenciatura en Estudios del Arte y Gestión Cultural'),
(79, 'Licenciatura en Música'),
(81, 'Licenciatura en Biotecnología'),
(84, 'Licenciatura en Administración y Gestión Fiscal de PyMEs'),
(85, 'Licenciatura en Logística Empresarial'),
(86, 'Licenciatura en Agronegocios'),
(87, 'Licenciatura en Comercio Electrónico'),
(88, 'Licenciatura en Informática y Tecnologías Computacionales'),
(92, 'Ingeniería en Alimentos'),
(93, 'Licenciatura en Comunicación Corporativa Estratégica'),
(94, 'Licenciatura en Actuación');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chequeo`
--

CREATE TABLE `chequeo` (
  `fecha_chequeo` date NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time DEFAULT NULL,
  `tiempo_total` time DEFAULT NULL,
  `bloqueo_registro` int(1) NOT NULL DEFAULT 0,
  `ID_colaborador` int(10) UNSIGNED NOT NULL
) ;

--
-- Disparadores `chequeo`
--
DELIMITER $$
CREATE TRIGGER `calculoHorasTotales` BEFORE INSERT ON `chequeo` FOR EACH ROW BEGIN
		IF NEW.hora_final IS NOT NULL THEN
			SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
		ELSE
			IF NEW.tiempo_total IS NOT NULL THEN
				SET NEW.hora_final = SEC_TO_TIME(TIME_TO_SEC(NEW.hora_inicial) + TIME_TO_SEC(NEW.tiempo_total));
			END IF;
		END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculoHorasTotalesActualizacion` BEFORE UPDATE ON `chequeo` FOR EACH ROW BEGIN
		IF NEW.hora_final IS NOT NULL THEN
			SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
		ELSE
			IF (NEW.tiempo_total IS NOT NULL AND NEW.tiempo_total <> OLD.tiempo_total) THEN
				SET NEW.hora_final = SEC_TO_TIME(TIME_TO_SEC(NEW.hora_inicial) + TIME_TO_SEC(NEW.tiempo_total));
			ELSE
				SET NEW.tiempo_total = NULL;
			END IF;
		END IF;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaborador`
--

CREATE TABLE `colaborador` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombres` varchar(100) NOT NULL,
  `apellido_paterno` varchar(45) NOT NULL,
  `apellido_materno` varchar(45) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `numero_retardos` int(11) NOT NULL DEFAULT 0,
  `numero_desbloqueos` int(11) NOT NULL DEFAULT 0,
  `ID_carrera` int(10) UNSIGNED NOT NULL,
  `ID_modalidad` int(10) UNSIGNED NOT NULL,
  `ID_horario` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Disparadores `colaborador`
--
DELIMITER $$
CREATE TRIGGER `eliminadoHorarioNoUtilizado` AFTER DELETE ON `colaborador` FOR EACH ROW BEGIN
		IF (SELECT COUNT(ID_horario) FROM `colaborador` WHERE ID_horario = OLD.ID_horario) <= 0 THEN		
			DELETE FROM `horario` WHERE (ID = OLD.ID_horario);
		END IF;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contingencia`
--

CREATE TABLE `contingencia` (
  `fecha` date NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time NOT NULL,
  `tiempo_total` time NOT NULL,
  `observaciones` text NOT NULL,
  `ID_colaborador` int(10) UNSIGNED NOT NULL
) ;

--
-- Disparadores `contingencia`
--
DELIMITER $$
CREATE TRIGGER `calculoHorasContingencia` BEFORE INSERT ON `contingencia` FOR EACH ROW BEGIN
		SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculoHorasContingenciaActualizacion` BEFORE UPDATE ON `contingencia` FOR EACH ROW BEGIN
		SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coordinador`
--

CREATE TABLE `coordinador` (
  `ID` int(10) UNSIGNED NOT NULL,
  `clave` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `coordinador`
--

INSERT INTO `coordinador` (`ID`, `clave`) VALUES
(141414, '63a9f0ea7bb98050796b649e85481845');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hora_inicial` time NOT NULL,
  `hora_final` time NOT NULL,
  `ID_turno` int(10) UNSIGNED NOT NULL
) ;

--
-- Disparadores `horario`
--
DELIMITER $$
CREATE TRIGGER `calculoTurnoHorario` BEFORE INSERT ON `horario` FOR EACH ROW BEGIN
		IF NEW.hora_inicial < "14:00:00" AND NEW.hora_final > "14:00:00" THEN
			SET NEW.ID_turno = 3;
		ELSE
			IF NEW.hora_inicial >= "14:00:00" AND NEW.hora_final <= "21:00:00" THEN
				SET NEW.ID_turno = 2;
			ELSE
				SET NEW.ID_turno = 1;
			END IF;
		END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculoTurnoHorarioActualizacion` BEFORE UPDATE ON `horario` FOR EACH ROW BEGIN
		IF NEW.hora_inicial < "14:00:00" AND NEW.hora_final > "14:00:00" THEN
			SET NEW.ID_turno = 3;
		ELSE
			IF NEW.hora_inicial >= "14:00:00" AND NEW.hora_final <= "21:00:00" THEN
				SET NEW.ID_turno = 2;
			ELSE
				SET NEW.ID_turno = 1;
			END IF;
		END IF;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `modalidad_colaborador`
--

CREATE TABLE `modalidad_colaborador` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `modalidad_colaborador`
--

INSERT INTO `modalidad_colaborador` (`ID`, `nombre`) VALUES
(1, 'Presencial'),
(2, 'Pasantía'),
(3, 'A distancia'),
(4, 'Mixta');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`ID`, `nombre`) VALUES
(1, 'Matutino'),
(2, 'Vespertino'),
(3, 'Mixto');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `carrera`
--
ALTER TABLE `carrera`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `chequeo`
--
ALTER TABLE `chequeo`
  ADD PRIMARY KEY (`fecha_chequeo`,`ID_colaborador`),
  ADD KEY `fk_Chequeo_Colaborador1_idx` (`ID_colaborador`);

--
-- Indices de la tabla `colaborador`
--
ALTER TABLE `colaborador`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Colaborador_Carrera_idx` (`ID_carrera`),
  ADD KEY `fk_Colaborador_Modalidad_Colaborador1_idx` (`ID_modalidad`),
  ADD KEY `fk_Colaborador_Horario1_idx` (`ID_horario`);

--
-- Indices de la tabla `contingencia`
--
ALTER TABLE `contingencia`
  ADD PRIMARY KEY (`fecha`,`ID_colaborador`),
  ADD KEY `fk_Contingencia_Colaborador1_idx` (`ID_colaborador`);

--
-- Indices de la tabla `coordinador`
--
ALTER TABLE `coordinador`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `horario`
--
ALTER TABLE `horario`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Horario_Turno1_idx` (`ID_turno`);

--
-- Indices de la tabla `modalidad_colaborador`
--
ALTER TABLE `modalidad_colaborador`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `turno`
--
ALTER TABLE `turno`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `carrera`
--
ALTER TABLE `carrera`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=95;

--
-- AUTO_INCREMENT de la tabla `colaborador`
--
ALTER TABLE `colaborador`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89999824;

--
-- AUTO_INCREMENT de la tabla `coordinador`
--
ALTER TABLE `coordinador`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141415;

--
-- AUTO_INCREMENT de la tabla `horario`
--
ALTER TABLE `horario`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `modalidad_colaborador`
--
ALTER TABLE `modalidad_colaborador`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `turno`
--
ALTER TABLE `turno`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `chequeo`
--
ALTER TABLE `chequeo`
  ADD CONSTRAINT `fk_Chequeo_Colaborador1` FOREIGN KEY (`ID_colaborador`) REFERENCES `colaborador` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `chequeo` 
  ADD CONSTRAINT `CK_Fecha` CHECK (`fecha_chequeo` BETWEEN "2021-01-01" AND "2030-12-31");

ALTER TABLE `chequeo` 
  ADD CONSTRAINT `CK_Chequeo_Horas` CHECK (`hora_inicial` < `hora_final`);

--
-- Filtros para la tabla `colaborador`
--
ALTER TABLE `colaborador`
  ADD CONSTRAINT `fk_Colaborador_Carrera` FOREIGN KEY (`ID_carrera`) REFERENCES `carrera` (`ID`),
  ADD CONSTRAINT `fk_Colaborador_Horario1` FOREIGN KEY (`ID_horario`) REFERENCES `horario` (`ID`),
  ADD CONSTRAINT `fk_Colaborador_Modalidad_Colaborador1` FOREIGN KEY (`ID_modalidad`) REFERENCES `modalidad_colaborador` (`ID`);

ALTER TABLE `colaborador` 
  ADD CONSTRAINT `CK_Fecha_Nacimiento` CHECK (`fecha_nacimiento` >= "1900-01-01");
--
-- Filtros para la tabla `contingencia`
--
ALTER TABLE `contingencia`
  ADD CONSTRAINT `fk_Contingencia_Colaborador1` FOREIGN KEY (`ID_colaborador`) REFERENCES `colaborador` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `contingencia` 
  ADD CONSTRAINT `CK_Fecha_Registro` CHECK (`fecha` BETWEEN "2021-01-01" AND "2030-12-31");

ALTER TABLE `contingencia` 
  ADD CONSTRAINT `CK_Contingencia_Horas` CHECK (`hora_inicial` < `hora_final`);
--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `fk_Horario_Turno1` FOREIGN KEY (`ID_turno`) REFERENCES `turno` (`ID`);

ALTER TABLE `horario` 
  ADD CONSTRAINT `CK_Horario_Horas` CHECK (`hora_inicial` < `hora_final` 
  AND `hora_inicial` BETWEEN "08:00:00" AND "21:00:00" AND `hora_final` BETWEEN "08:00:00" AND "21:00:00");
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
