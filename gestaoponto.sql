-- phpMyAdmin SQL Dump
-- version 4.8.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 03-Maio-2024 às 12:04
-- Versão do servidor: 10.1.32-MariaDB
-- PHP Version: 5.6.36

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gestaoponto`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `ferias`
--

CREATE TABLE `ferias` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data_inicio` date DEFAULT NULL,
  `duracao` int(11) DEFAULT NULL,
  `adiantamento_13` enum('sim','nao') DEFAULT NULL,
  `dias_adicionais` enum('sim','nao') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `ferias`
--

INSERT INTO `ferias` (`id`, `id_usuario`, `data_inicio`, `duracao`, `adiantamento_13`, `dias_adicionais`) VALUES
(5, 6, '2024-06-20', 30, 'nao', 'nao'),
(6, 6, '2024-09-12', 27, 'sim', 'sim'),
(7, 6, '2024-11-01', 20, 'sim', 'sim');

-- --------------------------------------------------------

--
-- Estrutura da tabela `registros_ponto`
--

CREATE TABLE `registros_ponto` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `longitude` varchar(20) DEFAULT NULL,
  `latitude` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `registros_ponto`
--

INSERT INTO `registros_ponto` (`id`, `id_usuario`, `data`, `hora`, `longitude`, `latitude`) VALUES
(9, 6, '2023-12-20', '10:45:12', '-48.3932155', '-1.3513723'),
(10, 6, '2023-12-20', '11:29:25', '-48.3932157', '-1.3513761'),
(11, 6, '2023-12-20', '23:34:44', '-48.3932164', '-1.3513746'),
(17, 6, '2023-12-22', '16:35:29', '-48.3932144', '-1.3513789'),
(18, 6, '2023-12-22', '16:50:44', '-48.3932126', '-1.3513723'),
(19, 6, '2023-12-22', '20:03:10', '-48.3932052', '-1.3513766'),
(20, 6, '2023-12-23', '13:04:48', '-48.3932115', '-1.351376'),
(21, 6, '2023-12-23', '21:28:39', '-48.4018111', '-1.3549048'),
(23, 6, '2024-01-02', '22:31:46', '-48.4041311', '-1.3547683'),
(24, 6, '2024-02-01', '21:58:38', '-48.3931598', '-1.3514013'),
(25, 6, '2024-02-04', '21:42:44', '-48.3931558', '-1.3514008'),
(26, 6, '2024-02-04', '22:05:33', '-48.3931592', '-1.3513982'),
(27, 6, '2024-02-04', '23:18:51', '-48.3931557', '-1.3514021'),
(37, 6, '2023-12-24', '08:00:00', '', ''),
(38, 6, '2023-12-24', '12:00:00', '', ''),
(39, 6, '2023-12-24', '13:00:00', '', ''),
(40, 6, '2023-12-24', '17:00:00', '', ''),
(42, 6, '2024-02-05', '00:10:00', '', ''),
(43, 16, '2024-04-20', '17:34:34', '-48.393157', '-1.3514299'),
(45, 6, '2024-04-21', '13:58:47', '-48.3931635', '-1.3514237'),
(49, 16, '2024-04-21', '12:00:00', '', ''),
(50, 16, '2024-04-21', '16:00:00', '', ''),
(51, 16, '2024-04-21', '16:35:00', '', ''),
(52, 16, '2024-04-21', '17:00:00', '', '');

-- --------------------------------------------------------

--
-- Estrutura da tabela `solicitacaoabonos`
--

CREATE TABLE `solicitacaoabonos` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `batida1` time DEFAULT NULL,
  `batida2` time DEFAULT NULL,
  `batida3` time DEFAULT NULL,
  `batida4` time DEFAULT NULL,
  `batida5` time DEFAULT NULL,
  `batida6` time DEFAULT NULL,
  `justificativa` text,
  `dia` date DEFAULT NULL,
  `status` int(11) DEFAULT NULL COMMENT '1 - pendente, 2 - aprovado, 3 - reprovado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `solicitacaoabonos`
--

INSERT INTO `solicitacaoabonos` (`id`, `id_usuario`, `batida1`, `batida2`, `batida3`, `batida4`, `batida5`, `batida6`, `justificativa`, `dia`, `status`) VALUES
(7, 6, '09:26:13', '10:36:23', '12:22:10', '13:00:31', NULL, NULL, 'solicito correção por tal motivo', '2023-12-21', 3),
(8, 6, '09:26:13', '10:36:23', '12:22:10', '13:08:31', '14:00:13', NULL, 'solicito correção por tal motivo', '2023-12-21', 3),
(9, 6, '16:35:29', '17:50:00', NULL, NULL, NULL, NULL, 'pode aprovar?', '2023-12-22', 3),
(10, 6, '16:35:00', '16:01:44', '21:03:10', NULL, NULL, NULL, 'ajuste por favor', '2023-12-22', 3),
(11, 6, '08:00:00', '12:00:00', '13:00:00', '17:00:00', NULL, NULL, 'estava de ferias', '2023-12-24', 2),
(12, 6, '08:00:42', '12:00:00', NULL, NULL, NULL, NULL, 'oie', '2023-12-25', 1),
(13, 6, '22:00:46', NULL, NULL, NULL, NULL, NULL, 'aceite pfvr', '2024-01-02', 2),
(14, 6, '21:58:38', '22:00:00', '02:05:00', NULL, NULL, NULL, 'esqueci!', '2024-02-01', 1),
(15, 6, '21:42:44', '22:05:33', '23:18:51', '00:00:00', NULL, NULL, 'fim de expediente', '2024-02-04', 3),
(16, 6, '22:00:00', '00:30:00', NULL, NULL, NULL, NULL, 'correção de ponto', '2024-01-02', 1),
(17, 6, '00:10:00', NULL, NULL, NULL, NULL, NULL, 'corrigir pfvr', '2024-02-05', 2),
(18, 16, '17:34:34', '18:00:00', NULL, NULL, NULL, NULL, 'vc pode add?', '2024-04-20', 3),
(19, 16, '12:00:00', NULL, NULL, NULL, NULL, NULL, 'me atrasei por conta da chuva.', '2024-04-21', 2),
(20, 16, '12:00:00', '16:00:00', '16:35:00', '17:00:00', NULL, NULL, 'erro de banda larga e fjm de expediente adiatado', '2024-04-21', 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `senha` varchar(100) NOT NULL,
  `data_cadastro` date NOT NULL,
  `ativo` tinyint(1) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `primeiro_acesso` char(1) DEFAULT '1',
  `telefone` varchar(20) DEFAULT NULL,
  `perfil` int(11) DEFAULT '1' COMMENT '1 - usuario basico, 2 - usuario admin',
  `data_nascimento` date DEFAULT NULL,
  `setor` varchar(50) DEFAULT NULL,
  `funcao` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `login`, `senha`, `data_cadastro`, `ativo`, `email`, `cpf`, `nome`, `primeiro_acesso`, `telefone`, `perfil`, `data_nascimento`, `setor`, `funcao`) VALUES
(1, 'usuario1', '529440Lucas.', '2023-01-01', 1, 'antoniolucasmatos939@gmail.com', '527.192.272-34', 'Antonio Lucas Matos do Carmo', '1', '91981179398', 1, '0000-00-00', NULL, NULL),
(2, 'usuario2', 'senha456', '2023-01-02', 1, 'usuario2@email.com', '987.654.321-00', 'Ciclano Silva', '1', '987654321', 1, '0000-00-00', NULL, NULL),
(3, 'usuario3', 'senha789', '2023-01-03', 0, 'usuario3@email.com', '111.222.333-44', 'Beltrano Souza', '1', '111222333', 1, '0000-00-00', NULL, NULL),
(4, 'usuario4', 'senhaabc', '2023-01-04', 1, 'usuario4@email.com', '555.666.777-88', 'João da Silva', '1', '555666777', 1, '0000-00-00', NULL, NULL),
(5, '00000', 'senhaxyz', '2023-01-05', 0, 'usuario5@email.comm', '999.888.777-66', 'Antônio Lucas Matos do Carmo', '1', '999888777', 1, '0000-00-00', NULL, NULL),
(6, 'adm', '529440Lucas.', '2023-01-05', 1, 'zephyrshockware@gmail.com', '527.192.272-34', 'Antonio Lucas Matos do Carmo(adm)', '0', '(91) 9 8117 9398', 2, '2001-01-17', 'TI', 'ANALISTA PLENO'),
(16, '93002220', '529440Lucas.', '2023-12-24', 1, 'antoniolucasmatos939@gmail.com', '247.319.782-20', 'ANTONIO LUCAS MATOS', '0', '91981179398', 1, '2001-01-17', 'TI', 'ANALISTA');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ferias`
--
ALTER TABLE `ferias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `registros_ponto`
--
ALTER TABLE `registros_ponto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `solicitacaoabonos`
--
ALTER TABLE `solicitacaoabonos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ferias`
--
ALTER TABLE `ferias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `registros_ponto`
--
ALTER TABLE `registros_ponto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;

--
-- AUTO_INCREMENT for table `solicitacaoabonos`
--
ALTER TABLE `solicitacaoabonos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `ferias`
--
ALTER TABLE `ferias`
  ADD CONSTRAINT `ferias_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Limitadores para a tabela `registros_ponto`
--
ALTER TABLE `registros_ponto`
  ADD CONSTRAINT `registros_ponto_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Limitadores para a tabela `solicitacaoabonos`
--
ALTER TABLE `solicitacaoabonos`
  ADD CONSTRAINT `solicitacaoabonos_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
