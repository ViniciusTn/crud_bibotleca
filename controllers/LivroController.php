<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Livro.php';

$database = new Database();
$db = $database->getConnection();
$livro = new Livro($db);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $livro->id_livro = $_GET['id'];
            $stmt = $livro->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $livros_arr = array();
                $livros_arr["livros"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    if ($row['id_livro'] == $livro->id_livro) {
                        $livro_item = array(
                            "id_livro" => $row['id_livro'],
                            "titulo" => $row['titulo'],
                            "genero" => $row['genero'],
                            "ano_publicacao" => $row['ano_publicacao'],
                            "id_autor" => $row['id_autor'],
                            "autor_nome" => $row['autor_nome']
                        );
                        array_push($livros_arr["livros"], $livro_item);
                        break;
                    }
                }
                
                if (!empty($livros_arr["livros"])) {
                    http_response_code(200);
                    echo json_encode($livros_arr);
                } else {
                    http_response_code(404);
                    echo json_encode(array("message" => "Livro não encontrado."));
                }
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum livro encontrado."));
            }
        } elseif (isset($_GET['genero'])) {
            $genero = $_GET['genero'];
            $stmt = $livro->readByGenre($genero);
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $livros_arr = array();
                $livros_arr["livros"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $livro_item = array(
                        "id_livro" => $row['id_livro'],
                        "titulo" => $row['titulo'],
                        "genero" => $row['genero'],
                        "ano_publicacao" => $row['ano_publicacao'],
                        "id_autor" => $row['id_autor'],
                        "autor_nome" => $row['autor_nome']
                    );
                    array_push($livros_arr["livros"], $livro_item);
                }
                
                http_response_code(200);
                echo json_encode($livros_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum livro encontrado para o gênero especificado."));
            }
        } elseif (isset($_GET['autor'])) {
            $id_autor = $_GET['autor'];
            $stmt = $livro->readByAuthor($id_autor);
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $livros_arr = array();
                $livros_arr["livros"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $livro_item = array(
                        "id_livro" => $row['id_livro'],
                        "titulo" => $row['titulo'],
                        "genero" => $row['genero'],
                        "ano_publicacao" => $row['ano_publicacao'],
                        "id_autor" => $row['id_autor'],
                        "autor_nome" => $row['autor_nome']
                    );
                    array_push($livros_arr["livros"], $livro_item);
                }
                
                http_response_code(200);
                echo json_encode($livros_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum livro encontrado para o autor especificado."));
            }
        } elseif (isset($_GET['ano'])) {
            $ano = $_GET['ano'];
            $stmt = $livro->readByYear($ano);
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $livros_arr = array();
                $livros_arr["livros"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $livro_item = array(
                        "id_livro" => $row['id_livro'],
                        "titulo" => $row['titulo'],
                        "genero" => $row['genero'],
                        "ano_publicacao" => $row['ano_publicacao'],
                        "id_autor" => $row['id_autor'],
                        "autor_nome" => $row['autor_nome']
                    );
                    array_push($livros_arr["livros"], $livro_item);
                }
                
                http_response_code(200);
                echo json_encode($livros_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum livro encontrado para o ano especificado."));
            }
        } else {
            $stmt = $livro->read();
            $num = $stmt->rowCount();
            
            if ($num > 0) {
                $livros_arr = array();
                $livros_arr["livros"] = array();
                
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $livro_item = array(
                        "id_livro" => $row['id_livro'],
                        "titulo" => $row['titulo'],
                        "genero" => $row['genero'],
                        "ano_publicacao" => $row['ano_publicacao'],
                        "id_autor" => $row['id_autor'],
                        "autor_nome" => $row['autor_nome']
                    );
                    array_push($livros_arr["livros"], $livro_item);
                }
                
                http_response_code(200);
                echo json_encode($livros_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Nenhum livro encontrado."));
            }
        }
        break;
        
    case 'POST':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->titulo) && !empty($data->ano_publicacao) && !empty($data->id_autor)) {
            $livro->titulo = $data->titulo;
            $livro->genero = $data->genero ?? '';
            $livro->ano_publicacao = $data->ano_publicacao;
            $livro->id_autor = $data->id_autor;
            
            if ($livro->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Livro criado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível criar o livro."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. Título, ano de publicação e ID do autor são obrigatórios."));
        }
        break;
        
    case 'PUT':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_livro) && !empty($data->titulo) && !empty($data->ano_publicacao) && !empty($data->id_autor)) {
            $livro->id_livro = $data->id_livro;
            $livro->titulo = $data->titulo;
            $livro->genero = $data->genero ?? '';
            $livro->ano_publicacao = $data->ano_publicacao;
            $livro->id_autor = $data->id_autor;
            
            if ($livro->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Livro atualizado com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível atualizar o livro."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Dados incompletos. ID, título, ano de publicação e ID do autor são obrigatórios."));
        }
        break;
        
    case 'DELETE':
        $data = json_decode(file_get_contents("php://input"));
        
        if (!empty($data->id_livro)) {
            $livro->id_livro = $data->id_livro;
            
            if ($livro->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Livro excluído com sucesso."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Não foi possível excluir o livro."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "ID do livro é obrigatório."));
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(array("message" => "Método não permitido."));
        break;
}
?>
