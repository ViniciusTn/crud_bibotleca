<?php
class Emprestimo {
    private $conn;
    private $table_name = "emprestimos";

    public $id_emprestimo;
    public $id_livro;
    public $id_leitor;
    public $data_emprestimo;
    public $data_devolucao;
    public $livro_titulo;
    public $leitor_nome;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        if (!$this->isLivroDisponivel($this->id_livro)) {
            throw new Exception("Livro já está emprestado e não pode ser emprestado novamente.");
        }

        if ($this->countEmprestimosAtivos($this->id_leitor) >= 3) {
            throw new Exception("Leitor já possui 3 empréstimos ativos. Limite máximo atingido.");
        }

        $query = "INSERT INTO " . $this->table_name . " SET id_livro=:id_livro, id_leitor=:id_leitor, data_emprestimo=:data_emprestimo, data_devolucao=:data_devolucao";
        $stmt = $this->conn->prepare($query);

        $this->id_livro = htmlspecialchars(strip_tags($this->id_livro));
        $this->id_leitor = htmlspecialchars(strip_tags($this->id_leitor));
        $this->data_emprestimo = htmlspecialchars(strip_tags($this->data_emprestimo));
        $this->data_devolucao = htmlspecialchars(strip_tags($this->data_devolucao));

        $stmt->bindParam(":id_livro", $this->id_livro);
        $stmt->bindParam(":id_leitor", $this->id_leitor);
        $stmt->bindParam(":data_emprestimo", $this->data_emprestimo);
        $stmt->bindParam(":data_devolucao", $this->data_devolucao);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT e.*, l.titulo as livro_titulo, lt.nome as leitor_nome 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN livros l ON e.id_livro = l.id_livro 
                  LEFT JOIN leitores lt ON e.id_leitor = lt.id_leitor 
                  ORDER BY e.data_emprestimo DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readAtivos() {
        $query = "SELECT e.*, l.titulo as livro_titulo, lt.nome as leitor_nome 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN livros l ON e.id_livro = l.id_livro 
                  LEFT JOIN leitores lt ON e.id_leitor = lt.id_leitor 
                  WHERE e.data_devolucao IS NULL 
                  ORDER BY e.data_emprestimo DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readConcluidos() {
        $query = "SELECT e.*, l.titulo as livro_titulo, lt.nome as leitor_nome 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN livros l ON e.id_livro = l.id_livro 
                  LEFT JOIN leitores lt ON e.id_leitor = lt.id_leitor 
                  WHERE e.data_devolucao IS NOT NULL 
                  ORDER BY e.data_emprestimo DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readByLeitor($id_leitor) {
        $query = "SELECT e.*, l.titulo as livro_titulo, lt.nome as leitor_nome 
                  FROM " . $this->table_name . " e 
                  LEFT JOIN livros l ON e.id_livro = l.id_livro 
                  LEFT JOIN leitores lt ON e.id_leitor = lt.id_leitor 
                  WHERE e.id_leitor = :id_leitor 
                  ORDER BY e.data_emprestimo DESC";
        $stmt = $this->conn->prepare($query);
        $id_leitor = htmlspecialchars(strip_tags($id_leitor));
        $stmt->bindParam(":id_leitor", $id_leitor);
        $stmt->execute();
        return $stmt;
    }

    public function update() {
        $query = "UPDATE " . $this->table_name . " SET id_livro=:id_livro, id_leitor=:id_leitor, data_emprestimo=:data_emprestimo, data_devolucao=:data_devolucao WHERE id_emprestimo=:id_emprestimo";
        $stmt = $this->conn->prepare($query);

        $this->id_livro = htmlspecialchars(strip_tags($this->id_livro));
        $this->id_leitor = htmlspecialchars(strip_tags($this->id_leitor));
        $this->data_emprestimo = htmlspecialchars(strip_tags($this->data_emprestimo));
        $this->data_devolucao = htmlspecialchars(strip_tags($this->data_devolucao));
        $this->id_emprestimo = htmlspecialchars(strip_tags($this->id_emprestimo));

        $stmt->bindParam(":id_livro", $this->id_livro);
        $stmt->bindParam(":id_leitor", $this->id_leitor);
        $stmt->bindParam(":data_emprestimo", $this->data_emprestimo);
        $stmt->bindParam(":data_devolucao", $this->data_devolucao);
        $stmt->bindParam(":id_emprestimo", $this->id_emprestimo);

        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_emprestimo = :id_emprestimo";
        $stmt = $this->conn->prepare($query);
        $this->id_emprestimo = htmlspecialchars(strip_tags($this->id_emprestimo));
        $stmt->bindParam(":id_emprestimo", $this->id_emprestimo);
        if ($stmt->execute()) {
            return true;
        }
        return false;
    }

    private function isLivroDisponivel($id_livro) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE id_livro = :id_livro AND data_devolucao IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_livro", $id_livro);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'] == 0;
    }

    private function countEmprestimosAtivos($id_leitor) {
        $query = "SELECT COUNT(*) as count FROM " . $this->table_name . " WHERE id_leitor = :id_leitor AND data_devolucao IS NULL";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id_leitor", $id_leitor);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['count'];
    }
}
?>
