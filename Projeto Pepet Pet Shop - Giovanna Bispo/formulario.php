<?php
session_start();
if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['id'])) {
    header("Location: aviso.html");
    exit();
}
include("conexao.php");

$horarios_ocupados = [];
$data_atual = date('Y-m-d');
$sql = "SELECT data_agendamento FROM agendamentos WHERE DATE(data_agendamento) >= ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $data_atual);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $horarios_ocupados[] = $row['data_agendamento'];
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulário - Pepet</title>
    <link rel="shortcut icon" href="assets/imagens/logo-site.jpg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/estiloFormulario.css">
</head>
<body>
    <header>
        <div class="inicio-imagem">
            <a href="index.php">
                <img src="assets/imagens/logo.png" id="logo-inicio" alt="Logo da Pepet Pet Shop">
            </a>
        </div>
        <nav>
            <ul>
                <li><a href="agendamentos.php">Agendamentos</a></li>
            </ul>
        </nav>
    </header>

    <h1>Formulário de agendamento <span>🐾</span></h1>
    <form action="salvar_agendamento.php" method="POST">
        <label for="pet_existente">🐕 Escolha um pet já cadastrado:</label>
        <select name="pet_existente" id="pet_existente">
            <option value="">-- Nenhum, vou cadastrar um novo --</option>
            <?php
            $usuario_id = $_SESSION['usuario']['id'];
            $result = $conn->query("SELECT id, nome_pet FROM pets WHERE usuario_id = $usuario_id");
            while ($row = $result->fetch_assoc()) {
                echo "<option value='{$row['id']}'>{$row['nome_pet']}</option>";
            }
            ?>
        </select>

        <div id="novo_pet_fields">
            <label for="nome_pet">🐶 Nome do Pet:</label>
            <input type="text" name="nome_pet">

            <label for="raca_select">🐾 Raça:</label>
            <select name="raca_select" id="raca_select" onchange="mostrarOutroCampo()">
                <option value="Labrador">Labrador</option>
                <option value="Poodle">Poodle</option>
                <option value="Shih Tzu">Shih Tzu</option>
                <option value="Vira-lata">Vira-lata</option>
                <option value="Golden Retriever">Golden Retriever</option>
                <option value="Bulldog Francês">Bulldog Francês</option>
                <option value="Yorkshire">Yorkshire</option>
                <option value="Chihuahua">Chihuahua</option>
                <option value="Outro">Outro</option>
            </select>
            <input type="text" name="raca_outro" id="raca_outro" placeholder="Digite a raça" style="display:none;">
        </div>

        <label for="servico">🛁 Serviço:</label>
        <select name="servico" required>
            <option value="">Selecione um serviço</option>
            <option value="Banho">Banho</option>
            <option value="Tosa">Tosa</option>
            <option value="Banho e Tosa">Banho e Tosa</option>
            <option value="Consulta">Consulta</option>
            <option value="Hotelzinho Pet">Hotelzinho Pet</option>
            <option value="Passeio">Passeio</option>
            <option value="Adestramento">Adestramento</option>
        </select>

        <label for="data_agendamento">📅 Data e Hora:</label>
        <select name="data_agendamento" required>
            <option value="">Selecione um horário</option>
            <?php
            $inicio = new DateTime('08:00');
            $fim = new DateTime('18:00');
            $intervalo = new DateInterval('PT30M');
            $periodo = new DatePeriod($inicio, $intervalo, $fim);

            $hoje = new DateTime();
            for ($dia = 0; $dia < 7; $dia++) {
                $data = (clone $hoje)->modify("+$dia days")->format('Y-m-d');
                foreach ($periodo as $hora) {
                    $horario_completo = $data . ' ' . $hora->format('H:i:s');
                    if (!in_array($horario_completo, $horarios_ocupados)) {
                        $value = $data . 'T' . $hora->format('H:i');
                        $label = $data . ' - ' . $hora->format('H:i');
                        echo "<option value=\"$value\">$label</option>";
                    }
                }
            }
            ?>
        </select>

        <label for="telefone">📞 Telefone:</label>
        <input type="tel" name="telefone" id="telefone" required placeholder="(XX) XXXXX-XXXX">

        <label for="obs_add">📝 Observações adicionais:</label>
        <textarea name="obs_add"></textarea>

        <button type="submit">Agendar</button>
    </form>

    <script>
        function mostrarOutroCampo() {
            const select = document.getElementById('raca_select');
            const outro = document.getElementById('raca_outro');
            outro.style.display = (select.value === 'Outro') ? 'block' : 'none';
        }

        document.getElementById('pet_existente').addEventListener('change', function () {
            document.getElementById('novo_pet_fields').style.display = this.value ? 'none' : 'block';
        });

        document.addEventListener('DOMContentLoaded', () => {
            document.getElementById('novo_pet_fields').style.display = document.getElementById('pet_existente').value ? 'none' : 'block';

            const telefone = document.getElementById('telefone');
            telefone.addEventListener('input', () => {
                let v = telefone.value.replace(/\D/g, '');
                v = v.replace(/^(\d{2})(\d)/g, '($1) $2');
                v = v.replace(/(\d{5})(\d)/, '$1-$2');
                telefone.value = v.substring(0, 15);
            });
        });
    </script>
</body>
</html>
