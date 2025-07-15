CREATE DATABASE EcoMarket; 
USE EcoMarket;

 /* Tabela de Usuário */
CREATE TABLE tbUsu (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL ,
    senha INT NOT NULL,
    telefone VARCHAR(11) NOT NULL
);

UPDATE tbUsu SET senha = PASSWORD(senha);

/* Tabela de Produtos */
CREATE TABLE tbProduto (
	id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    descricao VARCHAR(200) NOT NULL,
    preco VARCHAR(10) NOT NULL ,
    categoria INT NOT NULL ,
    FOREIGN KEY(categoria) REFERENCES tbCategorias(id)
);

/* Tabela de Categorias */

CREATE TABLE tbCategorias (
	id INT PRIMARY KEY AUTO_INCREMENT ,
    nome VARCHAR(100) NOT NULL
);
