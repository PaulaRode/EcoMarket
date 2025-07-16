-- Adicionar coluna produtor_id na tabela tbProduto
ALTER TABLE tbProduto ADD COLUMN produtor_id INT;

-- Adicionar chave estrangeira
ALTER TABLE tbProduto ADD CONSTRAINT fk_produto_produtor 
FOREIGN KEY (produtor_id) REFERENCES tbUsu(id);

-- Atualizar produtos existentes (opcional - para desenvolvimento)
-- UPDATE tbProduto SET produtor_id = 1 WHERE produtor_id IS NULL; 