<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: aviso.html");
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];
$pet_id = null;

if (!empty($_POST['pet_existente'])) {
    $pet_id = $_POST['pet_existente'];
} else {
    $nome_pet = $_POST['nome_pet'] ?? '';
    $raca = $_POST['raca_select'] ?? '';
    $raca_outro = $_POST['raca_outro'] ?? '';
    $raca_final = ($raca === 'Outro') ? $raca_outro : $raca;

    $stmt_pet = $conn->prepare("INSERT INTO pets (usuario_id, nome_pet, raca) VALUES (?, ?, ?)");
    $stmt_pet->bind_param("iss", $usuario_id, $nome_pet, $raca_final);
    if ($stmt_pet->execute()) {
        $pet_id = $stmt_pet->insert_id;
    } else {
        die("Erro ao cadastrar novo pet: " . $conn->error);
    }
}

$servico = $_POST['servico'] ?? '';
$data_agendamento = $_POST['data_agendamento'] ?? '';
$telefone = $_POST['telefone'] ?? '';
$obs_add = $_POST['obs_add'] ?? '';
$status = 'aberto';

$stmt_verifica = $conn->prepare("SELECT COUNT(*) AS total FROM agendamentos WHERE data_agendamento = ?");
$stmt_verifica->bind_param("s", $data_agendamento);
$stmt_verifica->execute();
$result_verifica = $stmt_verifica->get_result();
$row = $result_verifica->fetch_assoc();
if ($row['total'] > 0) {
    echo "Horário já ocupado. Por favor, escolha outro.";
    exit();
}

$stmt = $conn->prepare("INSERT INTO agendamentos (usuario_id, pet_id, servico, data_agendamento, telefone, obs_add, status) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("iisssss", $usuario_id, $pet_id, $servico, $data_agendamento, $telefone, $obs_add, $status);

if ($stmt->execute()) {
    header("Location: agendamentos.php");
    exit();
} else {
    echo "Erro ao agendar: " . $conn->error;
}

?>
