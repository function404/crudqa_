-- Cria o banco de dados "crudqa" se ele não existir, utilizando o charset utf8mb4
-- para suporte completo a caracteres especiais e emojis, e a collation adequada.
CREATE DATABASE IF NOT EXISTS crudqa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados "crudqa" para as operações seguintes.
USE crudqa;


-- Tabela de Usuários

-- Esta tabela armazena as informações dos usuários do sistema.
-- Campos:
--   id           : Identificador único de cada usuário (chave primária, auto_increment).
--   email        : E-mail do usuário (único, para evitar duplicidade).
--   nome         : Nome completo do usuário.
--   telefone     : Número de telefone do usuário (campo opcional).
--   senha        : Senha do usuário (idealmente deve ser armazenada de forma criptografada).
--   administrador: Indica se o usuário possui privilégios de administrador (0 = não, 1 = sim).
--   created_at   : Data e hora em que o registro foi criado.
--   updated_at   : Data e hora da última atualização do registro (atualizado automaticamente).
CREATE TABLE `usuario` (
  `id` int(10) UNSIGNED NOT NULL,
  `email` varchar(255) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(20) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Tabela de Produtos

-- Esta tabela armazena as informações dos produtos do sistema.
-- Campos:
--   id         : Identificador único de cada produto (chave primária, auto_increment).
--   nome       : Nome do produto.
--   descricao  : Descrição detalhada do produto (permite mais de 255 caracteres).
--   valor      : Preço do produto, utilizando DECIMAL para precisão em valores monetários.
--   imagem     : Caminho ou URL da imagem do produto (em vez de armazenar a imagem em blob).
--   created_at : Data e hora em que o registro foi criado.
--   updated_at : Data e hora da última atualização do registro (atualizado automaticamente).
CREATE TABLE `produto` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `valor` decimal(10,2) NOT NULL,
  `imagem` mediumblob DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Inserção de Dados nas Tabelas

-- Inserção de dados iniciais na tabela "usuario".
-- Cada linha representa um usuário com seus respectivos dados.
INSERT INTO usuario (email, nome, telefone, senha, administrador) VALUES
('felipe.gabriel@gmail.com', 'Felipe Gabriel', '4799999999', '123', 0),
('lincoln.mezzalira@gmail.com', 'Lincoln', '4788888888', '123', 1),
('joao.prestes@gmail.com', 'João', '4777777777', '123', 0),
('yasmin.friedemann@gmail.com', 'Yasmin', '4555555555', '123', 0);