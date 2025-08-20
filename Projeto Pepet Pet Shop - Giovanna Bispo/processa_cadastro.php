<?php
include('conexao.php');

$nome = $_POST['nome_completo'];
$cpf = $_POST['cpf'];
$email = $_POST['email'];
$senha = $_POST['senha'];
$confirmar_senha = $_POST['confirmar_senha'];
if (strpos($email, '@pepet') !== false) {
    $tipo_usuario = 1; // Funcionário
} else {
    $tipo_usuario = 0; // Usuário comum
}

if ($senha != $confirmar_senha) {
    header('Location: cadastro.php?erro=Senhas não coincidem');
    exit();
}

$verifica_cpf = $conn->prepare("SELECT id FROM usuarios WHERE cpf = ?");
$verifica_cpf->bind_param("s", $cpf);
$verifica_cpf->execute();
$verifica_cpf->store_result();

if ($verifica_cpf->num_rows > 0) {
    header('Location: cadastro.php?erro=CPF já cadastrado');
    exit();
}

$senha_hash = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO usuarios (nome_completo, cpf, email, senha, tipo_usuario) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssssi", $nome, $cpf, $email, $senha_hash, $tipo_usuario);

if ($stmt->execute()) {
    header('Location: cadastro.php?sucesso=Cadastro realizado com sucesso');
} else {
    header('Location: cadastro.php?erro=Erro ao cadastrar usuário');
}

$stmt->close();
$conn->close();
?>
