<?php
class Livro {
    private $conn;
    private $table_name = "livros";

    public $id_livro;
    public $titulo;
    public $genero;
    public $ano_publicacao;
    public $id_autor;
    public $autor_nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET titulo=:titulo, genero=:genero, ano_publicacao=:ano_publicacao, id_autor=:id_autor";
        $stmt = $this->conn->prepare($query);

        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->ano_publicacao = htmlspecialchars(strip_tags($this->ano_publicacao));
        $this->id_autor = htmlspecialchars(strip_tags($this->id_autor));

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":genero", $this->genero);
        $stmt->bindParam(":ano_publicacao", $this->ano_publicacao);
        $stmt->bindParam(":id_autor", $this->id_autor);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT l.*, a.nome as autor_nome FROM " . $this->table_name . " l LEFT JOIN autores a ON l.id_autor = a.id_autor ORDER BY l.titulo";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByGenre($genero) {
        $query = "SELECT l.*, a.nome as autor_nome FROM " . $this->table_name . " l LEFT JOIN autores a ON l.id_autor = a.id_autor WHERE l.genero = :genero ORDER BY l.titulo";
        $stmt = $this->conn->prepare($query);
        $genero = htmlspecialchars(strip_tags($genero));
        $stmt->bindParam(":genero", $genero);
        $stmt->execute();
        return $stmt;
    }

    public function readByAuthor($id_autor) {
        $query = "SELECT l.*, a.nome as autor_nome FROM " . $this->table_name . " l LEFT JOIN autores a ON l.id_autor = a.id_autor WHERE l.id_autor = :id_autor ORDER BY l.titulo";
        $stmt = $this->conn->prepare($query);
        $id_autor = htmlspecialchars(strip_tags($id_autor));
        $stmt->bindParam(":id_autor", $id_autor);
        $stmt->execute();
        return $stmt;
    }

    public function readByYear($ano) {
        $query = "SELECT l.*, a.nome as autor_nome FROM " . $this->table_name . " l LEFT JOIN autores a ON l.id_autor = a.id_autor WHERE l.ano_publicacao = :ano ORDER BY l.titulo";
        $stmt = $this->conn->prepare($query);
        $ano = htmlspecialchars(strip_tags($ano));
        $stmt->bindParam(":ano", $ano);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET titulo=:titulo, genero=:genero, ano_publicacao=:ano_publicacao, id_autor=:id_autor WHERE id_livro=:id_livro";
        $stmt = $this->conn->prepare($query);

        $this->titulo = htmlspecialchars(strip_tags($this->titulo));
        $this->genero = htmlspecialchars(strip_tags($this->genero));
        $this->ano_publicacao = htmlspecialchars(strip_tags($this->ano_publicacao));
        $this->id_autor = htmlspecialchars(strip_tags($this->id_autor));
        $this->id_livro = htmlspecialchars(strip_tags($this->id_livro));

        $stmt->bindParam(":titulo", $this->titulo);
        $stmt->bindParam(":genero", $this->genero);
        $stmt->bindParam(":ano_publicacao", $this->ano_publicacao);
        $stmt->bindParam(":id_autor", $this->id_autor);
        $stmt->bindParam(":id_livro", $this->id_livro);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_livro = :id_livro";
        $stmt = $this->conn->prepare($query);
        $this->id_livro = htmlspecialchars(strip_tags($this->id_livro));
        $stmt->bindParam(":id_livro", $this->id_livro);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
