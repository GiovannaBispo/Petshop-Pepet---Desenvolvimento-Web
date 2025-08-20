<?php
session_start();
include("conexao.php");

header('Content-Type: application/json');

$dados = json_decode(file_get_contents("php://input"), true);

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo_usuario'] != 1) {
    echo json_encode(['erro' => 'Permissão negada']);
    exit();
}

$id = intval($dados['id']);
$novo_status = $conn->real_escape_string($dados['status']);

$permitidos = ['aberto', 'andamento', 'finalizado', 'cancelado'];
if (!in_array($novo_status, $permitidos)) {
    echo json_encode(['erro' => 'Status inválido']);
    exit();
}

$sql = "UPDATE agendamentos SET status = '$novo_status' WHERE id = $id";

if ($conn->query($sql)) {
    echo json_encode(['sucesso' => true]);
} else {
    echo json_encode(['erro' => 'Erro ao atualizar status']);
}
?>
