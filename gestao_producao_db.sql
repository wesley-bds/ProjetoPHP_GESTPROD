-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 14/06/2025 às 22:59
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
-- Banco de dados: `gestao_producao_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico_producao`
--

CREATE TABLE `historico_producao` (
  `id` int(11) NOT NULL,
  `maquina_id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL,
  `quantidade_produzida` int(11) NOT NULL,
  `tempo_duracao` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `maquinas`
--

CREATE TABLE `maquinas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `produto_principal_id` int(11) DEFAULT NULL,
  `quantidade_meta` int(11) NOT NULL,
  `status` enum('Ociosa','Em Producao','Parada') DEFAULT 'Ociosa',
  `producao_atual` int(11) DEFAULT 0,
  `tempo_producao_acumulado` int(11) DEFAULT 0,
  `historico_ativo_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `maquinas`
--

INSERT INTO `maquinas` (`id`, `nome`, `produto_principal_id`, `quantidade_meta`, `status`, `producao_atual`, `tempo_producao_acumulado`, `historico_ativo_id`) VALUES
(1, 'maquina 1', NULL, 1000, 'Ociosa', 0, 0, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `estoque` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id`, `nome`, `descricao`, `estoque`, `created_at`) VALUES
(1, 'Capinha de Celular', 'Capinha Sao paulo', 0, '2025-06-14 18:51:52'),
(2, 'Capinha iphone', 'Cor azul', 0, '2025-06-14 18:52:15'),
(3, 'placas de bateria', 'samsung', 0, '2025-06-14 19:10:59'),
(4, 'aaaa', 'sdsff', 0, '2025-06-14 19:11:12'),
(5, 'Capinha iphone2', 'cor roxa', 0, '2025-06-14 19:23:14'),
(6, 'retet', 'ertrtretret', 0, '2025-06-14 19:23:39'),
(7, 'ertreyrey', 'ertetrete', 0, '2025-06-14 19:23:43'),
(8, 'rtertretre', 'ertertertret', 0, '2025-06-14 19:23:46'),
(9, 'ujyjhgfh', 'regdfg', 0, '2025-06-14 19:23:48'),
(10, 'regegergre', 'regergreg', 0, '2025-06-14 19:24:02'),
(11, 'regergergre', 'regergergerg', 0, '2025-06-14 19:24:08'),
(12, 'wefwe', 'wefwefwef', 0, '2025-06-14 19:31:40'),
(13, 'Capinha de Celular2', 'gamba', 0, '2025-06-14 19:42:49'),
(14, 'b', 'aa', 0, '2025-06-14 19:43:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Q4m16GVAoEmRkgsfs1FZzuWKUAfgVjbpEKvNOQgckT1TFlGrhOUCK'),
(3, 'outro_usuario', '$2y$10$iYSaD5N2FpFw6zffndpxb.V0paiaQeC7LI6.a.igVkebKfOkGDhQC');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `historico_producao`
--
ALTER TABLE `historico_producao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maquina_id` (`maquina_id`),
  ADD KEY `produto_id` (`produto_id`);

--
-- Índices de tabela `maquinas`
--
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `produto_principal_id` (`produto_principal_id`),
  ADD KEY `fk_historico_ativo` (`historico_ativo_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `historico_producao`
--
ALTER TABLE `historico_producao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `maquinas`
--
ALTER TABLE `maquinas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `historico_producao`
--
ALTER TABLE `historico_producao`
  ADD CONSTRAINT `historico_producao_ibfk_1` FOREIGN KEY (`maquina_id`) REFERENCES `maquinas` (`id`),
  ADD CONSTRAINT `historico_producao_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

--
-- Restrições para tabelas `maquinas`
--
ALTER TABLE `maquinas`
  ADD CONSTRAINT `fk_historico_ativo` FOREIGN KEY (`historico_ativo_id`) REFERENCES `historico_producao` (`id`),
  ADD CONSTRAINT `maquinas_ibfk_1` FOREIGN KEY (`produto_principal_id`) REFERENCES `produtos` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
