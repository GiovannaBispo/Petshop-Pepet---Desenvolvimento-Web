<?php
session_start();
$logado = isset($_SESSION['usuario']);
$tipo_usuario = $logado ? $_SESSION['usuario']['tipo_usuario'] : null;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Pepet Pet Shop</title>
  <link rel="shortcut icon" href="assets/imagens/logo-site.jpg" type="image/x-icon">
  <link rel="stylesheet" href="assets/css/estiloIndex.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;600&display=swap" rel="stylesheet">
</head>
<body id="topo">
  <header>
    <div class="inicio-imagem">
      <a href="#inicio">
        <img src="assets/imagens/logo.png" id="logo-inicio" alt="Logo da Pepet Pet Shop">
      </a>
    </div>

    <button class="menu-toggle" onclick="toggleMenu()">☰</button>

    <nav>
      <ul>
        <li><a href="#inicio">Início</a></li>
        <li><a href="#produtos">Produtos</a></li>
        <li><a href="#servicos">Serviços</a></li>
        <li><a href="#contato">Contato</a></li>
        <li>
          <a href="<?= $logado ? 'formulario.php' : 'aviso.html' ?>">Agende conosco</a>
        </li>
        <?php if ($logado): ?>
          <li><a href="agendamentos.php">Agendamentos</a></li>
          <?php if ($tipo_usuario == 1): ?>
            <li><a href="painel.php">Funcionários</a></li>
          <?php endif; ?>
          <li><a href="logout.php">Sair</a></li>
        <?php else: ?>
          <li><a href="login.php">Login</a></li>
        <?php endif; ?>
      </ul>
    </nav>
  </header>

  <main>
    <section id="inicio" style="background: #f8f8f8; padding: 40px; border-radius: 15px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);">
      <h2 style="text-align: center;">Bem-vindo à Pepet! 🐾</h2>
      <div class="inicio-container" style="display: flex; justify-content: center; align-items: center; flex-direction: column;">
        <div class="inicio-texto" style="max-width: 800px; text-align: center;">
          <p>Na Pepet, cada rabinho abanando e cada miadinho feliz é o que nos motiva todos os dias!  
          Somos um pet shop apaixonado por oferecer os melhores produtos e serviços para quem mais amamos: nossos pets.  
          Com carinho, cuidado e qualidade, cuidamos do seu melhor amigo como se fosse nosso.  
          Vem conhecer esse mundo cheio de amor! 💖</p>
        </div>
      </div>
    </section>

    <section id="produtos">
      <h2>Produtos</h2>
      <div class="produtos-container">
        <div class="produto-card">
          <img src="assets/imagens/racao.jpg" alt="Ração Premium">
          <h3>Ração Premium</h3>
          <p>Nutrição de qualidade para seu pet crescer forte e saudável!</p>
        </div>
        <div class="produto-card">
          <img src="assets/imagens/brinquedo.jpg" alt="Brinquedos Divertidos">
          <h3>Brinquedos Divertidos</h3>
          <p>Brinquedos seguros para garantir a diversão e felicidade do seu bichinho.</p>
        </div>
        <div class="produto-card">
          <img src="assets/imagens/cama.jpg" alt="Caminhas Confortáveis">
          <h3>Caminhas Confortáveis</h3>
          <p>O melhor conforto para o soninho do seu pet!</p>
        </div>
      </div>
    </section>

    <section id="servicos">
      <h2>Serviços</h2>
      <div class="servicos-container">
        <div class="servico-card">
          <img src="assets/imagens/banho.jpeg" alt="Banho & Tosa">
          <h3>Banho & Tosa</h3>
          <p>Deixe seu pet ainda mais lindo e cheiroso com nosso serviço especializado.</p>
        </div>
        <div class="servico-card">
          <img src="assets/imagens/veterinario.png" alt="Consultas Veterinárias">
          <h3>Consultas Veterinárias</h3>
          <p>Profissionais qualificados para cuidar da saúde do seu pet.</p>
        </div>
        <div class="servico-card">
          <img src="assets/imagens/hotel.jpeg" alt="Hotelzinho Pet">
          <h3>Hotelzinho Pet</h3>
          <p>Hospedagem segura e amorosa para seu bichinho enquanto você viaja.</p>
        </div>
      </div>
    </section>
  </main>

  <footer>
    <section id="contato">
      <h2>Contato</h2>
      <div class="contato-container">
        <p>📞 Telefone: (11) 1234-5678</p>
        <p>📧 E-mail: contato@pepetpetshop.com.br</p>
        <p>📍 Endereço: Rua dos Bichinhos Felizes, 123 - São Paulo/SP</p>
      </div>
      <form class="formulario-contato">
        <input type="text" placeholder="Seu nome" required />
        <input type="email" placeholder="Seu e-mail" required />
        <textarea placeholder="Sua mensagem" required></textarea>
        <button type="submit">Enviar Mensagem</button>
      </form>
    </section>
    <p>&copy; 2025 PetShop Fofura 🐾 Todos os direitos reservados.</p>
  </footer>

  <a href="#topo" class="botao-flutuante" title="Voltar ao topo">⬆</a>

  <script src="assets/js/petshop.js" defer></script>
</body>
</html>
