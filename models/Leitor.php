<?php
class Leitor {
    private $conn;
    private $table_name = "leitores";

    public $id_leitor;
    public $nome;
    public $email;
    public $telefone;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nome=:nome, email=:email, telefone=:telefone";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);

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
        $query = "UPDATE " . $this->table_name . " SET nome=:nome, email=:email, telefone=:telefone WHERE id_leitor=:id_leitor";
        $stmt = $this->conn->prepare($query);

        $this->nome = htmlspecialchars(strip_tags($this->nome));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->telefone = htmlspecialchars(strip_tags($this->telefone));
        $this->id_leitor = htmlspecialchars(strip_tags($this->id_leitor));

        $stmt->bindParam(":nome", $this->nome);
        $stmt->bindParam(":email", $this->email);
        $stmt->bindParam(":telefone", $this->telefone);
        $stmt->bindParam(":id_leitor", $this->id_leitor);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_leitor = :id_leitor";
        $stmt = $this->conn->prepare($query);
        $this->id_leitor = htmlspecialchars(strip_tags($this->id_leitor));
        $stmt->bindParam(":id_leitor", $this->id_leitor);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>
