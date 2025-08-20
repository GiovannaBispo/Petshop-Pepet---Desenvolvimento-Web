<?php
session_start();
include("conexao.php");

header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['usuario'])) {
    echo json_encode(['erro' => 'Usuário não autenticado']);
    exit();
}

$id = intval($dados['id']);
$servico = $conn->real_escape_string($dados['servico']);
$telefone = $conn->real_escape_string($dados['telefone']);
$usuario_id = $_SESSION['usuario']['id'];

$sql = "UPDATE agendamentos SET servico = '$servico', telefone = '$telefone' 
        WHERE id = $id AND usuario_id = $usuario_id AND status = 'aberto'";

if ($conn->query($sql)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar agendamento']);
}
?>
