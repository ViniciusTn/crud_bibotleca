<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Autor.php';

$database = new Database();
$db = $database->getConnection();
$autor = new Autor($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            // Get single autor
            $autor->id_autor = $_GET['id'];
            $stmt = $autor->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $autores_arr = array();
                $autores_arr["autores"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['id_autor'] == $autor->id_autor) {
                        $autor_item = array(
                            "id_autor" => $row['id_autor'],
                            "nome" => $row['nome'],
                            "nacionalidade" => $row['nacionalidade'],
                            "ano_nascimento" => $row['ano_nascimento']
                        );
                        array_push($autores_arr["autores"], $autor_item);
                        break;
                    }
                }
                
                if (!empty($autores_arr["autores"])) {
                    http_response_code(200);
                    echo json_encode($autores_arr);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Autor não encontrado."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum autor encontrado."));
            }
        } else {
            // Get all autores
            $stmt = $autor->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $autores_arr = array();
                $autores_arr["autores"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $autor_item = array(
                        "id_autor" => $row['id_autor'],
                        "nome" => $row['nome'],
                        "nacionalidade" => $row['nacionalidade'],
                        "ano_nascimento" => $row['ano_nascimento']
                    );
                    array_push($autores_arr["autores"], $autor_item);
                }
                
                http_response_code(200);
                echo json_encode($autores_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum autor encontrado."));
            }
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->nome)) {
            $autor->nome = $data->nome;
            $autor->nacionalidade = $data->nacionalidade ?? '';
            $autor->ano_nascimento = $data->ano_nascimento ?? null;
            
            if ($autor->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Autor criado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o autor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. Nome é obrigatório."));
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_autor) && !empty($data->nome)) {
            $autor->id_autor = $data->id_autor;
            $autor->nome = $data->nome;
            $autor->nacionalidade = $data->nacionalidade ?? '';
            $autor->ano_nascimento = $data->ano_nascimento ?? null;
            
            if ($autor->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Autor atualizado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível atualizar o autor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID e nome são obrigatórios."));
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_autor)) {
            $autor->id_autor = $data->id_autor;
            
            if ($autor->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Autor excluído com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível excluir o autor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID do autor é obrigatório."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>
