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

ALTER TABLE tbUsu
MODIFY COLUMN senha VARCHAR(255) NOT NULL;

ALTER TABLE tbUsu
ADD CONSTRAINT fk_id_produto
FOREIGN KEY (id_produto) REFERENCES tbProduto(id);
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

ALTER TABLE tbProduto
ADD COLUMN link_img VARCHAR(255);

/* Tabela de Categorias */

CREATE TABLE tbCategorias (
	id INT PRIMARY KEY AUTO_INCREMENT ,
    nome VARCHAR(100) NOT NULL
);


