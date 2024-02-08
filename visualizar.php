<?php
// conexão com banco de dados
include_once "conexao.php";

// Receber a página via método GET
$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_NUMBER_INT);

// Verificar se o ID não está vazia
if (!empty($id)) {
    // Selecionar usuario e endereço
    $query_usuarios = "SELECT usr.id, usr.nome, usr.email,
        ende.logradouro, ende.numero
        FROM usuarios AS usr
        LEFT JOIN enderecos AS ende ON ende.usuario_id=usr.id
        WHERE usr.id=:id LIMIT 1";
    $result_usuarios = $conn->prepare($query_usuarios);
    $result_usuarios->bindParam(':id', $id);
    $result_usuarios->execute();

    // Verifica se a consulta foi bem sucedida e tem registro
    if (($result_usuarios) and ($result_usuarios->rowCount() !=0)) {
        $row_usuario = $result_usuarios->fetch(PDO::FETCH_ASSOC);
        $retorna = ['status' => true, 'dados' => $row_usuario]; 
    } else {
        $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
    }
} else {
    $retorna = ['status' => false, 'msg' => "<div class='alert alert-danger' role='alert'>Erro: Nenhum usuário encontrado!</div>"];
}
// Retornar o resultado em formato JSON
echo json_encode($retorna);
