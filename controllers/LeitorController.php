<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Leitor.php';

$database = new Database();
$db = $database->getConnection();
$leitor = new Leitor($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $leitor->id_leitor = $_GET['id'];
            $stmt = $leitor->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $leitores_arr = array();
                $leitores_arr["leitores"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['id_leitor'] == $leitor->id_leitor) {
                        $leitor_item = array(
                            "id_leitor" => $row['id_leitor'],
                            "nome" => $row['nome'],
                            "email" => $row['email'],
                            "telefone" => $row['telefone']
                        );
                        array_push($leitores_arr["leitores"], $leitor_item);
                        break;
                    }
                }
                
                if (!empty($leitores_arr["leitores"])) {
                    http_response_code(200);
                    echo json_encode($leitores_arr);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Leitor não encontrado."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum leitor encontrado."));
            }
        } else {
            $stmt = $leitor->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $leitores_arr = array();
                $leitores_arr["leitores"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $leitor_item = array(
                        "id_leitor" => $row['id_leitor'],
                        "nome" => $row['nome'],
                        "email" => $row['email'],
                        "telefone" => $row['telefone']
                    );
                    array_push($leitores_arr["leitores"], $leitor_item);
                }
                
                http_response_code(200);
                echo json_encode($leitores_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum leitor encontrado."));
            }
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->nome)) {
            $leitor->nome = $data->nome;
            $leitor->email = $data->email ?? '';
            $leitor->telefone = $data->telefone ?? '';
            
            if ($leitor->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Leitor criado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o leitor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. Nome é obrigatório."));
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_leitor) && !empty($data->nome)) {
            $leitor->id_leitor = $data->id_leitor;
            $leitor->nome = $data->nome;
            $leitor->email = $data->email ?? '';
            $leitor->telefone = $data->telefone ?? '';
            
            if ($leitor->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Leitor atualizado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível atualizar o leitor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID e nome são obrigatórios."));
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_leitor)) {
            $leitor->id_leitor = $data->id_leitor;
            
            if ($leitor->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Leitor excluído com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível excluir o leitor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID do leitor é obrigatório."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>
