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

CREATE DATABASE IF NOT EXISTS prefeitura_db;
USE prefeitura_db;


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
(1, 'Buraco na Rua das Flores', 'Há um buraco grande próximo ao cruzamento, prejudicando o trânsito e oferecendo risco para veículos e pedestres.', 'Rua das Flores, nº 250 - Centro', '2026-05-20 09:15:00', '2026-05-22 16:30:00', 2, 2, 4, 1, 2),
(2, 'Poste apagado na Avenida Brasil', 'O poste da esquina está apagado há vários dias, deixando a via escura durante a noite.', 'Avenida Brasil, esquina com Rua Minas Gerais - Jardim América', '2026-05-21 18:40:00', '2026-05-23 10:20:00', 3, 1, 4, 1, 1),
(3, 'Lixo acumulado no Parque Central', 'As lixeiras do parque estão cheias e há descarte irregular de sacos de lixo próximo à área de caminhada.', 'Parque Municipal Central', '2026-05-24 08:10:00', '2026-05-25 15:45:00', 4, 3, 4, 7, 3),
(8, 'Bueiro entupido após chuva', 'Após a última chuva, o bueiro da rua ficou entupido e a água está acumulando na calçada.', 'Rua das Acácias, nº 118 - Vila Nova', '2026-05-27 14:05:00', NULL, 5, 5, 3, 3, NULL),
(10, 'Solicitação de poda de árvore', 'Uma árvore grande está com galhos encostando na fiação e dificultando a passagem de pedestres.', 'Rua Ipê Amarelo, nº 72 - Jardim das Flores', '2026-05-28 11:25:00', NULL, 6, 4, 2, 2, NULL),
(11, 'Faixa de pedestre apagada', 'A faixa de pedestre em frente à escola está quase invisível, dificultando a travessia dos alunos.', 'Avenida Educação, nº 430 - Centro', '2026-05-29 07:50:00', NULL, 7, 8, 1, NULL, NULL),
(12, 'Horta comunitária no bairro', 'Moradores solicitam avaliação para implantação de uma horta comunitária em terreno público sem uso.', 'Rua das Flores, nº 123 - Centro', '2026-05-30 13:30:00', '2026-06-01 09:40:00', 6, 4, 4, 2, 2),
(13, 'Pedido de mão única na Rua C', 'A via está com fluxo intenso nos dois sentidos e veículos estacionados dificultam a passagem dos moradores.', 'Rua C - Bairro Funcionários', '2026-06-01 16:10:00', NULL, 3, 8, 2, 6, NULL);

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
(1, 'Luz Urbana Manutenção Ltda', '12.345.678/0001-90', 'contato@luzurbana.com.br', '(11) 4000-1000', 'Carlos Mendes', 'Iluminação Pública', 1, '2026-05-15 10:00:00'),
(2, 'Via Forte Pavimentação', '23.456.789/0001-01', 'atendimento@viaforte.com.br', '(11) 4000-2000', 'Marina Costa', 'Pavimentação e Obras Urbanas', 1, '2026-05-15 10:15:00'),
(3, 'EcoLimpa Serviços Urbanos', '34.567.890/0001-12', 'contato@ecolimpa.com.br', '(11) 4000-3000', 'Rafael Almeida', 'Coleta de Lixo e Limpeza Urbana', 1, '2026-05-15 10:30:00'),
(4, 'Verdejar Meio Ambiente', '45.678.901/0001-23', 'operacoes@verdejar.com.br', '(11) 4000-4000', 'Patrícia Ramos', 'Poda, Jardinagem e Áreas Verdes', 1, '2026-05-15 10:45:00');

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
(1, 'O reparo do asfalto foi concluído e a via está liberada para circulação. Agradecemos o contato.', '2026-05-22 16:30:00', 1, 1),
(2, 'A equipe de iluminação realizou a manutenção e o poste voltou a funcionar normalmente.', '2026-05-23 10:20:00', 1, 2),
(3, 'A limpeza do parque foi realizada e o ponto foi incluído no roteiro de fiscalização preventiva.', '2026-05-25 15:45:00', 1, 3),
(4, 'A solicitação foi analisada pela Secretaria de Meio Ambiente e aprovada para estudo técnico do espaço.', '2026-06-01 09:40:00', 1, 12);

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
(1, '2026-05-20 09:15:00', 'Abertura', 'Chamado aberto pelo cidadão.', 2, 1),
(2, '2026-05-20 11:30:00', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Obras.', 1, 1),
(3, '2026-05-20 11:35:00', 'Empresa', 'Chamado atribuído à empresa parceira: Via Forte Pavimentação.', 1, 1),
(4, '2026-05-22 16:30:00', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 1),
(5, '2026-05-21 18:40:00', 'Abertura', 'Chamado aberto pelo cidadão.', 3, 2),
(6, '2026-05-22 08:15:00', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Obras.', 1, 2),
(7, '2026-05-22 08:20:00', 'Empresa', 'Chamado atribuído à empresa parceira: Luz Urbana Manutenção Ltda.', 1, 2),
(8, '2026-05-23 10:20:00', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 2),
(9, '2026-05-24 08:10:00', 'Abertura', 'Chamado aberto pelo cidadão.', 4, 3),
(10, '2026-05-24 10:00:00', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Serviços Urbanos.', 1, 3),
(11, '2026-05-24 10:05:00', 'Empresa', 'Chamado atribuído à empresa parceira: EcoLimpa Serviços Urbanos.', 1, 3),
(12, '2026-05-25 15:45:00', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 3),
(13, '2026-05-27 14:05:00', 'Abertura', 'Chamado aberto pelo cidadão.', 5, 8),
(14, '2026-05-27 16:20:00', 'Atribuição', 'Chamado atribuído ao órgão: SAAE.', 1, 8),
(15, '2026-05-28 09:10:00', 'Status', 'Status alterado para: Em Andamento.', 1, 8),
(16, '2026-05-28 11:25:00', 'Abertura', 'Chamado aberto pelo cidadão.', 6, 10),
(17, '2026-05-28 14:35:00', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Meio Ambiente.', 1, 10),
(18, '2026-05-28 14:40:00', 'Status', 'Status alterado para: Em Análise.', 1, 10),
(19, '2026-05-29 07:50:00', 'Abertura', 'Chamado aberto pelo cidadão.', 7, 11),
(20, '2026-05-30 13:30:00', 'Abertura', 'Chamado aberto pelo cidadão.', 6, 12),
(21, '2026-05-30 15:10:00', 'Atribuição', 'Chamado atribuído ao órgão: Secretaria de Meio Ambiente.', 1, 12),
(22, '2026-05-31 09:15:00', 'Empresa', 'Chamado atribuído à empresa parceira: Via Forte Pavimentação.', 1, 12),
(23, '2026-06-01 09:40:00', 'Resolução', 'Feedback enviado ao cidadão e chamado marcado como resolvido.', 1, 12),
(24, '2026-06-01 16:10:00', 'Abertura', 'Chamado aberto pelo cidadão.', 3, 13),
(25, '2026-06-01 17:25:00', 'Atribuição', 'Chamado atribuído ao órgão: DETRAN Municipal.', 1, 13),
(26, '2026-06-01 17:30:00', 'Status', 'Status alterado para: Em Análise.', 1, 13);

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
(1, 8, 'cidadao', 'img_8_cidadao_69f76a7c73899.png', 'foto_bueiro_entupido.png', '2026-05-27 14:05:00'),
(2, 10, 'cidadao', 'img_10_cidadao_69fcbf2286577.png', 'foto_arvore_fiacao.png', '2026-05-28 11:25:00'),
(3, 12, 'cidadao', 'img_12_cidadao_69ff80f33f824.jpg', 'terreno_horta_comunitaria.jpg', '2026-05-30 13:30:00');

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
(1, 'Administrador', 'admin@prefeitura.gov.br', '(11) 4000-0000', NULL, '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', '2026-05-15 08:00:00'),
(2, 'João Silva', 'joao@email.com', '(11) 98888-1001', '123.456.789-00', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 09:00:00'),
(3, 'Maria Oliveira', 'maria@email.com', '(11) 98888-1002', '234.567.890-11', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 09:20:00'),
(4, 'Pedro Santos', 'pedro@email.com', '(11) 98888-1003', '345.678.901-22', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 09:40:00'),
(5, 'Ana Costa', 'ana@email.com', '(11) 98888-1004', '456.789.012-33', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 10:00:00'),
(6, 'Lucas Pereira', 'lucas@email.com', '(11) 98888-1005', '567.890.123-44', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 10:20:00'),
(7, 'Camila Rocha', 'camila@email.com', '(11) 98888-1006', '678.901.234-55', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'cidadao', '2026-05-15 10:40:00');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `historico`
--
ALTER TABLE `historico`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT de tabela `imagens`
--
ALTER TABLE `imagens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `orgaos`
--
ALTER TABLE `orgaos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `status`
--
ALTER TABLE `status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

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
