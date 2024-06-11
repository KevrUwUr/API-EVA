-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 14-10-2023 a las 16:56:08
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
-- Base de datos: `kpi`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_dias`
--

CREATE TABLE `tbl_dias` (
  `tbl_dias_ID` varchar(50) NOT NULL,
  `tbl_dias_FECHA` varchar(40) DEFAULT NULL,
  `tbl_dias_META_DIA` varchar(20) DEFAULT NULL,
  `tbl_dias_OBSERVACIONES` varchar(50) DEFAULT NULL,
  `tbl_dias_OBSERVACIONES_PERSONALES` varchar(20) DEFAULT NULL,
  `tbl_SEMANA` varchar(50) DEFAULT NULL,
  `tbl_persona_USUARIO_RED` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_dias`
--

INSERT INTO `tbl_dias` (`tbl_dias_ID`, `tbl_dias_FECHA`, `tbl_dias_META_DIA`, `tbl_dias_OBSERVACIONES`, `tbl_dias_OBSERVACIONES_PERSONALES`, `tbl_SEMANA`, `tbl_persona_USUARIO_RED`) VALUES
('30', 'Fri Oct 13 2023 12:23:37 GMT-0500 (hora ', 'viernes', 'problema de librs', '1', 'Semana-2', 'padiernamazo.5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_grupo`
--

CREATE TABLE `tbl_grupo` (
  `tbl_grupo_ID` int(11) NOT NULL,
  `tbl_grupo_AREA` varchar(50) DEFAULT NULL,
  `tbl_grupo_NOMBRES` varchar(50) DEFAULT NULL,
  `tbl_grupo_DESCRICION` varchar(50) DEFAULT NULL,
  `tbl_grupo_MANAGER` varchar(50) DEFAULT NULL,
  `tbl_grupo_CREAR_FECHA` varchar(50) DEFAULT NULL,
  `tbl_grupo_ESTADO` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_grupo`
--

INSERT INTO `tbl_grupo` (`tbl_grupo_ID`, `tbl_grupo_AREA`, `tbl_grupo_NOMBRES`, `tbl_grupo_DESCRICION`, `tbl_grupo_MANAGER`, `tbl_grupo_CREAR_FECHA`, `tbl_grupo_ESTADO`) VALUES
(5, 'Desarrollo', 'Desarrollo', 'programadores con experiencia en multiple leguaje', 'Luis Garcia', '2023-10-13 12:18:46', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_grupo_objectivo`
--

CREATE TABLE `tbl_grupo_objectivo` (
  `tbl_grupo_objectivo_ID` int(11) NOT NULL,
  `tbl_grupo_objectivos_NOMBRES` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_METRICA` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_PARAMETRO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO_SEMANA` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO_MESES` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_PROMEDIO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_ESTADO` tinyint(1) DEFAULT NULL,
  `tbl_grupo_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_grupo_objectivo_usuario`
--

CREATE TABLE `tbl_grupo_objectivo_usuario` (
  `tbl_grupo_objectivo_ID` int(11) NOT NULL,
  `tbl_grupo_objectivos_NOMBRES` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_METRICA` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_PARAMETRO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO_SEMANA` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_OBJETIVO_MESES` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_PROMEDIO` varchar(50) DEFAULT NULL,
  `tbl_grupo_objectivo_ESTADO` tinyint(1) DEFAULT NULL,
  `tbl_persona_USUARIO_RED` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_grupo_objectivo_usuario`
--

INSERT INTO `tbl_grupo_objectivo_usuario` (`tbl_grupo_objectivo_ID`, `tbl_grupo_objectivos_NOMBRES`, `tbl_grupo_objectivo_OBJETIVO`, `tbl_grupo_objectivo_METRICA`, `tbl_grupo_objectivo_PARAMETRO`, `tbl_grupo_objectivo_OBJETIVO_SEMANA`, `tbl_grupo_objectivo_OBJETIVO_MESES`, `tbl_grupo_objectivo_PROMEDIO`, `tbl_grupo_objectivo_ESTADO`, `tbl_persona_USUARIO_RED`) VALUES
(1, 'eva', 'terminar el proyeyo', 'calida', 'Tiempo', '20', '50', '100', 1, 'padiernamazo.5');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_persona`
--

CREATE TABLE `tbl_persona` (
  `tbl_persona_USUARIO_RED` varchar(50) NOT NULL,
  `tbl_persona_CARGO` varchar(50) DEFAULT NULL,
  `tbl_persona_CENTRO_COSTO` varchar(50) DEFAULT NULL,
  `tbl_persona_NOMBRE` varchar(50) DEFAULT NULL,
  `tbl_persona_APELLIDO` varchar(50) DEFAULT NULL,
  `tbl_persona_FECHA_NACIMIENTO` date DEFAULT NULL,
  `tbl_persona_NUM_DOCUMENTO` varchar(50) DEFAULT NULL,
  `tbl_persona_TELEFONO` varchar(50) DEFAULT NULL,
  `tbl_persona_CCMS_ID` varchar(50) DEFAULT NULL,
  `tbl_persona_USUARIO_CCMS` varchar(50) DEFAULT NULL,
  `tbl_persona_FECHA_INGRESO_TP` date DEFAULT NULL,
  `tbl_persona_FECHA_EGRESO_TP` date DEFAULT NULL,
  `tbl_persona_CORREO_CORPORATIVO` varchar(50) DEFAULT NULL,
  `tbl_persona_ESTDO_CIVIL` varchar(50) DEFAULT NULL,
  `tbl_persona_HIJO` varchar(50) DEFAULT NULL,
  `tbl_persona_DIRECCION` varchar(50) DEFAULT NULL,
  `tbl_persona_BARRIO` varchar(50) DEFAULT NULL,
  `tbl_persona_CIUDAD` varchar(50) DEFAULT NULL,
  `tbl_persona_NOMBRE_INSTITUCION` varchar(50) DEFAULT NULL,
  `tbl_persona_CARRERA` varchar(50) DEFAULT NULL,
  `tbl_persona_TIPO_CARRERA` varchar(50) DEFAULT NULL,
  `tbl_persona_NIVEL_CARRERA` varchar(50) DEFAULT NULL,
  `tbl_persona_CORREO_PERSONAL` varchar(50) DEFAULT NULL,
  `tbl_persona_PLAZA` varchar(50) DEFAULT NULL,
  `tbl_persona_GRUPO_SANGUINIO` varchar(50) DEFAULT NULL,
  `tbl_persona_AFP` varchar(50) DEFAULT NULL,
  `tbl_persona_EQUIPO_COMPUESTO` varchar(50) DEFAULT NULL,
  `tbl_persona_OBSERVACIONES` varchar(50) DEFAULT NULL,
  `tbl_persona_ESTADO` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_persona`
--

INSERT INTO `tbl_persona` (`tbl_persona_USUARIO_RED`, `tbl_persona_CARGO`, `tbl_persona_CENTRO_COSTO`, `tbl_persona_NOMBRE`, `tbl_persona_APELLIDO`, `tbl_persona_FECHA_NACIMIENTO`, `tbl_persona_NUM_DOCUMENTO`, `tbl_persona_TELEFONO`, `tbl_persona_CCMS_ID`, `tbl_persona_USUARIO_CCMS`, `tbl_persona_FECHA_INGRESO_TP`, `tbl_persona_FECHA_EGRESO_TP`, `tbl_persona_CORREO_CORPORATIVO`, `tbl_persona_ESTDO_CIVIL`, `tbl_persona_HIJO`, `tbl_persona_DIRECCION`, `tbl_persona_BARRIO`, `tbl_persona_CIUDAD`, `tbl_persona_NOMBRE_INSTITUCION`, `tbl_persona_CARRERA`, `tbl_persona_TIPO_CARRERA`, `tbl_persona_NIVEL_CARRERA`, `tbl_persona_CORREO_PERSONAL`, `tbl_persona_PLAZA`, `tbl_persona_GRUPO_SANGUINIO`, `tbl_persona_AFP`, `tbl_persona_EQUIPO_COMPUESTO`, `tbl_persona_OBSERVACIONES`, `tbl_persona_ESTADO`) VALUES
('acevedohenao.9', NULL, NULL, 'Cindy Camila', 'Acevedo Henao', NULL, '1023522367', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('admin@admin.com', 'Sin observaciones', 'Costo 123', 'Juan Andres', 'Peres Gutierez', '2023-09-23', '123456789', '', 'CCMS123', 'UsuarioCCMS123', '2023-09-23', '2023-09-23', 'juan.perez@empresa.com', 'Soltero', '2', '123 Calle Principal', '123 Calle Principal', 'Ciudad Principal', 'Universidad XYZ', 'Presencial', 'Presencial', NULL, 'juan.perez@gmail.com', 'Plaza 001', 'O+', 'AFP1', 'Equipo 1', 'Gerente', 1),
('Alvarezbenavides.7', NULL, NULL, 'Nestor Andres', 'Alvarez', NULL, '1110451197', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('alvarezchacon.10', NULL, NULL, 'Geraldine', 'Álvarez Chacón', NULL, '1007249080', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('cubilloscarreno.6', NULL, NULL, 'Julián Camilo', 'Cubillos Carreño', NULL, '1032490201', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('cuellarsanchez.8', NULL, NULL, 'Laura Camila', 'Cuellar Sánchez', NULL, '1026300202', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('gallegomunoz.9', NULL, NULL, 'Carolina', 'Gallego Muñoz', NULL, '1037665105', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('lozanorodriguez.30', NULL, NULL, 'David Eduardo', 'Lozano Rodriguez', NULL, '1002234563', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('Molinavasquez.13', NULL, NULL, 'Edwin Ernesto', 'Molina Vasquez', NULL, '1014195005', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('muneraramirez.6', NULL, NULL, 'Ana Sofía', 'Múnera Ramírez', NULL, '1025640399', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('ochoabedoya.6', NULL, NULL, 'Valentina', 'Ochoa Bedoya', NULL, '1216727827', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('padiernamazo.5', NULL, NULL, 'Vanessa', 'Padierna Mazo', NULL, '1000899120', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('pardocontreras.5', NULL, NULL, 'Andrea Giovanna', 'Pardo Contreras', NULL, '52530901', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('penagoscorrea.5', NULL, NULL, 'Paula', 'Penagos Correa', NULL, '1019136522', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('piedrahitah.5', NULL, NULL, 'Jhon Deyby', 'Piedrahita Higuita', NULL, '1011396579', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('vargassuarez.10', NULL, NULL, 'Angela Marcela', 'Vargas Suarez', NULL, '1012366427', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('velezr.9', NULL, NULL, 'Andrés Mauricio', 'Vélez Reales', NULL, '1143357275', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1),
('zambranonino.7', NULL, NULL, 'Erika Julieth', 'Zambrano Niño', NULL, '1023958781', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_persona_asinado_grupo`
--

CREATE TABLE `tbl_persona_asinado_grupo` (
  `ID` int(11) NOT NULL,
  `tbl_persona_USUARIO_RED` varchar(255) DEFAULT NULL,
  `tbl_grupo_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_persona_asinado_grupo`
--

INSERT INTO `tbl_persona_asinado_grupo` (`ID`, `tbl_persona_USUARIO_RED`, `tbl_grupo_ID`) VALUES
(1, 'padiernamazo.5', 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tbl_usuarios`
--

CREATE TABLE `tbl_usuarios` (
  `tbl_usuarios_USUARIO` varchar(50) NOT NULL,
  `tbl_usuarios_CLAVE` varchar(50) DEFAULT NULL,
  `tbl_usuarios_CARGO` varchar(50) DEFAULT 'T'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tbl_usuarios`
--

INSERT INTO `tbl_usuarios` (`tbl_usuarios_USUARIO`, `tbl_usuarios_CLAVE`, `tbl_usuarios_CARGO`) VALUES
('acevedohenao.9', '1023522367', 'T'),
('admin@admin.com', 'admin', 'ADMINISTRADOR'),
('Alvarezbenavides.7', '1110451197', 'T'),
('alvarezchacon.10', '1007249080', 'T'),
('cubilloscarreno.6', '1032490201', 'T'),
('cuellarsanchez.8', '1026300202', 'T'),
('gallegomunoz.9', '1037665105', 'T'),
('lozanorodriguez.30', '1002234563', 'T'),
('Molinavasquez.13', '1014195005', 'T'),
('muneraramirez.6', '1025640399', 'T'),
('ochoabedoya.6', '1216727827', 'T'),
('ortizmunoz.10', '1036671697', 'T'),
('padiernamazo.5', '1000899120', 'T'),
('pardocontreras.5', '52530901', 'T'),
('penagoscorrea.5', '1019136522', 'T'),
('piedrahitah.5', '1011396579', 'T'),
('vargassuarez.10', '1012366427', 'T'),
('velezr.9', '1143357275', 'T'),
('zambranonino.7', '1023958781', 'T');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `tbl_dias`
--
ALTER TABLE `tbl_dias`
  ADD PRIMARY KEY (`tbl_dias_ID`),
  ADD KEY `tbl_persona_USUARIO_RED` (`tbl_persona_USUARIO_RED`);

--
-- Indices de la tabla `tbl_grupo`
--
ALTER TABLE `tbl_grupo`
  ADD PRIMARY KEY (`tbl_grupo_ID`);

--
-- Indices de la tabla `tbl_grupo_objectivo`
--
ALTER TABLE `tbl_grupo_objectivo`
  ADD PRIMARY KEY (`tbl_grupo_objectivo_ID`),
  ADD KEY `tbl_grupo_ID` (`tbl_grupo_ID`);

--
-- Indices de la tabla `tbl_grupo_objectivo_usuario`
--
ALTER TABLE `tbl_grupo_objectivo_usuario`
  ADD PRIMARY KEY (`tbl_grupo_objectivo_ID`),
  ADD KEY `tbl_persona_USUARIO_RED` (`tbl_persona_USUARIO_RED`);

--
-- Indices de la tabla `tbl_persona`
--
ALTER TABLE `tbl_persona`
  ADD PRIMARY KEY (`tbl_persona_USUARIO_RED`);

--
-- Indices de la tabla `tbl_persona_asinado_grupo`
--
ALTER TABLE `tbl_persona_asinado_grupo`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `tbl_persona_USUARIO_RED` (`tbl_persona_USUARIO_RED`),
  ADD KEY `tbl_grupo_ID` (`tbl_grupo_ID`);

--
-- Indices de la tabla `tbl_usuarios`
--
ALTER TABLE `tbl_usuarios`
  ADD PRIMARY KEY (`tbl_usuarios_USUARIO`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `tbl_grupo`
--
ALTER TABLE `tbl_grupo`
  MODIFY `tbl_grupo_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `tbl_grupo_objectivo`
--
ALTER TABLE `tbl_grupo_objectivo`
  MODIFY `tbl_grupo_objectivo_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `tbl_grupo_objectivo_usuario`
--
ALTER TABLE `tbl_grupo_objectivo_usuario`
  MODIFY `tbl_grupo_objectivo_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tbl_persona_asinado_grupo`
--
ALTER TABLE `tbl_persona_asinado_grupo`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `tbl_dias`
--
ALTER TABLE `tbl_dias`
  ADD CONSTRAINT `tbl_dias_ibfk_1` FOREIGN KEY (`tbl_persona_USUARIO_RED`) REFERENCES `tbl_persona` (`tbl_persona_USUARIO_RED`);

--
-- Filtros para la tabla `tbl_grupo_objectivo`
--
ALTER TABLE `tbl_grupo_objectivo`
  ADD CONSTRAINT `tbl_grupo_objectivo_ibfk_1` FOREIGN KEY (`tbl_grupo_ID`) REFERENCES `tbl_grupo` (`tbl_grupo_ID`);

--
-- Filtros para la tabla `tbl_grupo_objectivo_usuario`
--
ALTER TABLE `tbl_grupo_objectivo_usuario`
  ADD CONSTRAINT `tbl_grupo_objectivo_usuario_ibfk_1` FOREIGN KEY (`tbl_persona_USUARIO_RED`) REFERENCES `tbl_persona` (`tbl_persona_USUARIO_RED`);

--
-- Filtros para la tabla `tbl_persona`
--
ALTER TABLE `tbl_persona`
  ADD CONSTRAINT `tbl_persona_ibfk_1` FOREIGN KEY (`tbl_persona_USUARIO_RED`) REFERENCES `tbl_usuarios` (`tbl_usuarios_USUARIO`);

--
-- Filtros para la tabla `tbl_persona_asinado_grupo`
--
ALTER TABLE `tbl_persona_asinado_grupo`
  ADD CONSTRAINT `tbl_persona_asinado_grupo_ibfk_2` FOREIGN KEY (`tbl_persona_USUARIO_RED`) REFERENCES `tbl_persona` (`tbl_persona_USUARIO_RED`),
  ADD CONSTRAINT `tbl_persona_asinado_grupo_ibfk_3` FOREIGN KEY (`tbl_grupo_ID`) REFERENCES `tbl_grupo` (`tbl_grupo_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
