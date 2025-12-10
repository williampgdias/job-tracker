-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Tempo de geração: 10/12/2025 às 01:46
-- Versão do servidor: 8.0.44
-- Versão do PHP: 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `job_tracker`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `site` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pessoa_contato` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `vagas`
--

CREATE TABLE `vagas` (
  `id` int NOT NULL,
  `empresa_id` int NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `link_vaga` text COLLATE utf8mb4_general_ci,
  `salario_pretendido` decimal(10,2) DEFAULT NULL,
  `status` enum('Aplicado','Entrevista','Teste','Rejeitado','Oferta') COLLATE utf8mb4_general_ci DEFAULT 'Aplicado',
  `data_aplicacao` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `vagas`
--
ALTER TABLE `vagas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `vagas`
--
ALTER TABLE `vagas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `vagas`
--
ALTER TABLE `vagas`
  ADD CONSTRAINT `vagas_ibfk_1` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
