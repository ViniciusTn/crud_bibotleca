<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Emprestimo.php';

$database = new Database();
$db = $database->getConnection();
$emprestimo = new Emprestimo($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $emprestimo->id_emprestimo = $_GET['id'];
            $stmt = $emprestimo->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $emprestimos_arr = array();
                $emprestimos_arr["emprestimos"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['id_emprestimo'] == $emprestimo->id_emprestimo) {
                        $emprestimo_item = array(
                            "id_emprestimo" => $row['id_emprestimo'],
                            "id_livro" => $row['id_livro'],
                            "id_leitor" => $row['id_leitor'],
                            "data_emprestimo" => $row['data_emprestimo'],
                            "data_devolucao" => $row['data_devolucao'],
                            "livro_titulo" => $row['livro_titulo'],
                            "leitor_nome" => $row['leitor_nome']
                        );
                        array_push($emprestimos_arr["emprestimos"], $emprestimo_item);
                        break;
                    }
                }
                
                if (!empty($emprestimos_arr["emprestimos"])) {
                    http_response_code(200);
                    echo json_encode($emprestimos_arr);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Empréstimo não encontrado."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum empréstimo encontrado."));
            }
        } elseif (isset($_GET['leitor'])) {
            $id_leitor = $_GET['leitor'];
            $stmt = $emprestimo->readByLeitor($id_leitor);
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $emprestimos_arr = array();
                $emprestimos_arr["emprestimos"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $emprestimo_item = array(
                        "id_emprestimo" => $row['id_emprestimo'],
                        "id_livro" => $row['id_livro'],
                        "id_leitor" => $row['id_leitor'],
                        "data_emprestimo" => $row['data_emprestimo'],
                        "data_devolucao" => $row['data_devolucao'],
                        "livro_titulo" => $row['livro_titulo'],
                        "leitor_nome" => $row['leitor_nome']
                    );
                    array_push($emprestimos_arr["emprestimos"], $emprestimo_item);
                }
                
                http_response_code(200);
                echo json_encode($emprestimos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum empréstimo encontrado para o leitor especificado."));
            }
        } elseif (isset($_GET['ativos'])) {
            $stmt = $emprestimo->readAtivos();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $emprestimos_arr = array();
                $emprestimos_arr["emprestimos"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $emprestimo_item = array(
                        "id_emprestimo" => $row['id_emprestimo'],
                        "id_livro" => $row['id_livro'],
                        "id_leitor" => $row['id_leitor'],
                        "data_emprestimo" => $row['data_emprestimo'],
                        "data_devolucao" => $row['data_devolucao'],
                        "livro_titulo" => $row['livro_titulo'],
                        "leitor_nome" => $row['leitor_nome']
                    );
                    array_push($emprestimos_arr["emprestimos"], $emprestimo_item);
                }
                
                http_response_code(200);
                echo json_encode($emprestimos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum empréstimo ativo encontrado."));
            }
        } elseif (isset($_GET['concluidos'])) {
            $stmt = $emprestimo->readConcluidos();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $emprestimos_arr = array();
                $emprestimos_arr["emprestimos"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $emprestimo_item = array(
                        "id_emprestimo" => $row['id_emprestimo'],
                        "id_livro" => $row['id_livro'],
                        "id_leitor" => $row['id_leitor'],
                        "data_emprestimo" => $row['data_emprestimo'],
                        "data_devolucao" => $row['data_devolucao'],
                        "livro_titulo" => $row['livro_titulo'],
                        "leitor_nome" => $row['leitor_nome']
                    );
                    array_push($emprestimos_arr["emprestimos"], $emprestimo_item);
                }
                
                http_response_code(200);
                echo json_encode($emprestimos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum empréstimo concluído encontrado."));
            }
        } else {
            $stmt = $emprestimo->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $emprestimos_arr = array();
                $emprestimos_arr["emprestimos"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $emprestimo_item = array(
                        "id_emprestimo" => $row['id_emprestimo'],
                        "id_livro" => $row['id_livro'],
                        "id_leitor" => $row['id_leitor'],
                        "data_emprestimo" => $row['data_emprestimo'],
                        "data_devolucao" => $row['data_devolucao'],
                        "livro_titulo" => $row['livro_titulo'],
                        "leitor_nome" => $row['leitor_nome']
                    );
                    array_push($emprestimos_arr["emprestimos"], $emprestimo_item);
                }
                
                http_response_code(200);
                echo json_encode($emprestimos_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum empréstimo encontrado."));
            }
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_livro) && !empty($data->id_leitor) && !empty($data->data_emprestimo)) {
            $emprestimo->id_livro = $data->id_livro;
            $emprestimo->id_leitor = $data->id_leitor;
            $emprestimo->data_emprestimo = $data->data_emprestimo;
            $emprestimo->data_devolucao = $data->data_devolucao ?? null;
            
            try {
                if ($emprestimo->create()) {
                    http_response_code(201);
                    echo json_encode(array("message" => "Empréstimo criado com sucesso."));
                } else {
                    http_response_code(503);
                    echo json_encode(array("message" => "Não foi possível criar o empréstimo."));
                }
            } catch (Exception $e) {
                http_response_code(400);
                echo json_encode(array("message" => $e->getMessage()));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID do livro, ID do leitor e data de empréstimo são obrigatórios."));
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_emprestimo) && !empty($data->id_livro) && !empty($data->id_leitor) && !empty($data->data_emprestimo)) {
            $emprestimo->id_emprestimo = $data->id_emprestimo;
            $emprestimo->id_livro = $data->id_livro;
            $emprestimo->id_leitor = $data->id_leitor;
            $emprestimo->data_emprestimo = $data->data_emprestimo;
            $emprestimo->data_devolucao = $data->data_devolucao ?? null;
            
            if ($emprestimo->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Empréstimo atualizado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível atualizar o empréstimo."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID do empréstimo, ID do livro, ID do leitor e data de empréstimo são obrigatórios."));
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_emprestimo)) {
            $emprestimo->id_emprestimo = $data->id_emprestimo;
            
            if ($emprestimo->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Empréstimo excluído com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível excluir o empréstimo."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID do empréstimo é obrigatório."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>
