-- Cria o banco de dados "crudqa" se ele não existir, utilizando o charset utf8mb4
-- para suporte completo a caracteres especiais e emojis, e a collation adequada.
CREATE DATABASE IF NOT EXISTS crudqa CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Seleciona o banco de dados "crudqa" para as operações seguintes.
USE crudqa;

-- Tabela de Usuários

-- Esta tabela armazena as informações dos usuários do sistema.
-- Campos:
--   idUsuario     : Identificador único de cada usuário (chave primária, auto_increment).
--   email         : E-mail do usuário (único, para evitar duplicidade).
--   nomeUsuario   : Nome completo do usuário.
--   telefone      : Número de telefone do usuário (campo opcional).
--   senha         : Senha do usuário (idealmente deve ser armazenada de forma criptografada).
--   administrador : Indica se o usuário possui privilégios de administrador (0 = não, 1 = sim).
--   criado_as     : Data e hora em que o registro foi criado.
--   atualizado_as : Data e hora da última atualização do registro (atualizado automaticamente).
CREATE TABLE IF NOT EXISTS `usuario` (
  `idUsuario` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL UNIQUE,
  `nomeUsuario` varchar(255) NOT NULL,
  `telefone` varchar(20) UNIQUE,
  `senha` varchar(255) NOT NULL,
  `administrador` tinyint(1) NOT NULL DEFAULT 0,
  `criado_as` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_as` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idUsuario`),
  CHECK (nomeUsuario <> ''),
  CHECK (email <> ''),
  CHECK (senha <> '')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Produtos

-- Esta tabela armazena as informações dos produtos do sistema.
-- Campos:
--   idProduto     : Identificador único de cada produto (chave primária, auto_increment).
--   nomeProduto   : Nome do produto.
--   descricao     : Descrição detalhada do produto (permite mais de 255 caracteres).
--   valor         : Preço do produto, utilizando DECIMAL para precisão em valores monetários.
--   imagem        : Caminho ou URL da imagem do produto (em vez de armazenar a imagem em blob).
--   criado_as     : Data e hora em que o registro foi criado.
--   atualizado_as : Data e hora da última atualização do registro (atualizado automaticamente).
CREATE TABLE IF NOT EXISTS `produto` (
  `idProduto` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `nomeProduto` varchar(255) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `imagem` mediumblob DEFAULT NULL,
  `quantidade` int(5) DEFAULT NULL,
  `criado_as` timestamp NOT NULL DEFAULT current_timestamp(),
  `atualizado_as` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`idProduto`),
  CHECK (nomeProduto <> ''),
  CHECK (descricao <> ''),
  CHECK (valor <> ''),
  CHECK (quantidade <> ''),
  CHECK (imagem <> '')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabela de Keys

-- Esta tabela armazena as chaves de acesso para administração do sistema.
-- Campos:
--   idKey : Identificador único de cada chave (chave primária, auto_increment).
--   key   : Chave de acesso (número inteiro de 6 dígitos).
CREATE TABLE IF NOT EXISTS `keys` (
  `idKey` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key_value` int(6) NOT NULL UNIQUE,
  PRIMARY KEY (`idKey`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insere na tabela de Keys
INSERT INTO `keys`(`idKey`, `key_value`) VALUES 
(1,'123456');