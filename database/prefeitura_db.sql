-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 22/05/2026 às 16:56
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
-- Banco de dados: `prefeitura_db`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id`, `nome`, `descricao`) VALUES
(1, 'Iluminação Pública', 'Postes apagados, lâmpadas queimadas, falta de iluminação'),
(2, 'Pavimentação', 'Buracos, calçadas danificadas, asfalto irregular'),
(3, 'Coleta de Lixo', 'Falta de coleta, lixo acumulado, entulho'),
(4, 'Áreas Verdes', 'Manutenção de parques, corte de grama, poda de árvores'),
(5, 'Saneamento Básico', 'Esgoto, abastecimento de água, bueiros entupidos'),
(6, 'Saúde', 'Postos de saúde, medicamentos, atendimento'),
(7, 'Educação', 'Escolas, creches, transporte escolar'),
(8, 'Trânsito', 'Sinalização, semáforos, faixas de pedestre');

-- --------------------------------------------------------

--
-- Estrutura para tabela `chamados`
--

CREATE TABLE `chamados` (
  `id` int(11) NOT NULL,
  `titulo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `localizacao` varchar(255) DEFAULT NULL,
  `data_abertura` datetime DEFAULT current_timestamp(),
  `data_fechamento` datetime DEFAULT NULL,
  `usuario_id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `status_id` int(11) NOT NULL DEFAULT 1,
  `orgao_id` int(11) DEFAULT NULL,
  `empresa_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `chamados`
--

INSERT INTO `chamados` (`id`, `titulo`, `descricao`, `localizacao`, `data_abertura`, `data_fechamento`, `usuario_id`, `categoria_id`, `status_id`, `orgao_id`, `empresa_id`) VALUES
(1, 'Buraco na Rua das Flores', 'Há um buraco grande no meio da rua, prejudicando o trânsito e colocando veículos em risco.', 'Rua das Flores, nº 250, Centro', '2026-04-29 16:07:18', '2024-11-20 14:00:00', 2, 2, 4, 1, NULL),
(2, 'Poste apagado na Av. Brasil', 'O poste da esquina com a Rua Minas Gerais está apagado há mais de uma semana, deixando a região sem iluminação.', 'Av. Brasil, esquina com Rua Minas Gerais', '2026-04-29 16:07:18', '2024-11-22 10:00:00', 2, 1, 4, 1, NULL),
(3, 'Lixo acumulado no Parque Central', 'Há muito lixo acumulado próximo às lixeiras do parque central, causando mau cheiro e atraindo animais.', 'Parque Municipal Central', '2026-04-29 16:07:18', '2024-11-25 16:00:00', 2, 3, 4, 7, NULL),
(7, 'teste teste', 'teste teste teste', 'teste teste teste teste', '2026-05-02 17:02:35', '2026-05-02 17:04:41', 4, 6, 4, 4, NULL),
(8, 'Minecraft', 'zelda vai pro passado', 'link to the past', '2026-05-03 12:32:12', '2026-05-03 12:45:28', 5, 1, 4, 7, NULL),
(10, 'sadfasd', 'sdfasdfasd', 'asdfasdf', '2026-05-07 13:34:42', NULL, 6, 7, 1, NULL, NULL),
(12, 'Queria que plantassem uma horta comunitária aqui', 'Planta umas plantinhas aqui pra nós prefeito', 'Rua das Flores, 123 - Centro', '2026-05-09 15:46:11', '2026-05-21 11:53:43', 6, 4, 4, 2, 2),
(13, 'Quero que transformem a rua em mão única', 'Com a mão dupla, os carros estão obstruindo a passagem dos moradores e gerando problemas', 'Ruas c - Funcionarios', '2026-05-21 11:42:41', NULL, 6, 5, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Estrutura para tabela `empresas`
--

CREATE TABLE `empresas` (
  `id` int(11) NOT NULL,
  `nome` varchar(150) NOT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `responsavel` varchar(150) DEFAULT NULL,
  `area_atuacao` varchar(150) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `empresas`
--

INSERT INTO `empresas` (`id`, `nome`, `cnpj`, `email`, `telefone`, `responsavel`, `area_atuacao`, `ativo`, `criado_em`) VALUES
(1, 'Luz Urbana Manutencao Ltda', '12.345.678/0001-90', 'contato@luzurbana.com', '(11) 4000-1000', 'Carlos Mendes', 'Iluminacao Publica', 1, '2026-05-21 10:28:08'),
(2, 'Via Forte Pavimentacao', '23.456.789/0001-01', 'atendimento@viaforte.com', '(11) 4000-2000', 'Marina Costa', 'Pavimentacao', 1, '2026-05-21 10:28:08'),
(3, 'EcoLimpa Servicos Urbanos', '34.567.890/0001-12', 'contato@ecolimpa.com', '(11) 4000-3000', 'Rafael Almeida', 'Coleta de Lixo e Limpeza Urbana', 1, '2026-05-21 10:28:08');

-- --------------------------------------------------------

--
-- Estrutura para tabela `feedbacks`
--

CREATE TABLE `feedbacks` (
  `id` int(11) NOT NULL,
  `mensagem` text NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `usuario_id` int(11) NOT NULL,
  `chamado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `feedbacks`
--

INSERT INTO `feedbacks` (`id`, `mensagem`, `data`, `usuario_id`, `chamado_id`) VALUES
(1, 'Prezado cidadão, informamos que o buraco na Rua das Flores foi reparado com asfalto novo. A via está liberada e em boas condições. Agradecemos seu contato!', '2024-11-20 15:00:00', 1, 1),
(2, 'O poste foi substituído e a iluminação foi restabelecida. Agradecemos a denúncia e continuamos à disposição!', '2024-11-22 11:00:00', 1, 2),
(3, 'O parque foi limpo e novas lixeiras foram instaladas. Contamos com a colaboração de todos para manter o espaço limpo!', '2024-11-25 17:00:00', 1, 3),
(5, 'Tudo resolvido paizão', '2026-05-02 17:04:41', 1, 7),
(6, 'Tudo resolvido paizão', '2026-05-03 12:45:28', 1, 8),
(7, 'feedback de teste', '2026-05-21 11:53:43', 1, 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `historico`
--

CREATE TABLE `historico` (
  `id` int(11) NOT NULL,
  `data` datetime DEFAULT current_timestamp(),
  `tipo_acao` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `chamado_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `historico`
--

INSERT INTO `historico` (`id`, `data`, `tipo_acao`, `descricao`, `usuario_id`, `chamado_id`) VALUES
(1, '2024-11-18 09:00:00', 'Abertura', 'Chamado aberto pelo cidadão.', 2, 1),
(2, '2024-11-18 14:00:00', 'Atribuição', 'Chamado atribuído à Secretaria de Obras.', 1, 1),
(3, '2024-11-20 14:00:00', 'Resolução', 'Chamado marcado como resolvido.', 1, 1),
(4, '2024-11-19 10:00:00', 'Abertura', 'Chamado aberto pelo cidadão.', 2, 2),
(5, '2024-11-19 15:00:00', 'Atribuição', 'Chamado atribuído à Secretaria de Obras.', 1, 2),
(6, '2024-11-22 10:00:00', 'Resolução', 'Chamado marcado como resolvido.', 1, 2),
(7, '2024-11-20 08:00:00', 'Abertura', 'Chamado aberto pelo cidadão.', 2, 3),
(8, '2024-11-20 11:00:00', 'Atribuição', 'Chamado atribuído à Secretaria de Serviços Urbanos.', 1, 3),
(9, '2024-11-25 16:00:00', 'Resolução', 'Chamado marcado como resolvido.', 1, 3),
(16, '2026-05-02 17:02:35', 'Abertura', 'Chamado aberto pelo cidadão via portal.', 4, 7),
(17, '2026-05-02 17:03:14', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Saúde.', 1, 7),
(18, '2026-05-02 17:04:02', 'Status', 'Status alterado para: Aberto.', 1, 7),
(19, '2026-05-02 17:04:26', 'Status', 'Status alterado para: Em Andamento.', 1, 7),
(20, '2026-05-02 17:04:41', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 7),
(21, '2026-05-03 12:32:12', 'Abertura', 'Chamado aberto pelo cidadão.', 5, 8),
(22, '2026-05-03 12:45:13', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Serviços Urbanos.', 1, 8),
(23, '2026-05-03 12:45:28', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 8),
(27, '2026-05-07 13:34:42', 'Abertura', 'Chamado aberto pelo cidadão.', 6, 10),
(29, '2026-05-09 15:46:11', 'Abertura', 'Chamado aberto pelo cidadão.', 6, 12),
(30, '2026-05-09 15:47:18', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Meio Ambiente.', 1, 12),
(31, '2026-05-21 11:42:41', 'Abertura', 'Chamado aberto pelo cidadão.', 6, 13),
(32, '2026-05-21 11:52:57', 'Status', 'Status alterado para: Em Andamento.', 1, 12),
(33, '2026-05-21 11:53:13', 'Edicao', 'Dados do chamado atualizados pelo administrador.', 1, 12),
(34, '2026-05-21 11:53:19', 'Edicao', 'Dados do chamado atualizados pelo administrador.', 1, 12),
(35, '2026-05-21 11:53:31', 'Empresa', 'Chamado atribuido a empresa parceira: Via Forte Pavimentacao.', 1, 12),
(36, '2026-05-21 11:53:43', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 12);

-- --------------------------------------------------------

--
-- Estrutura para tabela `imagens`
--

CREATE TABLE `imagens` (
  `id` int(11) NOT NULL,
  `chamado_id` int(11) NOT NULL,
  `tipo` enum('cidadao','admin') NOT NULL,
  `caminho` varchar(500) NOT NULL,
  `nome_original` varchar(255) NOT NULL,
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `imagens`
--

INSERT INTO `imagens` (`id`, `chamado_id`, `tipo`, `caminho`, `nome_original`, `criado_em`) VALUES
(1, 8, 'cidadao', 'img_8_cidadao_69f76a7c73899.png', 'MERfinal.png', '2026-05-03 12:32:12'),
(3, 10, 'cidadao', 'img_10_cidadao_69fcbf2286577.png', 'MERfinal.png', '2026-05-07 13:34:42'),
(5, 12, 'cidadao', 'img_12_cidadao_69ff80f33f824.jpg', 'workalovepfp.jpg', '2026-05-09 15:46:11');

-- --------------------------------------------------------

--
-- Estrutura para tabela `orgaos`
--

CREATE TABLE `orgaos` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `orgaos`
--

INSERT INTO `orgaos` (`id`, `nome`, `descricao`) VALUES
(1, 'Secretaria de Obras', 'Responsável por obras e manutenção da infraestrutura urbana'),
(2, 'Secretaria de Meio Ambiente', 'Responsável por parques, áreas verdes e meio ambiente'),
(3, 'SAAE', 'Serviço Autônomo de Água e Esgoto'),
(4, 'Secretaria de Saúde', 'Responsável pela saúde pública municipal'),
(5, 'Secretaria de Educação', 'Responsável pela rede municipal de ensino'),
(6, 'DETRAN Municipal', 'Responsável pelo trânsito e mobilidade urbana'),
(7, 'Secretaria de Serviços Urbanos', 'Responsável pela limpeza urbana e coleta de lixo');

-- --------------------------------------------------------

--
-- Estrutura para tabela `status`
--

CREATE TABLE `status` (
  `id` int(11) NOT NULL,
  `nome` varchar(50) NOT NULL,
  `ordem` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `status`
--

INSERT INTO `status` (`id`, `nome`, `ordem`) VALUES
(1, 'Aberto', 1),
(2, 'Em Análise', 2),
(3, 'Em Andamento', 3),
(4, 'Resolvido', 4),
(5, 'Encerrado', 5);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `cpf` varchar(14) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('cidadao','admin') NOT NULL DEFAULT 'cidadao',
  `criado_em` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `telefone`, `cpf`, `senha`, `tipo`, `criado_em`) VALUES
(1, 'Administrador', 'admin@prefeitura.gov.br', NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-04-29 16:07:18'),
(2, 'João Silva', 'joao@email.com', NULL, NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-04-29 16:07:18'),
(3, 'mais teste', 'teste@teste.com', NULL, NULL, '$2y$10$DXDxkSm2bALF2gyVouSrvOAeHb6pUOv4O2qTC2Bfh4sTeFLDUIIxa', 'cidadao', '2026-05-02 16:52:59'),
(4, 'teste 2', 'teste3@email.com', NULL, NULL, '$2y$10$Tzer1LiTGxAWwXECe1a.ueAnAluM689S.rYCjDbAU0oTXITIN6.r6', 'cidadao', '2026-05-02 17:02:35'),
(5, 'teste da silva', 'testesilva@email.com', NULL, '12345678900', '$2y$10$4I2vBgIbaLp97o9ysguvVOUptjkjRrfjAvo729b5OdMbvRi24pcr2', 'cidadao', '2026-05-03 12:31:26'),
(6, 'mais teste', 'maisteste@teste.com', NULL, '98765432100', '$2y$10$2VczI1OiamM.DjFZR0VZAO/.vhUDoo2e5Rh4cni0LJeJmMOeswO9O', 'cidadao', '2026-05-07 13:29:27'),
(7, 'flies past', 'emailteste@email.com', '1212345678', '01234567899', '$2y$10$.yZ2wFL3dA8TiK28SpR7s.g/p4iYQQt863lxjdm7MSdBj8sK08rQq', 'cidadao', '2026-05-21 11:39:57');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `chamados`
--
ALTER TABLE `chamados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `categoria_id` (`categoria_id`),
  ADD KEY `status_id` (`status_id`),
  ADD KEY `orgao_id` (`orgao_id`),
  ADD KEY `empresa_id` (`empresa_id`);

--
-- Índices de tabela `empresas`
--
ALTER TABLE `empresas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`);

--
-- Índices de tabela `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `chamado_id` (`chamado_id`);

--
-- Índices de tabela `historico`
--
ALTER TABLE `historico`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `chamado_id` (`chamado_id`);

--
-- Índices de tabela `imagens`
--
ALTER TABLE `imagens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `chamado_id` (`chamado_id`);

--
-- Índices de tabela `orgaos`
--
ALTER TABLE `orgaos`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `status`
--
ALTER TABLE `status`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `cpf` (`cpf`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `chamados`
--
ALTER TABLE `chamados`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `empresas`
--
ALTER TABLE `empresas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `feedbacks`
--
ALTER TABLE `feedbacks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `orgaos`
--
ALTER TABLE `orgaos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `chamados`
--
ALTER TABLE `chamados`
  ADD CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chamados_ibfk_2` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`),
  ADD CONSTRAINT `chamados_ibfk_3` FOREIGN KEY (`status_id`) REFERENCES `status` (`id`),
  ADD CONSTRAINT `chamados_ibfk_4` FOREIGN KEY (`orgao_id`) REFERENCES `orgaos` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `chamados_ibfk_5` FOREIGN KEY (`empresa_id`) REFERENCES `empresas` (`id`) ON DELETE SET NULL;

--
-- Restrições para tabelas `feedbacks`
--
ALTER TABLE `feedbacks`
  ADD CONSTRAINT `feedbacks_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `feedbacks_ibfk_2` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `historico`
--
ALTER TABLE `historico`
  ADD CONSTRAINT `historico_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `historico_ibfk_2` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `imagens`
--
ALTER TABLE `imagens`
  ADD CONSTRAINT `imagens_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
