<?php
session_start();
include("conexao.php");

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario_id = $_SESSION['usuario']['id'];
$tipo_usuario = $_SESSION['usuario']['tipo_usuario'];

if ($tipo_usuario == 1) {
    $sql = "SELECT ag.*, p.nome_pet, p.raca, u.nome_completo AS nome_dono
            FROM agendamentos ag
            JOIN pets p ON ag.pet_id = p.id
            JOIN usuarios u ON ag.usuario_id = u.id";
} else {
    $sql = "SELECT ag.*, p.nome_pet, p.raca, u.nome_completo AS nome_dono
            FROM agendamentos ag
            JOIN pets p ON ag.pet_id = p.id
            JOIN usuarios u ON ag.usuario_id = u.id
            WHERE ag.usuario_id = $usuario_id";
}

$result = $conn->query($sql);

$agendamentos = [
    'aberto' => [],
    'andamento' => [],
    'finalizado' => [],
    'cancelado' => []
];

while ($row = $result->fetch_assoc()) {
    $status = $row['status'];
    if (!isset($agendamentos[$status])) {
        $status = 'aberto';
    }
    $agendamentos[$status][] = $row;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="shortcut icon" href="assets/imagens/logo-site.jpg" type="image/x-icon">
        <title>Agendamento - Pepet</title>
        <link rel="stylesheet" href="assets/css/estiloAgendamento.css">
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
                    <li><a href="formulario.php">Agende conosco</a></li>
                </ul>
            </nav>
        </header>
        <h1>Agendamentos <span>&#128062;</span></h1>  
        <div class="kanban">

            <?php foreach ($agendamentos as $status => $agend_list): ?>
            <div class="coluna" data-status="<?= $status ?>">
                <h2><?= ucfirst($status) ?></h2>

                <?php if (count($agend_list) === 0): ?>
                    <p><em>Sem agendamentos</em></p>
                <?php endif; ?>

                <?php foreach ($agend_list as $ag): ?>
                <div class="card" data-id="<?= $ag['id'] ?>">
                    <strong>Pet: <?= htmlspecialchars($ag['nome_pet']) ?></strong>
                    <div><b>Raça:</b> <?= htmlspecialchars($ag['raca']) ?></div>
                    <div><b>Serviço:</b> 
                        <?php if ($tipo_usuario == 0 && $ag['status'] == 'aberto'): ?>
                            <input type="text" class="servico-input" value="<?= htmlspecialchars($ag['servico']) ?>" />
                        <?php else: ?>
                            <?= htmlspecialchars($ag['servico']) ?>
                        <?php endif; ?>
                    </div>
                    <div><b>Horario:</b> <?= htmlspecialchars($ag['data_agendamento']) ?></div>
                    <div><b>Dono:</b> <?= htmlspecialchars($ag['nome_dono']) ?></div>
                    <div><b>Telefone:</b> 
                        <?php if ($tipo_usuario == 0 && $ag['status'] == 'aberto'): ?>
                            <input type="tel" class="telefone-input" value="<?= htmlspecialchars($ag['telefone']) ?>" />
                        <?php else: ?>
                            <?= htmlspecialchars($ag['telefone']) ?>
                        <?php endif; ?>
                    </div>
                    <?php if ($tipo_usuario == 1): ?>
                    <div><b>Status:</b>
                        <select class="status-select">
                            <?php
                            $options = ['aberto', 'andamento', 'finalizado', 'cancelado'];
                            foreach ($options as $opt) {
                                $sel = ($ag['status'] === $opt) ? 'selected' : '';
                                echo "<option value=\"$opt\" $sel>$opt</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <?php else: ?>
                        <div><b>Status:</b> <?= htmlspecialchars($ag['status']) ?></div>
                    <?php endif; ?>

                    <div class="acoes">
                        <?php if ($tipo_usuario == 0 && $ag['status'] == 'aberto'): ?>
                            <button class="btn btn-edit" onclick="editarAgendamento(<?= $ag['id'] ?>, this)">Salvar</button>
                            <button class="btn btn-cancel" onclick="cancelarAgendamento(<?= $ag['id'] ?>)">Cancelar</button>
                        <?php elseif ($tipo_usuario == 1): ?>
                            <button class="btn btn-status" onclick="atualizarStatus(<?= $ag['id'] ?>, this)">Atualizar Status</button>
                        <?php else: ?>
                            <button class="btn btn-disabled" disabled>Nenhuma ação</button>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>

            </div>
            <?php endforeach; ?>

        </div>

    <script>
        // Funções de edição, cancelamento e atualização do status (via fetch para endpoints PHP)

        function editarAgendamento(id, btn) {
            const card = btn.closest('.card');
            const servico = card.querySelector('.servico-input').value;
            const telefone = card.querySelector('.telefone-input').value;

            fetch('editar_agendamento.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, servico, telefone})
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Agendamento atualizado!');
                } else {
                    alert('Erro: ' + data.erro);
                }
            })
            .catch(() => alert('Erro na requisição'));
        }

        function cancelarAgendamento(id) {
            if (!confirm('Deseja cancelar este agendamento?')) return;

            fetch('cancelar_agendamento.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id})
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Agendamento cancelado!');
                    location.reload();
                } else {
                    alert('Erro: ' + data.erro);
                }
            })
            .catch(() => alert('Erro na requisição'));
        }

        function atualizarStatus(id, btn) {
            const card = btn.closest('.card');
            const select = card.querySelector('.status-select');
            const novoStatus = select.value;

            fetch('atualizar_status.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/json'},
                body: JSON.stringify({id, status: novoStatus})
            })
            .then(res => res.json())
            .then(data => {
                if (data.sucesso) {
                    alert('Status atualizado!');
                    location.reload();
                } else {
                    alert('Erro: ' + data.erro);
                }
            })
            .catch(() => alert('Erro na requisição'));
        }
    </script>
    </body>
</html>
