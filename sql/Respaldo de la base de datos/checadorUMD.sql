-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-12-2022 a las 06:28:54
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
CREATE DATABASE IF NOT EXISTS `checadorumd` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish_ci;
USE `checadorumd`;

DELIMITER $$
--
-- Procedimientos
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `corregir_enumeracion_chequeos` (IN `fecha` DATE, IN `ID` INT(10))   BEGIN
        SET @numero_chequeo := 0;
        UPDATE chequeo SET numero_chequeo = @numero_chequeo := @numero_chequeo + 1 WHERE 
        fecha_chequeo = fecha AND ID_colaborador = ID;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_chequeo` (IN `fecha` DATE, IN `ID` INT(10), IN `numero` INT(11))   BEGIN
        SELECT hora_final, hora_inicial FROM chequeo WHERE fecha_chequeo = fecha
        AND ID_colaborador = ID AND numero_chequeo = numero LIMIT 1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_chequeo_anterior` (IN `fecha` DATE, IN `ID` INT(10), IN `numero` INT(11))   BEGIN
        SELECT hora_final, hora_inicial FROM chequeo WHERE fecha_chequeo = fecha
        AND ID_colaborador = ID AND numero_chequeo = numero_chequeo_anterior(fecha, ID, numero) LIMIT 1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_chequeo_posterior` (IN `fecha` DATE, IN `ID` INT(10), IN `numero` INT(11))   BEGIN
        SELECT hora_final, hora_inicial FROM chequeo WHERE fecha_chequeo = fecha
        AND ID_colaborador = ID AND numero_chequeo = numero_chequeo_posterior(fecha, ID, numero) LIMIT 1;
    END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `obtener_ultimo_chequeo` (IN `fecha` DATE, IN `ID` INT(10))   BEGIN
        SELECT numero_chequeo, hora_final, hora_inicial FROM chequeo WHERE fecha_chequeo = fecha
        AND ID_colaborador = ID AND numero_chequeo = (SELECT MAX(numero_chequeo) FROM chequeo WHERE 
        fecha_chequeo = fecha AND ID_colaborador = ID) LIMIT 1;
    END$$

--
-- Funciones
--
CREATE DEFINER=`root`@`localhost` FUNCTION `cantidad_chequeos` (`fecha` DATE, `ID` INT(10)) RETURNS INT(11)  BEGIN
        DECLARE valor INT;

        SELECT COUNT(*) INTO valor FROM chequeo WHERE fecha_chequeo = fecha AND ID_colaborador = ID LIMIT 1;

        RETURN valor;
    END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `numero_chequeo_anterior` (`fecha` DATE, `ID` INT(10), `numero` INT(11)) RETURNS INT(11)  BEGIN
        DECLARE valor INT;

        SELECT numero_chequeo INTO valor FROM chequeo WHERE fecha_chequeo = fecha AND ID_colaborador = ID
        AND numero_chequeo < numero ORDER BY numero_chequeo DESC LIMIT 1;

        RETURN valor;
    END$$

CREATE DEFINER=`root`@`localhost` FUNCTION `numero_chequeo_posterior` (`fecha` DATE, `ID` INT(10), `numero` INT(11)) RETURNS INT(11)  BEGIN
        DECLARE valor INT;

        SELECT numero_chequeo INTO valor FROM chequeo WHERE fecha_chequeo = fecha AND ID_colaborador = ID
        AND numero_chequeo > numero ORDER BY numero_chequeo ASC LIMIT 1;

        RETURN valor;
    END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_colaboradores_carreras`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_colaboradores_carreras` (
`nombre_carrera` text
,`cantidad_colaboradores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_colaboradores_modalidades`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_colaboradores_modalidades` (
`nombre_modalidad` varchar(45)
,`cantidad_colaboradores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_colaboradores_participaciones`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_colaboradores_participaciones` (
`nombre_participacion` varchar(45)
,`cantidad_colaboradores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_colaboradores_turnos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_colaboradores_turnos` (
`nombre_turno` varchar(45)
,`cantidad_colaboradores` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_contingencias_colaboradores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_contingencias_colaboradores` (
`nombre_colaborador` varchar(192)
,`cantidad_contingencias` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `cantidad_contingencias_fechas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `cantidad_contingencias_fechas` (
`fecha_contingencia` varchar(28)
,`cantidad_contingencias` bigint(21)
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carrera`
--

CREATE TABLE `carrera` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` tinytext CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=COMPACT;

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
  `fecha_chequeo` date NOT NULL CHECK (`fecha_chequeo` >= '2021-01-01'),
  `ID_colaborador` int(10) UNSIGNED NOT NULL,
  `numero_chequeo` int(11) NOT NULL DEFAULT 1,
  `hora_inicial` time NOT NULL CHECK (`hora_inicial` < `hora_final`),
  `hora_final` time DEFAULT NULL CHECK (`hora_inicial` < `hora_final`),
  `tiempo_total` time DEFAULT NULL,
  `bloqueo_registro` int(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Disparadores `chequeo`
--
DELIMITER $$
CREATE TRIGGER `calculoHorasTotalesYValidacionChequeo` BEFORE INSERT ON `chequeo` FOR EACH ROW BEGIN
		DECLARE done INT DEFAULT FALSE;
		DECLARE tiempo_inicial, tiempo_final TIME;
		DECLARE cursor_chequeos_anteriores CURSOR FOR SELECT hora_inicial, hora_final FROM chequeo 
		WHERE fecha_chequeo = NEW.fecha_chequeo AND 
		ID_colaborador = NEW.ID_colaborador AND numero_chequeo < NEW.numero_chequeo;

		DECLARE cursor_chequeos_posteriores CURSOR FOR SELECT hora_inicial, hora_final FROM chequeo 
		WHERE fecha_chequeo = NEW.fecha_chequeo AND 
		ID_colaborador = NEW.ID_colaborador AND numero_chequeo > NEW.numero_chequeo;
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		IF NEW.hora_final IS NOT NULL THEN
			IF NEW.hora_final < NEW.hora_inicial THEN
				SIGNAL SQLSTATE '45000' SET message_text = "La hora final no puede ser menor que la inicial.";
			END IF;

			SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
		ELSE
			IF NEW.tiempo_total IS NOT NULL THEN
				SET NEW.hora_final = SEC_TO_TIME(TIME_TO_SEC(NEW.hora_inicial) + TIME_TO_SEC(NEW.tiempo_total));
			END IF;
		END IF;

		OPEN cursor_chequeos_anteriores;
		read_loop: LOOP
			FETCH cursor_chequeos_anteriores INTO tiempo_inicial, tiempo_final;
			IF done THEN
				LEAVE read_loop;
			END IF;

			IF (NEW.hora_inicial < tiempo_inicial OR NEW.hora_inicial < tiempo_final 
			OR NEW.hora_final < tiempo_inicial OR NEW.hora_final < tiempo_final) THEN
				SIGNAL SQLSTATE '45000' SET message_text = "El horario especificado presenta conflictos con chequeos anteriores de la fecha correspondiente.";
			END IF;
		END LOOP;
		CLOSE cursor_chequeos_anteriores;

		IF NEW.hora_final IS NOT NULL THEN
			SET done = FALSE;
			OPEN cursor_chequeos_posteriores;
			read_loop: LOOP
				FETCH cursor_chequeos_posteriores INTO tiempo_inicial, tiempo_final;
				IF done THEN
					LEAVE read_loop;
				END IF;

				IF (NEW.hora_inicial > tiempo_inicial OR NEW.hora_inicial > tiempo_final 
				OR NEW.hora_final > tiempo_inicial OR NEW.hora_final > tiempo_final) THEN
					SIGNAL SQLSTATE '45000' SET message_text = "El horario especificado presenta conflictos con chequeos posteriores de la fecha correspondiente.";
				END IF;
			END LOOP;
			CLOSE cursor_chequeos_posteriores;
		END IF;
	END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `calculoHorasTotalesYValidacionChequeoActualizacion` BEFORE UPDATE ON `chequeo` FOR EACH ROW BEGIN
		DECLARE done INT DEFAULT FALSE;
		DECLARE tiempo_inicial, tiempo_final TIME;
		DECLARE cursor_chequeos_anteriores CURSOR FOR SELECT hora_inicial, hora_final FROM chequeo 
		WHERE fecha_chequeo = NEW.fecha_chequeo AND 
		ID_colaborador = NEW.ID_colaborador AND numero_chequeo < NEW.numero_chequeo 
		AND NOT (fecha_chequeo = OLD.fecha_chequeo AND ID_colaborador = OLD.ID_colaborador AND numero_chequeo = OLD.numero_chequeo);

		DECLARE cursor_chequeos_posteriores CURSOR FOR SELECT hora_inicial, hora_final FROM chequeo 
		WHERE fecha_chequeo = NEW.fecha_chequeo AND 
		ID_colaborador = NEW.ID_colaborador AND numero_chequeo > NEW.numero_chequeo 
		AND NOT (fecha_chequeo = OLD.fecha_chequeo AND ID_colaborador = OLD.ID_colaborador AND numero_chequeo = OLD.numero_chequeo);
		DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

		IF NEW.hora_final IS NOT NULL THEN
			IF NEW.hora_final < NEW.hora_inicial THEN
				SIGNAL SQLSTATE '45000' SET message_text = "La hora final no puede ser menor que la inicial.";
			END IF;
               
			SET NEW.tiempo_total = TIMEDIFF(NEW.hora_final, NEW.hora_inicial);
		ELSE
			IF (NEW.tiempo_total IS NOT NULL AND NEW.tiempo_total <> OLD.tiempo_total) THEN
				SET NEW.hora_final = SEC_TO_TIME(TIME_TO_SEC(NEW.hora_inicial) + TIME_TO_SEC(NEW.tiempo_total));
			ELSE
				SET NEW.tiempo_total = NULL;
			END IF;
		END IF;

		OPEN cursor_chequeos_anteriores;
		read_loop: LOOP
			FETCH cursor_chequeos_anteriores INTO tiempo_inicial, tiempo_final;
			IF done THEN
				LEAVE read_loop;
			END IF;

			IF (NEW.hora_inicial < tiempo_inicial OR NEW.hora_inicial < tiempo_final 
			OR NEW.hora_final < tiempo_inicial OR NEW.hora_final < tiempo_final) THEN
				SIGNAL SQLSTATE '45000' SET message_text = "El horario especificado presenta conflictos con chequeos anteriores de la fecha correspondiente.";
			END IF;
		END LOOP;
		CLOSE cursor_chequeos_anteriores;

		IF NEW.hora_final IS NOT NULL THEN
			SET done = FALSE;
			OPEN cursor_chequeos_posteriores;
			read_loop: LOOP
				FETCH cursor_chequeos_posteriores INTO tiempo_inicial, tiempo_final;
				IF done THEN
					LEAVE read_loop;
				END IF;

				IF (NEW.hora_inicial > tiempo_inicial OR NEW.hora_inicial > tiempo_final 
				OR NEW.hora_final > tiempo_inicial OR NEW.hora_final > tiempo_final) THEN
					SIGNAL SQLSTATE '45000' SET message_text = "El horario especificado presenta conflictos con chequeos posteriores de la fecha correspondiente.";
				END IF;
			END LOOP;
			CLOSE cursor_chequeos_posteriores;
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
  `nombres` varchar(100) CHARACTER SET utf8 NOT NULL,
  `apellido_paterno` varchar(45) CHARACTER SET utf8 NOT NULL,
  `apellido_materno` varchar(45) CHARACTER SET utf8 DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL CHECK (`fecha_nacimiento` >= '1900-01-01'),
  `numero_retardos` int(11) NOT NULL DEFAULT 0,
  `numero_desbloqueos` int(11) NOT NULL DEFAULT 0,
  `ID_carrera` int(10) UNSIGNED NOT NULL DEFAULT 10,
  `ID_modalidad` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `ID_participacion` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `ID_horario` int(10) UNSIGNED NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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
DELIMITER $$
CREATE TRIGGER `eliminadoHorarioNoUtilizadoActualizacion` AFTER UPDATE ON `colaborador` FOR EACH ROW BEGIN
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
  `fecha` date NOT NULL CHECK (`fecha` >= '2021-01-01'),
  `hora_inicial` time NOT NULL CHECK (`hora_inicial` < `hora_final`),
  `hora_final` time NOT NULL CHECK (`hora_inicial` < `hora_final`),
  `tiempo_total` time NOT NULL,
  `observaciones` text CHARACTER SET utf8 NOT NULL,
  `ID_colaborador` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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

--
-- Disparadores `coordinador`
--
DELIMITER $$
CREATE TRIGGER `verificarEliminadoCoordinador` BEFORE DELETE ON `coordinador` FOR EACH ROW BEGIN
		IF (OLD.ID = "141414") THEN
            SIGNAL SQLSTATE '45000' SET message_text = "No se puede eliminar el coordinador predeterminado.";
        END IF;
	END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `desglose_chequeos`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `desglose_chequeos` (
`fecha_chequeo` date
,`hora_inicial` time
,`hora_final` time
,`tiempo_total` time
,`tiempo_contingencia` time
,`bloqueo_registro` int(11)
,`ID_colaborador` int(10) unsigned
,`numero_chequeo` int(11)
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `desglose_colaboradores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `desglose_colaboradores` (
`ID` int(10) unsigned
,`nombre_completo` varchar(192)
,`fecha_nacimiento` date
,`numero_retardos` int(11)
,`numero_desbloqueos` int(11)
,`nombre_carrera` tinytext
,`nombre_modalidad` varchar(45)
,`hora_inicial` time
,`hora_final` time
,`nombre_participacion` varchar(45)
,`nombre_turno` varchar(45)
,`ID_carrera` int(10) unsigned
,`ID_modalidad` int(10) unsigned
,`ID_participacion` int(10) unsigned
,`ID_turno` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `desglose_contingencias`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `desglose_contingencias` (
`ID` int(10) unsigned
,`nombres` varchar(100)
,`apellido_paterno` varchar(45)
,`apellido_materno` varchar(45)
,`fecha` date
,`hora_inicial` time
,`hora_final` time
,`tiempo_total` time
,`observaciones` text
);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `desglose_separado_colaboradores`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `desglose_separado_colaboradores` (
`primer_nombre` varchar(300)
,`segundo_nombre` varchar(300)
,`apellido_paterno` varchar(45)
,`apellido_materno` varchar(45)
,`ID_carrera` int(10) unsigned
,`ID_modalidad` int(10) unsigned
,`hora_inicial` varchar(10)
,`hora_final` varchar(10)
,`numero_retardos` int(11)
,`numero_desbloqueos` int(11)
,`fecha_nacimiento` date
,`ID` int(10) unsigned
,`ID_participacion` int(10) unsigned
);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horario`
--

CREATE TABLE `horario` (
  `ID` int(10) UNSIGNED NOT NULL,
  `hora_inicial` time NOT NULL CHECK (`hora_inicial` < `hora_final` and `hora_inicial` between '08:00:00' and '21:00:00' and `hora_final` between '08:00:00' and '21:00:00'),
  `hora_final` time NOT NULL CHECK (`hora_inicial` < `hora_final` and `hora_inicial` between '08:00:00' and '21:00:00' and `hora_final` between '08:00:00' and '21:00:00'),
  `ID_turno` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

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
-- Estructura de tabla para la tabla `modalidad`
--

CREATE TABLE `modalidad` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(45) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `modalidad`
--

INSERT INTO `modalidad` (`ID`, `nombre`) VALUES
(1, 'Beca'),
(2, 'Servicio social'),
(3, 'Prácticas profesionales'),
(4, 'Beca / Servicio social'),
(5, 'Beca / Prácticas profesionales'),
(6, 'Pasantía');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participacion`
--

CREATE TABLE `participacion` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(45) COLLATE utf8mb4_spanish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `participacion`
--

INSERT INTO `participacion` (`ID`, `nombre`) VALUES
(1, 'Presencial'),
(2, 'A distancia'),
(3, 'Mixta'),
(4, 'Otra');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `turno`
--

CREATE TABLE `turno` (
  `ID` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(45) CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci ROW_FORMAT=COMPACT;

--
-- Volcado de datos para la tabla `turno`
--

INSERT INTO `turno` (`ID`, `nombre`) VALUES
(1, 'Matutino'),
(2, 'Vespertino'),
(3, 'Mixto');

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_colaboradores_carreras`
--
DROP TABLE IF EXISTS `cantidad_colaboradores_carreras`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_colaboradores_carreras`  AS SELECT coalesce(`carrera`.`nombre`,'Todas las carreras registradas') AS `nombre_carrera`, count(0) AS `cantidad_colaboradores` FROM (`carrera` join `colaborador` on(`carrera`.`ID` = `colaborador`.`ID_carrera`)) GROUP BY `carrera`.`nombre` with rollup having `nombre_carrera` is not null  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_colaboradores_modalidades`
--
DROP TABLE IF EXISTS `cantidad_colaboradores_modalidades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_colaboradores_modalidades`  AS SELECT coalesce(`modalidad`.`nombre`,'Todas las modalidades') AS `nombre_modalidad`, count(`colaborador`.`ID`) AS `cantidad_colaboradores` FROM (`modalidad` left join `colaborador` on(`modalidad`.`ID` = `colaborador`.`ID_modalidad`)) GROUP BY `modalidad`.`nombre` with rollup  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_colaboradores_participaciones`
--
DROP TABLE IF EXISTS `cantidad_colaboradores_participaciones`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_colaboradores_participaciones`  AS SELECT coalesce(`participacion`.`nombre`,'Todos los tipos de participación') AS `nombre_participacion`, count(`colaborador`.`ID`) AS `cantidad_colaboradores` FROM (`participacion` left join `colaborador` on(`participacion`.`ID` = `colaborador`.`ID_participacion`)) GROUP BY `participacion`.`nombre` with rollup  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_colaboradores_turnos`
--
DROP TABLE IF EXISTS `cantidad_colaboradores_turnos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_colaboradores_turnos`  AS SELECT coalesce(`turno`.`nombre`,'Todos los turnos') AS `nombre_turno`, count(`colaborador`.`ID`) AS `cantidad_colaboradores` FROM ((`colaborador` left join `horario` on(`horario`.`ID` = `colaborador`.`ID_horario`)) join `turno` on(`turno`.`ID` = `horario`.`ID_turno`)) GROUP BY `turno`.`nombre` with rollup  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_contingencias_colaboradores`
--
DROP TABLE IF EXISTS `cantidad_contingencias_colaboradores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_contingencias_colaboradores`  AS SELECT coalesce(`desglose_colaboradores`.`nombre_completo`,'Todos los colaboradores registrados') AS `nombre_colaborador`, count(0) AS `cantidad_contingencias` FROM (`desglose_colaboradores` join `contingencia` on(`contingencia`.`ID_colaborador` = `desglose_colaboradores`.`ID`)) GROUP BY `desglose_colaboradores`.`nombre_completo` with rollup  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `cantidad_contingencias_fechas`
--
DROP TABLE IF EXISTS `cantidad_contingencias_fechas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `cantidad_contingencias_fechas`  AS SELECT coalesce(`desglose_contingencias`.`fecha`,'Todas las fechas registradas') AS `fecha_contingencia`, count(0) AS `cantidad_contingencias` FROM `desglose_contingencias` GROUP BY `desglose_contingencias`.`fecha` with rollup  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `desglose_chequeos`
--
DROP TABLE IF EXISTS `desglose_chequeos`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `desglose_chequeos`  AS SELECT `chequeo`.`fecha_chequeo` AS `fecha_chequeo`, `chequeo`.`hora_inicial` AS `hora_inicial`, `chequeo`.`hora_final` AS `hora_final`, `chequeo`.`tiempo_total` AS `tiempo_total`, `contingencia`.`tiempo_total` AS `tiempo_contingencia`, `chequeo`.`bloqueo_registro` AS `bloqueo_registro`, `chequeo`.`ID_colaborador` AS `ID_colaborador`, `chequeo`.`numero_chequeo` AS `numero_chequeo` FROM (`chequeo` left join `contingencia` on(`chequeo`.`fecha_chequeo` = `contingencia`.`fecha` and `chequeo`.`ID_colaborador` = `contingencia`.`ID_colaborador`)) union select `contingencia`.`fecha` AS `fecha`,`chequeo`.`hora_inicial` AS `hora_inicial`,`chequeo`.`hora_final` AS `hora_final`,`chequeo`.`tiempo_total` AS `tiempo_total`,`contingencia`.`tiempo_total` AS `tiempo_contingencia`,`chequeo`.`bloqueo_registro` AS `bloqueo_registro`,`contingencia`.`ID_colaborador` AS `ID_colaborador`,`chequeo`.`numero_chequeo` AS `numero_chequeo` from (`contingencia` left join `chequeo` on(`chequeo`.`fecha_chequeo` = `contingencia`.`fecha` and `chequeo`.`ID_colaborador` = `contingencia`.`ID_colaborador`))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `desglose_colaboradores`
--
DROP TABLE IF EXISTS `desglose_colaboradores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `desglose_colaboradores`  AS SELECT `colaborador`.`ID` AS `ID`, concat_ws(' ',`colaborador`.`nombres`,`colaborador`.`apellido_paterno`,`colaborador`.`apellido_materno`) AS `nombre_completo`, `colaborador`.`fecha_nacimiento` AS `fecha_nacimiento`, `colaborador`.`numero_retardos` AS `numero_retardos`, `colaborador`.`numero_desbloqueos` AS `numero_desbloqueos`, `carrera`.`nombre` AS `nombre_carrera`, `modalidad`.`nombre` AS `nombre_modalidad`, `horario`.`hora_inicial` AS `hora_inicial`, `horario`.`hora_final` AS `hora_final`, `participacion`.`nombre` AS `nombre_participacion`, `turno`.`nombre` AS `nombre_turno`, `carrera`.`ID` AS `ID_carrera`, `modalidad`.`ID` AS `ID_modalidad`, `participacion`.`ID` AS `ID_participacion`, `turno`.`ID` AS `ID_turno` FROM (((((`colaborador` join `carrera` on(`colaborador`.`ID_carrera` = `carrera`.`ID`)) join `modalidad` on(`colaborador`.`ID_modalidad` = `modalidad`.`ID`)) join `participacion` on(`colaborador`.`ID_participacion` = `participacion`.`ID`)) join `horario` on(`colaborador`.`ID_horario` = `horario`.`ID`)) join `turno` on(`horario`.`ID_turno` = `turno`.`ID`))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `desglose_contingencias`
--
DROP TABLE IF EXISTS `desglose_contingencias`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `desglose_contingencias`  AS SELECT `colaborador`.`ID` AS `ID`, `colaborador`.`nombres` AS `nombres`, `colaborador`.`apellido_paterno` AS `apellido_paterno`, `colaborador`.`apellido_materno` AS `apellido_materno`, `contingencia`.`fecha` AS `fecha`, `contingencia`.`hora_inicial` AS `hora_inicial`, `contingencia`.`hora_final` AS `hora_final`, `contingencia`.`tiempo_total` AS `tiempo_total`, `contingencia`.`observaciones` AS `observaciones` FROM (`contingencia` join `colaborador` on(`colaborador`.`ID` = `contingencia`.`ID_colaborador`))  ;

-- --------------------------------------------------------

--
-- Estructura para la vista `desglose_separado_colaboradores`
--
DROP TABLE IF EXISTS `desglose_separado_colaboradores`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `desglose_separado_colaboradores`  AS SELECT if(substr(`colaborador`.`nombres`,1,locate(' ',`colaborador`.`nombres`)) = '',trim(substr(`colaborador`.`nombres`,locate(' ',`colaborador`.`nombres`) + 1)),trim(substr(`colaborador`.`nombres`,1,locate(' ',`colaborador`.`nombres`)))) AS `primer_nombre`, if(substr(`colaborador`.`nombres`,1,locate(' ',`colaborador`.`nombres`)) <> '',trim(substr(`colaborador`.`nombres`,locate(' ',`colaborador`.`nombres`) + 1)),'') AS `segundo_nombre`, `colaborador`.`apellido_paterno` AS `apellido_paterno`, `colaborador`.`apellido_materno` AS `apellido_materno`, `colaborador`.`ID_carrera` AS `ID_carrera`, `colaborador`.`ID_modalidad` AS `ID_modalidad`, time_format(`horario`.`hora_inicial`,'%H:%i') AS `hora_inicial`, time_format(`horario`.`hora_final`,'%H:%i') AS `hora_final`, `colaborador`.`numero_retardos` AS `numero_retardos`, `colaborador`.`numero_desbloqueos` AS `numero_desbloqueos`, `colaborador`.`fecha_nacimiento` AS `fecha_nacimiento`, `colaborador`.`ID` AS `ID`, `colaborador`.`ID_participacion` AS `ID_participacion` FROM (`colaborador` join `horario` on(`colaborador`.`ID_horario` = `horario`.`ID`))  ;

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
  ADD PRIMARY KEY (`numero_chequeo`,`fecha_chequeo`,`ID_colaborador`),
  ADD KEY `indice_fecha_registro` (`fecha_chequeo`),
  ADD KEY `indice_ID_colaborador` (`ID_colaborador`),
  ADD KEY `indice_colaborador_fecha_chequeo` (`fecha_chequeo`,`ID_colaborador`);

--
-- Indices de la tabla `colaborador`
--
ALTER TABLE `colaborador`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `fk_Colaborador_Carrera_idx` (`ID_carrera`),
  ADD KEY `fk_Colaborador_Modalidad_Colaborador1_idx` (`ID_modalidad`),
  ADD KEY `fk_Colaborador_Horario1_idx` (`ID_horario`),
  ADD KEY `fk_Colaborador_Participacion_Colaborador1` (`ID_participacion`);

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
-- Indices de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `participacion`
--
ALTER TABLE `participacion`
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
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `modalidad`
--
ALTER TABLE `modalidad`
  MODIFY `ID` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `participacion`
--
ALTER TABLE `participacion`
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

--
-- Filtros para la tabla `colaborador`
--
ALTER TABLE `colaborador`
  ADD CONSTRAINT `fk_Colaborador_Carrera` FOREIGN KEY (`ID_carrera`) REFERENCES `carrera` (`ID`),
  ADD CONSTRAINT `fk_Colaborador_Horario1` FOREIGN KEY (`ID_horario`) REFERENCES `horario` (`ID`),
  ADD CONSTRAINT `fk_Colaborador_Modalidad_Colaborador1` FOREIGN KEY (`ID_modalidad`) REFERENCES `modalidad` (`ID`),
  ADD CONSTRAINT `fk_Colaborador_Participacion_Colaborador1` FOREIGN KEY (`ID_participacion`) REFERENCES `participacion` (`ID`);

--
-- Filtros para la tabla `contingencia`
--
ALTER TABLE `contingencia`
  ADD CONSTRAINT `fk_Contingencia_Colaborador1` FOREIGN KEY (`ID_colaborador`) REFERENCES `colaborador` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `horario`
--
ALTER TABLE `horario`
  ADD CONSTRAINT `fk_Horario_Turno1` FOREIGN KEY (`ID_turno`) REFERENCES `turno` (`ID`);
COMMIT;

--
-- Secuencias
--
CREATE OR REPLACE SEQUENCE incremento_colaboradores
START WITH 100000 INCREMENT BY 1 MINVALUE = 100000;

CREATE OR REPLACE SEQUENCE incremento_administradores
START WITH 1000 INCREMENT BY 1 MINVALUE = 1000;

ALTER TABLE `colaborador` CHANGE `ID` `ID` INT(10) UNSIGNED NOT NULL 
DEFAULT (NEXT VALUE FOR incremento_colaboradores);

ALTER TABLE `coordinador` CHANGE `ID` `ID` INT(10) UNSIGNED NOT NULL 
DEFAULT (NEXT VALUE FOR incremento_administradores);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
