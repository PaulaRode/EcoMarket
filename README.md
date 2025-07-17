# 🌱 EcoMarket - Plataforma de Produtos Sustentáveis

Sistema completo de marketplace ecológico conectando produtores sustentáveis com consumidores conscientes, com foco em usabilidade, design moderno e responsividade

**PHP MySQL JavaScript CSS3 FontAwesome**

## 📋 Índice

- [Sobre o Projeto](#-sobre-o-projeto)
- [Funcionalidades](#-funcionalidades)
- [Tecnologias Utilizadas](#️-tecnologias-utilizadas)
- [Estrutura do Projeto](#-estrutura-do-projeto)
- [Instalação](#-instalação)
- [Configuração](#️-configuração)
- [Como Usar](#-como-usar)
- [API e Backend](#-api-e-backend)
- [Contribuição](#-contribuição)
- [Licença](#-licença)

## 🌱 Sobre o Projeto

**EcoMarket** é uma plataforma web completa para marketplace de produtos sustentáveis e orgânicos, combinando gestão de produtos tradicionais com consciência ambiental e uma experiência visual moderna e responsiva. O sistema conecta produtores locais com consumidores que valorizam produtos eco-friendly.

## ✨ Funcionalidades

### 🔐 Sistema de Autenticação
- Cadastro e login de produtores com validação e segurança
- Logout seguro
- Validação de senha forte e email único
- Sessões seguras com controle de acesso

### 🛍️ Vitrine Pública
- Exibição de produtos com imagens, descrições e preços
- Filtro dinâmico por categoria (Alimentos, Limpeza, etc.)
- Interface responsiva para todos os dispositivos
- Design moderno com elementos orgânicos

### 👨‍🌾 Dashboard do Produtor
- Visão geral dos produtos cadastrados
- Estatísticas de produtos por categoria
- Interface administrativa intuitiva
- Gestão completa de produtos

### 📦 Gestão de Produtos
- Cadastro, edição e exclusão de produtos
- Upload de imagens com validação
- Categorias organizadas
- Histórico completo de produtos

### 🎨 Interface Moderna
- Design responsivo para todos os dispositivos
- Paleta de cores sustentável (tons de verde)
- Elementos SVG orgânicos decorativos
- Animações CSS suaves
- Gradientes modernos

## 🛠️ Tecnologias Utilizadas

### Backend
- **PHP 7.4+**
- **MySQL 5.7+**
- **PDO** com prepared statements
- **Sessions** para autenticação

### Frontend
- **HTML5** semântico
- **CSS3** com organização em `styles/`
- **JavaScript ES6+** (scripts em `script/`)
- **FontAwesome** para ícones
- **Google Fonts** (Montserrat)

### Ferramentas
- **XAMPP** (Apache + MySQL + PHP)
- **Git** para versionamento
- **VS Code** para desenvolvimento

## 📁 Estrutura do Projeto

```
EcoMarket/
├── assets/
│   └── logo/
│       ├── eco logo.jpeg
│       ├── logo.jpg
│       └── logo.png
├── classes/
│   ├── Categoria.php
│   ├── DataBase.php
│   ├── Produto.php
│   └── Usuario.php
├── config/
│   ├── config.php
│   └── dump.sql
├── script/
│   ├── alterarProduto.js
│   ├── alterarUsuario.js
│   ├── cadastrarProduto.js
│   ├── cadastrarUsuario.js
│   ├── dashboard.js
│   ├── deletarProduto.js
│   ├── deletarUsuario.js
│   ├── footer.js
│   ├── header.js
│   ├── index.js
│   └── login.js
├── styles/
│   ├── alterarProduto.css
│   ├── alterarUsuario.css
│   ├── cadastrarProduto.css
│   ├── cadastrarUsuario.css
│   ├── dashboard.css
│   ├── deletarProduto.css
│   ├── deletarUsuario.css
│   ├── footer.css
│   ├── header.css
│   ├── index.css
│   └── login.css
├── uploads/                  # Imagens dos produtos
├── alterarProduto.php
├── alterarUsuario.php
├── cadastrarProduto.php
├── cadastrarUsuario.php
├── contato.php
├── dashboard.php
├── deletarProduto.php
├── deletarUsuario.php
├── footer.php
├── header.php
├── index.php
├── login.php
├── loginProcessa.php
├── logout.php
├── sobre.php
├── test_connection.php
└── README.md
```

## 🚀 Instalação

### Pré-requisitos
- **XAMPP** (Apache + MySQL + PHP)
- **PHP 7.4** ou superior
- **MySQL 5.7** ou superior
- **Navegador web moderno**

### Passos de Instalação

1. **Clone o repositório**
   ```bash
   # Coloque o projeto na pasta htdocs do XAMPP
   C:\xampp\htdocs\EcoMarket\
   ```

2. **Configure o XAMPP**
   - Inicie o Apache e MySQL
   - Acesse phpMyAdmin: `http://localhost/phpmyadmin`

3. **Configure o banco de dados**
   ```sql
   -- Execute o arquivo config/dump.sql
   CREATE DATABASE EcoMarket;
   USE EcoMarket;
   -- Importe o arquivo dump.sql
   ```

4. **Configure a conexão**
   ```php
   // Edite classes/DataBase.php
   $host = 'localhost';
   $db   = 'EcoMarket';
   $user = 'root';     // seu usuário MySQL
   $pass = '';         // sua senha MySQL
   ```

5. **Configure permissões**
   ```bash
   # Crie a pasta uploads/ na raiz do projeto
   mkdir uploads
   # Configure permissões de escrita
   chmod 755 uploads/
   ```

6. **Acesse o projeto**
   ```
   http://localhost/EcoMarket/
   ```

## ⚙️ Configuração

### Banco de Dados
O sistema utiliza as seguintes tabelas:

**tbUsu (Usuários)**
```sql
CREATE TABLE tbUsu (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    email VARCHAR(200) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    telefone VARCHAR(11) NOT NULL
);
```

**tbProduto (Produtos)**
```sql
CREATE TABLE tbProduto (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(200) NOT NULL,
    descricao VARCHAR(200) NOT NULL,
    preco VARCHAR(10) NOT NULL,
    categoria INT NOT NULL,
    link_img VARCHAR(255),
    id_usuario INT NOT NULL,
    FOREIGN KEY(categoria) REFERENCES tbCategorias(id),
    FOREIGN KEY(id_usuario) REFERENCES tbUsu(id)
);
```

**tbCategorias (Categorias)**
```sql
CREATE TABLE tbCategorias (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL
);
```

### Configurações de Segurança
- **Sessões PHP** configuradas para segurança
- **Prepared statements** para prevenir SQL injection
- **Hash de senhas** com `password_hash()` (BCRYPT)
- **Validação de entrada** em todos os formulários
- **Sanitização de dados** com `filter_input()`

## 📖 Como Usar

### Primeiro Acesso
1. Acesse a página inicial: `http://localhost/EcoMarket/`
2. Navegue pela vitrine de produtos
3. Use o filtro por categoria para encontrar produtos específicos
4. Para produtores: acesse `login.php` para criar conta

### Para Produtores
1. **Cadastro**: Acesse `cadastrarUsuario.php` para criar conta
2. **Login**: Use `login.php` para acessar o dashboard
3. **Cadastrar Produtos**: Use `cadastrarProduto.php` para adicionar produtos
4. **Gerenciar**: Use o dashboard para editar/excluir produtos
5. **Logout**: Use `logout.php` para sair do sistema

### Para Consumidores
1. **Navegar**: Explore a vitrine de produtos na página inicial
2. **Filtrar**: Use o seletor de categorias para encontrar produtos específicos
3. **Visualizar**: Clique nos produtos para ver detalhes
4. **Contato**: Use `contato.php` para entrar em contato

### Destaques Visuais
- Todos os formulários e páginas seguem um padrão visual moderno
- Paleta de cores sustentável com tons de verde
- Elementos SVG orgânicos decorativos
- Layout responsivo para todos os dispositivos
- Animações CSS suaves e gradientes modernos

## 🔧 API e Backend

### Endpoints Principais

**Autenticação**
- `POST /loginProcessa.php` - Autenticação de usuário
- `POST /cadastrarUsuario.php` - Cadastro de novo usuário
- `GET /logout.php` - Encerramento de sessão

**Gestão de Produtos**
- `POST /cadastrarProduto.php` - Criar novo produto
- `POST /alterarProduto.php` - Editar produto existente
- `POST /deletarProduto.php` - Excluir produto

**Gestão de Usuários**
- `POST /alterarUsuario.php` - Editar dados do usuário
- `POST /deletarUsuario.php` - Excluir usuário

**Páginas Principais**
- `GET /index.php` - Vitrine de produtos
- `GET /dashboard.php` - Painel do produtor
- `GET /contato.php` - Página de contato
- `GET /sobre.php` - Sobre o projeto

### Classes PHP
- **`DataBase.php`** - Conexão com banco de dados
- **`Usuario.php`** - Gerenciamento de usuários
- **`Produto.php`** - Gerenciamento de produtos
- **`Categoria.php`** - Gerenciamento de categorias

## 🤝 Contribuição

1. **Fork** o projeto
2. Crie uma **branch** para sua feature
   ```bash
   git checkout -b feature/NovaFuncionalidade
   ```
3. **Commit** e **push** das mudanças
   ```bash
   git commit -m 'Adiciona nova funcionalidade'
   git push origin feature/NovaFuncionalidade
   ```
4. Abra um **Pull Request**

### Padrões de Código
- **PHP**: PSR-12
- **JavaScript**: ES6+
- **CSS**: Organização modular
- **HTML**: Semantic markup

## 📄 Licença

Este projeto foi desenvolvido para fins educacionais e comerciais.

## 👥 Autores

**EcoMarket Team** - Desenvolvimento inicial
**PO** - Leonardo Andriotti - 2001leomoura@gmail.com
**Scrum Master** - Paula Rode - paulamrode@gmail.com
**Devs**
- Higor Carboni - ohigor.carboni@gmail.com
- Arthur Cantelle -arthurcccantelle@gmail.com
- Rafael Gomes - rg9402393@gmail.com
- Davi Schinoff - davi.schinoff@gmail.com
- Wesley Lima - wesleydelimarosa2024@gmail.com
- Rodrigo Nunes - rodrigo59130@gmail.com
- Matheus Baumhardt - matheusbaumhardt654@gmail.com
- Daniel Jacob - dj76318@gmail.com
- Gabriel Bock - gabrielcostabock@gmail.com
- Miguel Oliveira - miguel.oliveirahuehue@gmail.com

---

**🌱 EcoMarket - Conectando produtores sustentáveis com consumidores conscientes**
