-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 04-Fev-2025 às 12:56
-- Versão do servidor: 10.4.27-MariaDB
-- versão do PHP: 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_academia`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `aluno`
--

CREATE TABLE `aluno` (
  `aluno_cod` int(11) NOT NULL,
  `aluno_nome` varchar(155) NOT NULL,
  `aluno_cpf` varchar(50) NOT NULL,
  `aluno_endereco` varchar(255) NOT NULL,
  `aluno_telefone` varchar(50) NOT NULL,
  `aluno_senha` varchar(255) NOT NULL,
  `aluno_email` varchar(255) NOT NULL,
  `fk_usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `aluno`
--

INSERT INTO `aluno` (`aluno_cod`, `aluno_nome`, `aluno_cpf`, `aluno_endereco`, `aluno_telefone`, `aluno_senha`, `aluno_email`, `fk_usuario_id`) VALUES
(9, 'teste', '328.509.698-44', 'Rua do Pizzolli', '01847609775', '123', 'teste.com@gmail.com', 15),
(10, 'Lionel Messi', '308.509.387-22', 'Parque Agradável', '21999634795', 'MESSI', 'messi.et@gmail.com', 17),
(13, 'Henrique Martins', '123.456.789-00', 'Rua dos Bobos', '1299456-7891', '12345', 'henrique@gmail.com', 21),
(14, 'Newerton', '123.456.719-00', 'Rua dos Jogadores', '1299456-7812', 'newerton123', 'newerton@gmail.com', 27),
(15, 'Junior', '232.116.719-00', 'Rua das Andoras', '12 99439-1291', 'juninho123', 'junior@gmail.com', 28),
(16, 'Gustavo', '432.112.719-00', 'Rua dos Apaixonados', '12 99539-1291', 'gustavo321', 'gusta@gmail.com', 29);

-- --------------------------------------------------------

--
-- Estrutura da tabela `aula`
--

CREATE TABLE `aula` (
  `aula_cod` int(11) NOT NULL,
  `aula_tipo` enum('Yoga','Musculação','Crossfit','Zumba','Calistenia') NOT NULL,
  `aula_data` date NOT NULL,
  `fk_instrutor_cod` int(11) NOT NULL,
  `fk_aluno_cod` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `aula`
--

INSERT INTO `aula` (`aula_cod`, `aula_tipo`, `aula_data`, `fk_instrutor_cod`, `fk_aluno_cod`) VALUES
(4, 'Musculação', '2025-05-30', 13, 10),
(7, 'Calistenia', '2025-03-01', 15, 13),
(9, 'Crossfit', '2025-03-10', 16, 13),
(11, 'Calistenia', '2025-02-05', 15, 16),
(13, 'Calistenia', '2025-02-05', 15, 13);

-- --------------------------------------------------------

--
-- Estrutura da tabela `instrutor`
--

CREATE TABLE `instrutor` (
  `instrutor_cod` int(11) NOT NULL,
  `instrutor_nome` varchar(155) NOT NULL,
  `instrutor_especialidade` enum('Yoga','Musculação','Crossfit','Zumba','Calistenia') NOT NULL,
  `fk_usuario_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `instrutor`
--

INSERT INTO `instrutor` (`instrutor_cod`, `instrutor_nome`, `instrutor_especialidade`, `fk_usuario_id`) VALUES
(13, 'Ricardo Instrutor', 'Musculação', 19),
(15, 'Lara ', 'Calistenia', 22),
(16, 'Neilton', 'Crossfit', 23),
(17, 'Lourenço', 'Musculação', 24),
(18, 'Jonathan', 'Zumba', 25),
(19, 'Maria', 'Zumba', 26);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo_usuario` enum('Aluno','Instrutor') NOT NULL,
  `aluno_cod` int(11) DEFAULT NULL,
  `instrutor_cod` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `email`, `senha`, `tipo_usuario`, `aluno_cod`, `instrutor_cod`) VALUES
(15, 'teste.com@gmail.com', '123', 'Aluno', NULL, NULL),
(17, 'messi.et@gmail.com', 'MESSI', 'Aluno', NULL, NULL),
(19, 'ricardo.instrutor@gmail.com', 'RICARDO', 'Instrutor', NULL, 13),
(20, 'lucas@gmail.com', 'lucao123', 'Instrutor', NULL, NULL),
(21, 'henrique@gmail.com', '12345', 'Aluno', NULL, NULL),
(22, 'lara@gmail.com', '12345', 'Instrutor', NULL, NULL),
(23, 'neilton@gmail.com', 'NEILTON', 'Instrutor', NULL, NULL),
(24, 'lourenco@gmail.com', '12345', 'Instrutor', NULL, NULL),
(25, 'jonathan@gmail.com', '123', 'Instrutor', NULL, NULL),
(26, 'maria@gmail.com', '12345', 'Instrutor', NULL, NULL),
(27, 'newerton@gmail.com', 'newerton123', 'Aluno', NULL, NULL),
(28, 'junior@gmail.com', 'juninho123', 'Aluno', NULL, NULL),
(29, 'gusta@gmail.com', 'gustavo321', 'Aluno', NULL, NULL);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `aluno`
--
ALTER TABLE `aluno`
  ADD PRIMARY KEY (`aluno_cod`),
  ADD KEY `fk_usuario_aluno` (`fk_usuario_id`);

--
-- Índices para tabela `aula`
--
ALTER TABLE `aula`
  ADD PRIMARY KEY (`aula_cod`),
  ADD KEY `fk_instrutor_cod` (`fk_instrutor_cod`),
  ADD KEY `fk_aluno_cod` (`fk_aluno_cod`);

--
-- Índices para tabela `instrutor`
--
ALTER TABLE `instrutor`
  ADD PRIMARY KEY (`instrutor_cod`),
  ADD KEY `fk_usuario_id` (`fk_usuario_id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD KEY `aluno_cod` (`aluno_cod`),
  ADD KEY `instrutor_cod` (`instrutor_cod`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno`
--
ALTER TABLE `aluno`
  MODIFY `aluno_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de tabela `aula`
--
ALTER TABLE `aula`
  MODIFY `aula_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `instrutor`
--
ALTER TABLE `instrutor`
  MODIFY `instrutor_cod` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `aula`
--
ALTER TABLE `aula`
  ADD CONSTRAINT `fk_aluno_cod` FOREIGN KEY (`fk_aluno_cod`) REFERENCES `aluno` (`aluno_cod`),
  ADD CONSTRAINT `fk_instrutor_cod` FOREIGN KEY (`fk_instrutor_cod`) REFERENCES `instrutor` (`instrutor_cod`);

--
-- Limitadores para a tabela `instrutor`
--
ALTER TABLE `instrutor`
  ADD CONSTRAINT `fk_usuario_id` FOREIGN KEY (`fk_usuario_id`) REFERENCES `usuarios` (`id_usuario`);

--
-- Limitadores para a tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`aluno_cod`) REFERENCES `aluno` (`aluno_cod`),
  ADD CONSTRAINT `usuarios_ibfk_2` FOREIGN KEY (`instrutor_cod`) REFERENCES `instrutor` (`instrutor_cod`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
