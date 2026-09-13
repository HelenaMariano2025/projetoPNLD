CREATE DATABASE SistemaHBL DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE SistemaHBL;


CREATE TABLE turma (
	codigo INT PRIMARY KEY,
	curso VARCHAR (20) NOT NULL,
    siglaCurso VARCHAR (50),
	periodo INT NOT NULL,
    serie INT NOT NULL,
	matrizCurricular VARCHAR (200),
	situacao ENUM('ativo','inativo')
);

CREATE TABLE status_exemplar (
    codigo INT AUTO_INCREMENT PRIMARY KEY,
	descricao VARCHAR(100) NOT NULL 
);

CREATE TABLE aluno (
   matricula BIGINT NOT NULL UNIQUE PRIMARY KEY,
   nome VARCHAR(100) NOT NULL,
   datnasc DATE,
   endereco VARCHAR(200),
   sexo varCHAR(1),
   email VARCHAR(100) NOT NULL,
   situacao ENUM('ativo','inativo'),
   codigoTurma INT NOT NULL,
   FOREIGN KEY (codigoTurma) REFERENCES turma (codigo)
);

CREATE TABLE administrador (
   matricula BIGINT UNIQUE PRIMARY KEY,
   nome VARCHAR(50),
   senha VARCHAR(20) NOT NULL
);

CREATE TABLE livro (
   codigo INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
   isbn BIGINT NOT NULL UNIQUE, 
   titulo VARCHAR(100) NOT NULL,
   autor VARCHAR(100) NOT NULL,
   codigo_editora INTEGER NOT NULL,
   ano INT NOT NULL,
   situacao ENUM('ativo','inativo'),
   edicao INT NOT NULL,
   qtde_disponivel INT
);

/* CREATE TABLE exemplar(
   codigo_tombo BIGINT NOT NULL,
   codigo_tombo BIGINT NOT NULL,
   codigo_livro INTEGER NOT NULL,
   data_aquisicao DATE,
   tipo INT NOT NULL,
   status INT NOT NULL,
   observacao varchar(500),
   situacao INT NOT NULL, 
   PRIMARY KEY(codigo_tombo, codigo_livro),
   FOREIGN KEY(codigo_livro) REFERENCES livro (codigo),
   FOREIGN KEY(status) REFERENCES status_exemplar (codigo)
); */

CREATE TABLE emprestimo (
   codigo_emprestimo INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
   codigo_livro INTEGER NOT NULL,
   dataEmprestimo DATE NOT NULL,
   dataDevolucao DATE NOT NULL,
   matricula_aluno BIGINT NOT NULL,
   adm_responsavel BIGINT NOT NULL,
   FOREIGN KEY (codigo_livro) REFERENCES livro (codigo),
   FOREIGN KEY (matricula_aluno) REFERENCES aluno (matricula),
   FOREIGN KEY (adm_responsavel) REFERENCES administrador (matricula)
);

CREATE TABLE devolucao (
	codigo_devolucao INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    codigo_emprestimo INTEGER NOT NULL,
    dataDevolucao DATE,
    FOREIGN KEY (codigo_emprestimo) REFERENCES emprestimo (codigo_emprestimo)
);

INSERT INTO administrador VALUES ("1234567890", "Ronaldo", "1234567890");

select * from administrador;