<?php
session_start();

$email = $_POST['email'];
$senha = $_POST['senha'];

$conn = new mysqli('localhost', 'root', '', 'pepet');

if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

$sql = "SELECT id, nome_completo, senha, tipo_usuario FROM usuarios WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    $stmt->bind_result($id, $nome, $senha_hash, $tipo_usuario);
    $stmt->fetch();

    if (password_verify($senha, $senha_hash)) {
        $_SESSION['usuario'] = [
            'id' => $id,
            'nome' => $nome,
            'tipo_usuario' => $tipo_usuario
        ];
        header("Location: agendamentos.php");
    } else {
        header("Location: login.php?erro=Senha incorreta.");
    }
} else {
    header("Location: login.php?erro=Email não encontrado.");
}

$stmt->close();
$conn->close();
?>
