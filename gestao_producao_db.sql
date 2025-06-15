-- Definir o modo SQL para compatibilidade
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
-- Iniciar transação para segurança nas operações
START TRANSACTION;
-- Configurar fuso horário padrão
SET time_zone = "+00:00";

-- Criar o banco de dados
CREATE DATABASE gestao_producao_db;

-- Selecionar o banco de dados criado
USE `gestao_producao_db`;

-- Criação da tabela para registrar o histórico de produção
CREATE TABLE `historico_producao` (
  `id` int NOT NULL,
  `maquina_id` int NOT NULL,
  `produto_id` int NOT NULL,
  `data_inicio` datetime NOT NULL,
  `data_fim` datetime DEFAULT NULL,
  `quantidade_produzida` int NOT NULL,
  `tempo_duracao` int DEFAULT NULL
);

-- Criação da tabela de máquinas
CREATE TABLE `maquinas` (
  `id` int NOT NULL,
  `nome` varchar(100) NOT NULL,
  `produto_principal_id` int DEFAULT NULL,
  `quantidade_meta` int NOT NULL,
  `status` enum('Ociosa','Em Producao','Parada') DEFAULT 'Ociosa',
  `producao_atual` int DEFAULT '0',
  `tempo_producao_acumulado` int DEFAULT '0',
  `historico_ativo_id` int DEFAULT NULL
);

-- Inserir dados iniciais na tabela de máquinas
INSERT INTO `maquinas` (`id`, `nome`, `produto_principal_id`, `quantidade_meta`, `status`, `producao_atual`, `tempo_producao_acumulado`, `historico_ativo_id`) VALUES
(1, 'maquina 1', NULL, 1000, 'Ociosa', 0, 0, NULL);


-- Criação da tabela de produtos
CREATE TABLE `produtos` (
  `id` int NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `estoque` int DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- Inserir dados iniciais na tabela de produtos
INSERT INTO `produtos` (`id`, `nome`, `descricao`, `estoque`, `created_at`) VALUES
(1, 'Capinha de Celular', 'Capinha Sao paulo', 0, '2025-06-14 18:51:52'),
(2, 'Capinha iphone', 'Cor azul', 0, '2025-06-14 18:52:15'),
(3, 'placas de bateria', 'samsung', 0, '2025-06-14 19:10:59');
-- ... (outros inserts de produtos)


-- Criação da tabela de usuários
CREATE TABLE `usuarios` (
  `id` int NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
);

-- Inserir dados iniciais na tabela de usuários
INSERT INTO `usuarios` (`id`, `username`, `password`) VALUES
(1, 'admin', '$2y$10$Q4m16GVAoEmRkgsfs1FZzuWKUAfgVjbpEKvNOQgckT1TFlGrhOUCK'),
(3, 'outro_usuario', '$2y$10$iYSaD5N2FpFw6zffndpxb.V0paiaQeC7LI6.a.igVkebKfOkGDhQC');

-- Definir chaves primárias e índices para todas as tabelas

-- Para tabela historico_producao
ALTER TABLE `historico_producao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `maquina_id` (`maquina_id`),
  ADD KEY `produto_id` (`produto_id`);

-- Para tabela maquinas
ALTER TABLE `maquinas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nome` (`nome`),
  ADD KEY `produto_principal_id` (`produto_principal_id`),
  ADD KEY `fk_historico_ativo` (`historico_ativo_id`);

-- Para tabela produtos
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id`);

-- Para tabela usuarios
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);
  
  -- Configurar auto incremento para todas as tabelas

-- Para historico_producao
ALTER TABLE `historico_producao`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

-- Para maquinas
ALTER TABLE `maquinas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

-- Para produtos
ALTER TABLE `produtos`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

-- Para usuarios
ALTER TABLE `usuarios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
  
  -- Definir relacionamentos entre tabelas

-- Para historico_producao
ALTER TABLE `historico_producao`
  ADD CONSTRAINT `historico_producao_ibfk_1` FOREIGN KEY (`maquina_id`) REFERENCES `maquinas` (`id`),
  ADD CONSTRAINT `historico_producao_ibfk_2` FOREIGN KEY (`produto_id`) REFERENCES `produtos` (`id`);

-- Para maquinas
ALTER TABLE `maquinas`
  ADD CONSTRAINT `fk_historico_ativo` FOREIGN KEY (`historico_ativo_id`) REFERENCES `historico_producao` (`id`),
  ADD CONSTRAINT `maquinas_ibfk_1` FOREIGN KEY (`produto_principal_id`) REFERENCES `produtos` (`id`);

-- Finalizar transação
COMMIT;