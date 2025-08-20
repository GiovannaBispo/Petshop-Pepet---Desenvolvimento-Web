document.addEventListener('DOMContentLoaded', function() {
  const contatoForm = document.getElementById('contatoForm');
  if (contatoForm) {
      contatoForm.addEventListener('submit', function(e) {
          e.preventDefault();

          const nome = document.getElementById('nome').value;
          const email = document.getElementById('email').value;
          const mensagem = document.getElementById('mensagem').value;

          const resposta = document.getElementById('resposta');
          if (resposta) {
              resposta.innerHTML = `<p>Obrigado, ${nome}! Sua mensagem foi enviada com sucesso.</p>`;
          }

          contatoForm.reset();
      });
  }

  const toggle = document.querySelector('.menu-toggle');
  const navList = document.querySelector('nav ul');

  if (toggle && navList) {
      toggle.addEventListener('click', () => {
          navList.classList.toggle('show');
          toggle.classList.toggle('rotated');
      });
  }
});