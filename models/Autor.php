<?php
class Autor {
    private $conn;
    private $table_name = "autores";

    public $id_autor;
    public $nome;
    public $nacionalidade;
    public $ano_nascimento;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, nacionalidade=:nacionalidade, ano_nascimento=:ano_nascimento";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->nacionalidade = htmlspecialchars(strip_tags($this->nacionalidade));
        $this->ano_nascimento = htmlspecialchars(strip_tags($this->ano_nascimento));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":nacionalidade", $this->nacionalidade);
        $stmt->bindParam(":ano_nascimento", $this->ano_nascimento);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nome=:nome, nacionalidade=:nacionalidade, ano_nascimento=:ano_nascimento WHERE id_autor=:id_autor";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->nacionalidade = htmlspecialchars(strip_tags($this->nacionalidade));
        $this->ano_nascimento = htmlspecialchars(strip_tags($this->ano_nascimento));
        $this->id_autor = htmlspecialchars(strip_tags($this->id_autor));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":nacionalidade", $this->nacionalidade);
        $stmt->bindParam(":ano_nascimento", $this->ano_nascimento);
        $stmt->bindParam(":id_autor", $this->id_autor);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_autor = :id_autor";
        $stmt = $this->conn->prepare($query);
        $this->id_autor = htmlspecialchars(strip_tags($this->id_autor));
        $stmt->bindParam(":id_autor", $this->id_autor);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
