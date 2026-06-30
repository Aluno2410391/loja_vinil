-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/06/2026 às 12:08
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
-- Banco de dados: `loja_vinil`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categoria`
--

CREATE TABLE `categoria` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categoria`
--

INSERT INTO `categoria` (`id`, `nome`, `ativo`) VALUES
(1, 'Rock', 1),
(2, 'Pop', 1),
(3, 'Hip Hop', 1),
(4, 'MPB', 1),
(5, 'Eletronica', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_item`
--

CREATE TABLE `entrada_item` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `custo_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `entrada_mercadoria`
--

CREATE TABLE `entrada_mercadoria` (
  `id` int(11) NOT NULL,
  `fornecedor_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `status` enum('rascunho','confirmada') NOT NULL DEFAULT 'rascunho',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `estoque`
--

CREATE TABLE `estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 0,
  `minimo` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `estoque`
--

INSERT INTO `estoque` (`id`, `variacao_id`, `quantidade`, `minimo`) VALUES
(1, 1, 10, 2),
(2, 2, 8, 2),
(3, 3, 5, 1),
(4, 4, 7, 2),
(5, 5, 12, 3),
(6, 6, 5, 1),
(7, 7, 5, 1),
(8, 8, 3, 1),
(9, 9, 3, 1),
(10, 10, 5, 1),
(11, 11, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `fornecedor`
--

CREATE TABLE `fornecedor` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `cnpj` varchar(20) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `endereco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `fornecedor`
--

INSERT INTO `fornecedor` (`id`, `nome`, `cnpj`, `telefone`, `email`, `endereco`) VALUES
(1, 'Vinil Imports LTDA', '12.345.678/0001-90', '(21) 99999-1111', 'contato@vinilimports.com', 'Av. Vinil, 1000 - Rio de Janeiro - RJ');

-- --------------------------------------------------------

--
-- Estrutura para tabela `movimento_estoque`
--

CREATE TABLE `movimento_estoque` (
  `id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `tipo` enum('entrada','saida') NOT NULL,
  `quantidade` int(11) NOT NULL,
  `origem` enum('entrada','venda') NOT NULL,
  `origem_id` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_fiscal_entrada`
--

CREATE TABLE `nota_fiscal_entrada` (
  `id` int(11) NOT NULL,
  `entrada_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `chave_acesso` varchar(44) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `nota_fiscal_venda`
--

CREATE TABLE `nota_fiscal_venda` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `modelo` varchar(5) NOT NULL,
  `serie` varchar(5) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `data_emissao` date NOT NULL,
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produto`
--

CREATE TABLE `produto` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `descricao` text DEFAULT NULL,
  `tipo` enum('vinil','vitrola') NOT NULL DEFAULT 'vinil',
  `marca` varchar(100) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produto`
--

INSERT INTO `produto` (`id`, `categoria_id`, `nome`, `descricao`, `tipo`, `marca`, `foto`, `ativo`) VALUES
(1, 1, 'The Dark Side of the Moon', 'Pink Floyd', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/3/3b/Dark_Side_of_the_Moon.png', 1),
(2, 1, 'Back in Black', 'AC/DC', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/commons/b/b9/Back_in_Black_by_ACDC_Portuguese_single.jpg', 1),
(3, 2, 'Thriller', 'Michael Jackson', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/3/30/Michael_Jackson_-_Thriller.jpg', 1),
(4, 3, 'Illmatic', 'Nas', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/9/9c/Illmatic.jpg', 1),
(5, 4, 'Acabou Chorare', 'Novos Baianos', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/f/f6/AcabouChorare.jpg', 1),
(6, 5, 'Vitrola Bluetooth AT-LP60X', 'Toca-discos com Bluetooth', 'vitrola', 'Audio-Technica', 'https://m.media-amazon.com/images/I/71FF1JBUVQL._AC_UF1000,1000_QL80_.jpg', 1),
(7, 5, 'Vitrola Retro Bluetooth', 'Modelo portátil estilo maleta', 'vitrola', 'Raveo', 'https://lojamultilaser.vtexassets.com/arquivos/ids/1531005-1200-auto?v=638955684330700000&width=1200&height=auto&aspect=true', 1),
(8, 1, 'Killers', 'Melhor banda de Heavy metal Britanico Iron Maiden', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/thumb/b/b4/Iron_Maiden_Killers.jpg/250px-Iron_Maiden_Killers.jpg', 0),
(9, 1, 'Thriller', 'MJ', 'vinil', NULL, 'https://cdn-images.dzcdn.net/images/cover/92a024220a9532489c75c9d994835697/1900x1900-80-0-0.jpg', 0),
(10, 1, 'Appetite for Destruction', 'Guns N\' Roses', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/0/06/Appetite_for_Destruction.jpg', 1),
(11, 2, 'Bad', 'Michael Jackson', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/5/51/Michael_Jackson_-_Bad.png', 1),
(12, 2, '1989', 'Taylor Swift', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/c/c3/1989_de_Taylor_Swift.jpg', 1),
(13, 3, 'Get Rich or Die Tryin\'', '50 Cent', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/7/73/Get_Rich_or_Die_Tryin%27.jpg', 1),
(14, 3, 'All Eyez on Me', '2Pac', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/9/9c/2Pac_-_All_Eyez_on_Me.jpg', 1),
(21, 3, 'The Marshall Mathers LP', 'Eminem', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/a/ae/The_Marshall_Mathers_LP.jpg', 1),
(22, 3, 'The Chronic', 'Dr. Dre', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/c/c7/The_Chronic.jpg', 1),
(23, 3, 'Graduation', 'Kanye West', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/7/7b/Graduation_%28%C3%A1lbum_de_Kanye_West%29.jpg', 1),
(24, 4, 'Refazenda', 'Gilberto Gil', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/4/4c/Gilberto_Gil_%281969%29.jpeg', 1),
(25, 4, 'Clube da Esquina', 'Milton Nascimento & Lô Borges', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/c/cb/Milton_Nascimento_-_Clube_da_Esquina.jpg', 1),
(26, 4, 'Alucinação', 'Belchior', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/a/aa/Capa_de_Alucina%C3%A7%C3%A3o.jpg', 1),
(27, 2, 'Like a Virgin', 'Madonna', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/6/60/Capa_do_%C3%A1lbum_Like_a_Virgin.jpg', 1),
(28, 2, 'After Hours', 'The Weeknd', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/c/c1/The_Weeknd_-_After_Hours.png', 1),
(29, 2, 'Purpose', 'Justin Bieber', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/a/ac/Capa_de_Purpose.png', 1),
(30, 2, 'Future Nostalgia', 'Dua Lipa', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/c/c7/Dua_Lipa_-_Future_Nostalgia.png', 1),
(31, 2, 'Confessions on a Dance Floor', 'Madonna', 'vinil', NULL, 'https://upload.wikimedia.org/wikipedia/pt/a/a8/Confessions_on_a_Dance_Floor_%282005%29_por_Madonna.jpg', 1),
(43, 5, 'Suporte de Vinil de madeira', 'Suporte de vinil de base de madeira com suporte de metal, suporte de armazenamento de vinil', 'vitrola', NULL, 'https://m.media-amazon.com/images/I/61TG01bBZAL._AC_UL480_FMwebp_QL65_.jpg', 1),
(44, 5, 'Audio-Technica AT6013', 'Limpador de disco antiestático de dupla ação', 'vitrola', NULL, 'https://m.media-amazon.com/images/I/61zC6pfobvL._AC_UL480_FMwebp_QL65_.jpg', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuario`
--

CREATE TABLE `usuario` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `perfil` enum('admin','vendedor') NOT NULL,
  `ativo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `perfil`, `ativo`) VALUES
(1, 'Admin', 'admin@vinil.com', '123456', 'admin', 1),
(2, 'Vendedor', 'vendedor@vinil.com', '123456', 'vendedor', 1),
(6, 'teste', 'teste@emal.com', '$2y$10$msxiZ1cLYKGMKdjAuGv6MOcgixa0v8FvM6ZGODkR4dt..wDBkyYJa', 'admin', 1),
(7, 'ADM', 'admin2@vinil.com', '$2y$10$5kvHFg65PHC7.l.FrnlNX.GzAa69PhulVS8ymcRkUkGPPtd.6Ucb.', 'vendedor', 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `variacao`
--

CREATE TABLE `variacao` (
  `id` int(11) NOT NULL,
  `produto_id` int(11) NOT NULL,
  `descricao` varchar(100) NOT NULL,
  `preco` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `variacao`
--

INSERT INTO `variacao` (`id`, `produto_id`, `descricao`, `preco`) VALUES
(1, 1, 'LP 180g', 149.90),
(2, 2, 'LP', 129.90),
(3, 3, 'LP Duplo', 189.90),
(4, 4, 'LP', 119.90),
(5, 5, 'LP Nacional', 99.90),
(6, 6, 'Preta 110V Bluetooth', 1299.90),
(7, 6, 'Preta 220V Bluetooth', 1299.90),
(8, 7, 'Maleta Marrom Bluetooth 110V', 899.90),
(9, 7, 'Maleta Azul Bluetooth Bivolt', 949.90),
(10, 8, 'LP', 100.00),
(11, 9, 'LP', 87.00),
(12, 10, '', 139.90),
(13, 11, '', 179.90),
(14, 12, '', 229.90),
(15, 13, '', 169.90),
(16, 14, '', 259.90),
(23, 21, '', 189.90),
(24, 22, '', 179.90),
(25, 23, '', 249.90),
(26, 24, '', 149.90),
(27, 25, '', 179.90),
(28, 26, '', 149.90),
(29, 27, '', 149.90),
(30, 28, '', 219.90),
(31, 29, '', 149.90),
(32, 30, '', 199.90),
(33, 31, '', 179.90),
(34, 43, '', 589.99),
(35, 44, '', 2990.99);

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda`
--

CREATE TABLE `venda` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `data` date NOT NULL,
  `status` enum('aberta','finalizada') NOT NULL DEFAULT 'aberta',
  `valor_total` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `venda_item`
--

CREATE TABLE `venda_item` (
  `id` int(11) NOT NULL,
  `venda_id` int(11) NOT NULL,
  `variacao_id` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categoria`
--
ALTER TABLE `categoria`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `entrada_id` (`entrada_id`),
  ADD KEY `variacao_id` (`variacao_id`);

--
-- Índices de tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fornecedor_id` (`fornecedor_id`);

--
-- Índices de tabela `estoque`
--
ALTER TABLE `estoque`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variacao_id` (`variacao_id`);

--
-- Índices de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variacao_id` (`variacao_id`);

--
-- Índices de tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `entrada_id` (`entrada_id`),
  ADD UNIQUE KEY `chave_acesso` (`chave_acesso`);

--
-- Índices de tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venda_id` (`venda_id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Índices de tabela `produto`
--
ALTER TABLE `produto`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_produto_categoria` (`categoria_id`);

--
-- Índices de tabela `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Índices de tabela `variacao`
--
ALTER TABLE `variacao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_variacao_produto` (`produto_id`);

--
-- Índices de tabela `venda`
--
ALTER TABLE `venda`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`);

--
-- Índices de tabela `venda_item`
--
ALTER TABLE `venda_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `venda_id` (`venda_id`),
  ADD KEY `variacao_id` (`variacao_id`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categoria`
--
ALTER TABLE `categoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `entrada_item`
--
ALTER TABLE `entrada_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `estoque`
--
ALTER TABLE `estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de tabela `fornecedor`
--
ALTER TABLE `fornecedor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produto`
--
ALTER TABLE `produto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT de tabela `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `variacao`
--
ALTER TABLE `variacao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT de tabela `venda`
--
ALTER TABLE `venda`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `venda_item`
--
ALTER TABLE `venda_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `entrada_item`
--
ALTER TABLE `entrada_item`
  ADD CONSTRAINT `entrada_item_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entrada_mercadoria` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `entrada_item_ibfk_2` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`);

--
-- Restrições para tabelas `entrada_mercadoria`
--
ALTER TABLE `entrada_mercadoria`
  ADD CONSTRAINT `entrada_mercadoria_ibfk_1` FOREIGN KEY (`fornecedor_id`) REFERENCES `fornecedor` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `estoque`
--
ALTER TABLE `estoque`
  ADD CONSTRAINT `fk_estoque_variacao` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `movimento_estoque`
--
ALTER TABLE `movimento_estoque`
  ADD CONSTRAINT `movimento_estoque_ibfk_1` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`);

--
-- Restrições para tabelas `nota_fiscal_entrada`
--
ALTER TABLE `nota_fiscal_entrada`
  ADD CONSTRAINT `nota_fiscal_entrada_ibfk_1` FOREIGN KEY (`entrada_id`) REFERENCES `entrada_mercadoria` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `nota_fiscal_venda`
--
ALTER TABLE `nota_fiscal_venda`
  ADD CONSTRAINT `nota_fiscal_venda_ibfk_1` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `produto`
--
ALTER TABLE `produto`
  ADD CONSTRAINT `fk_produto_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categoria` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `variacao`
--
ALTER TABLE `variacao`
  ADD CONSTRAINT `fk_variacao_produto` FOREIGN KEY (`produto_id`) REFERENCES `produto` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `venda`
--
ALTER TABLE `venda`
  ADD CONSTRAINT `venda_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuario` (`id`) ON UPDATE CASCADE;

--
-- Restrições para tabelas `venda_item`
--
ALTER TABLE `venda_item`
  ADD CONSTRAINT `venda_item_ibfk_1` FOREIGN KEY (`venda_id`) REFERENCES `venda` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `venda_item_ibfk_2` FOREIGN KEY (`variacao_id`) REFERENCES `variacao` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
