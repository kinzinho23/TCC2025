-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 06/03/2026 às 02:37
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `banco`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `materias`
--

CREATE TABLE `materias` (
  `idMateria` int(11) NOT NULL,
  `nomeMateria` varchar(50) NOT NULL,
  `codigoMateria` varchar(225) NOT NULL,
  `tipo` enum('obrigatoria','optativa','eletiva') NOT NULL,
  `cargaHoraria` int(225) NOT NULL,
  `detalhesMateria` varchar(225) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `stts` enum('ativa','inativa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `materias`
--

INSERT INTO `materias` (`idMateria`, `nomeMateria`, `codigoMateria`, `tipo`, `cargaHoraria`, `detalhesMateria`, `idUsuario`, `stts`) VALUES
(2, 'Matemática', 'MAT101', 'obrigatoria', 0, 'Estudo das relações numéricas.', 3, 'ativa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `salas`
--

CREATE TABLE `salas` (
  `idSala` int(11) NOT NULL,
  `nomeSala` varchar(225) NOT NULL,
  `capacidade` int(40) NOT NULL,
  `tipoSala` varchar(225) NOT NULL,
  `stts` enum('Manutenção','Em uso','Livre','Agendada') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nomeUsuario` varchar(225) NOT NULL,
  `identificador` int(15) NOT NULL,
  `senhaUsuario` varchar(225) NOT NULL,
  `tipoUsuario` enum('professor','aluno','coordenacao','admin') NOT NULL,
  `idSala` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nomeUsuario`, `identificador`, `senhaUsuario`, `tipoUsuario`, `idSala`) VALUES
(1, 'king', 123, '$2y$10$zpp6sUCul.KiDKZC/FBDD.rGMnIwec.0HEWBdV0MYi2J4sYioq9O.', 'admin', NULL),
(2, 'aluno', 1234, '$2y$10$FP/v8E036N0szUNodqvhEeax.6U5MArrLW5Mf779WKKf57ZG8VfGW', 'aluno', NULL),
(3, 'P.diddy', 321, '$2y$10$3u8f1sCxiaNHpXdgsriX9.5m1Bp0894z2/7MeLLuBBA16fI7IE7zO', 'professor', NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `materias`
--
ALTER TABLE `materias`
  ADD PRIMARY KEY (`idMateria`),
  ADD KEY `idUsuario` (`idUsuario`);

--
-- Índices de tabela `salas`
--
ALTER TABLE `salas`
  ADD PRIMARY KEY (`idSala`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD KEY `idSala` (`idSala`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `materias`
--
ALTER TABLE `materias`
  MODIFY `idMateria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `salas`
--
ALTER TABLE `salas`
  MODIFY `idSala` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `materias`
--
ALTER TABLE `materias`
  ADD CONSTRAINT `materias_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`);

--
-- Restrições para tabelas `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `usuario_ibfk_1` FOREIGN KEY (`idSala`) REFERENCES `salas` (`idSala`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
