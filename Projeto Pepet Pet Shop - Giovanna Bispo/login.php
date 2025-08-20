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
            <h2>Login</h2>
            
            <?php if (isset($_GET['erro'])): ?>
                <div id="mensagem" style="color: red; margin-bottom: 10px; background: #ff00003b; padding: 4px 14px; border: none; border-radius: 8px;">
                    <?= htmlspecialchars($_GET['erro']) ?>
                </div>
            <?php endif; ?>

            <form action="verificar_login.php" method="POST" id="form-login">
                <input type="email" name="email" placeholder="Email" required>
                <input type="password" name="senha" placeholder="Senha" required>
                <button type="submit" id="login">Entrar</button>
            </form>
            <a href="index.php">← Voltar para o início</a>
            <a href="cadastro.php">Não tem cadastro? Cadastre-se</a>
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