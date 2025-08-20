<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pepet</title>
    <link rel="shortcut icon" href="assets/imagens/logo-site.jpg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/estiloLogin.css">
</head>
<body>
    <header>
        <div class="inicio-imagem">
            <a href="index.php">
                <img src="assets/imagens/logo.png" id="logo-inicio" alt="Logo da Pepet Pet Shop">
            </a>
        </div>
    </header>

    <main id="main_login">
        <div id="div_login">
            <h2>Cadastro</h2>

            <?php if (isset($_GET['erro'])): ?>
                <div id="mensagem" style="color: #ff0000; margin-bottom: 10px; background: #ff00003b; padding: 4px 14px; border: none; border-radius: 8px;">
                    <?= htmlspecialchars($_GET['erro']) ?>
                </div>
            <?php elseif (isset($_GET['sucesso'])): ?>
                <div style="color: green; margin-bottom: 10px; background: #d4edda; padding: 4px 14px; border: none; border-radius: 8px;">
                    <?= htmlspecialchars($_GET['sucesso']) ?>
                    <br>
                    <a href="login.php"><button>Voltar ao Login</button></a>
                </div>
            <?php endif; ?>

            <form action="processa_cadastro.php" method="POST" id="form-login">
                <input type="text" name="nome_completo" placeholder="Nome completo" required>
                <input type="text" name="cpf" placeholder="CPF (xxx.xxx.xxx-xx)" required pattern="\d{3}\.\d{3}\.\d{3}-\d{2}">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <input type="password" name="confirmar_senha" placeholder="Confirmar senha" required>
                <button type="submit" id="login">Cadastrar</button>
            </form>
            <a href="login.php">← Voltar para login</a>
        </div>
    </main>
</body>
</html>

<script>
    setTimeout(function() {
        var msg = document.getElementById('mensagem');
        if (msg) {
            msg.style.display = 'none';
        }
    }, 4000);
</script>
