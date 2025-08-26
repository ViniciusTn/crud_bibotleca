-- Banco de dados da Biboteca
CREATE DATABASE IF NOT EXISTS biboteca;
USE biboteca;

-- Tabela de Autores
CREATE TABLE autores (
    id_autor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    nacionalidade VARCHAR(50),
    ano_nascimento INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Livros
CREATE TABLE livros (
    id_livro INT AUTO_INCREMENT PRIMARY KEY,
    titulo VARCHAR(200) NOT NULL,
    genero VARCHAR(50),
    ano_publicacao INT NOT NULL,
    id_autor INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_autor) REFERENCES autores(id_autor) ON DELETE CASCADE,
    CHECK (ano_publicacao > 1500 AND ano_publicacao <= YEAR(CURDATE()))
);

-- Tabela de Leitores
CREATE TABLE leitores (
    id_leitor INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE,
    telefone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Tabela de Empréstimos
CREATE TABLE emprestimos (
    id_emprestimo INT AUTO_INCREMENT PRIMARY KEY,
    id_livro INT NOT NULL,
    id_leitor INT NOT NULL,
    data_emprestimo DATE NOT NULL,
    data_devolucao DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_livro) REFERENCES livros(id_livro) ON DELETE CASCADE,
    FOREIGN KEY (id_leitor) REFERENCES leitores(id_leitor) ON DELETE CASCADE,
    CHECK (data_devolucao IS NULL OR data_devolucao >= data_emprestimo)
);

-- Dados de exemplo para Autores
INSERT INTO autores (nome, nacionalidade, ano_nascimento) VALUES
('Machado de Assis', 'Brasileiro', 1839),
('Clarice Lispector', 'Brasileira', 1920),
('Jorge Amado', 'Brasileiro', 1912),
('George Orwell', 'Britânico', 1903),
('J.K. Rowling', 'Britânica', 1965);

-- Dados de exemplo para Livros
INSERT INTO livros (titulo, genero, ano_publicacao, id_autor) VALUES
('Dom Casmurro', 'Romance', 1899, 1),
('Memórias Póstumas de Brás Cubas', 'Romance', 1881, 1),
('A Hora da Estrela', 'Romance', 1977, 2),
('Capitães da Areia', 'Romance', 1937, 3),
('1984', 'Ficção Científica', 1949, 4),
('Harry Potter e a Pedra Filosofal', 'Fantasia', 1997, 5);

-- Dados de exemplo para Leitores
INSERT INTO leitores (nome, email, telefone) VALUES
('João Silva', 'joao@email.com', '(11) 99999-9999'),
('Maria Santos', 'maria@email.com', '(11) 88888-8888'),
('Pedro Oliveira', 'pedro@email.com', '(11) 77777-7777');

-- Dados de exemplo para Empréstimos
INSERT INTO emprestimos (id_livro, id_leitor, data_emprestimo, data_devolucao) VALUES
(1, 1, '2024-01-15', '2024-01-30'),
(4, 2, '2024-02-01', NULL),
(5, 3, '2024-02-10', '2024-02-25'),
(2, 1, '2024-03-01', NULL),
(6, 2, '2024-03-05', NULL);

-- Índices para melhor performance
CREATE INDEX idx_livros_autor ON livros(id_autor);
CREATE INDEX idx_livros_genero ON livros(genero);
CREATE INDEX idx_livros_ano ON livros(ano_publicacao);
CREATE INDEX idx_emprestimos_livro ON emprestimos(id_livro);
CREATE INDEX idx_emprestimos_leitor ON emprestimos(id_leitor);
CREATE INDEX idx_emprestimos_data ON emprestimos(data_emprestimo);
