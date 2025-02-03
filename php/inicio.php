<?php
include_once('conexao.php');
session_start();

if (!isset($_SESSION['email_sessao'])) {
    header('Location: login.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>
    <link rel="stylesheet" href="../css/inicio.css">
    <title>Início</title>
</head>
<body>
<header>
    <nav>
        <a href="inicio.php"><img id="logo-acad" src="../img/logo-sem-fundo.png" alt=""></a>
        <ul>
            <li><a href="inicio.php">Início</a></li>
            <li><a href="aulas.php">Minhas aulas</a></li>
            <li><a href="gerenciarinstrutores.php">Instrutores</a></li>
            <li><a href="gerenciaralunos.php">Alunos</a></li>
        </ul>

        <?php if (isset($_SESSION['email_sessao'])): ?>
            <div id="perfil-logout">
                <a href="perfil.php">
                    <div id="perfil">
                        <img id="icon-perfil" src="../img/user-vector.png" alt="">
                        <h6><?= $_SESSION['email_sessao'] ?></h6>
                        <h6><?= $_SESSION['tipo_usuario'] ?></h6>
                    </div>
                </a>
                <a href="#" onclick="confirmarSaida();">
                    <div id="logout">
                        <img id="icon-logout" src="../img/logout.png" alt="">
                        <h6>Sair</h6>
                    </div>
                </a>
            </div>
        <?php else: ?>
            <a id="entrar" href="index.php">Entrar</a>
        <?php endif; ?>
    </nav>
</header>
    <main>
        <div id="textos">
            <h1>Bem-vindo à Alpha Gym - Onde sua evolução começa!</h1>
            <p>Na Alpha Gym, acreditamos que cada treino é um passo rumo à sua melhor versão. Com estrutura moderna, equipamentos de ponta e uma equipe dedicada, oferecemos o ambiente ideal para você superar desafios e alcançar seus objetivos. Seja para ganhar força, definir o corpo ou melhorar sua saúde, aqui você encontra tudo o que precisa.
            <br><br>Seu limite é apenas o começo. <b>Treine na Alpha Gym!</b></p>
        </div>
        <img id="img-acad" src="../img/academia.webp" alt="">
    </main>
    <section class="benefits">
    <h2>Por que escolher a Alpha Gym?</h2>
    <div class="benefits-container">

        <div class="benefit">
            <span class="icon">🏋️‍♂️</span>
            <h3>Alta Qualidade</h3>
            <p>Contamos com aparelhos modernos para o melhor desempenho nos treinos.</p>
        </div>

        <div class="benefit">
            <span class="icon">🎯</span>
            <h3>Planos Acessíveis</h3>
            <p>Oferecemos planos flexíveis e acessíveis para atender às suas necessidades e ao seu bolso.</p>
        </div>

        <div class="benefit">
            <span class="icon">🕒</span>
            <h3>Disposição</h3>
            <p>Estamos abertos todos dia da semana, de segunda a segunda das 5:30 as 23:00</p>
        </div>

        <div class="benefit">
            <span class="icon">💪</span>
            <h3>Treinadores</h3>
            <p>Nossa equipe de profissionais está pronta para te auxiliar a alcançar seus objetivos com eficiência.</p>
        </div>

        <div class="benefit">
            <span class="icon">🏆</span>
            <h3>Ambiente Motivador</h3>
            <p>Um espaço preparado para te motivar e proporcionar uma experiência única em cada treino.</p>
        </div>

        <div class="benefit">
            <span class="icon">🤝</span>
            <h3>Comunidade Fitness</h3>
            <p>Junte-se a uma comunidade de pessoas focadas no bem-estar e na evolução física.</p>
        </div>
        <div class="benefit">
            <span class="icon">🛡️</span>
            <h3>Segurança e Conforto</h3>
            <p>Ambiente seguro e higienizado, garantindo bem-estar durante os treinos.</p>
        </div>

        <!-- Novo Card 2 -->
        <div class="benefit">
            <span class="icon">🥗</span>
            <h3>Dicas de Nutrição</h3>
            <p>Receba orientações sobre alimentação para potencializar seus resultados.</p>
        </div>
    </div>
    </div>
</section>
<section class="planos">
    <h2>Escolha o Plano Perfeito para Você!</h2>
    <div class="planos-container">

        <!-- Plano 1 -->
        <div class="plano">
            <h3>Plano Básico</h3>
            <p class="preco">R$ 59,90/mês</p>
            <ul>
                <li>✅ Acesso livre à academia</li>
                <li>✅ Funcionamento 24h</li>
                <li>✅ Máquinas e pesos livres</li>
                <li>❌ Acompanhamento profissional</li>
                <li>❌ Aulas coletivas</li>
            </ul>
            <button>Assinar Agora</button>
        </div>

        <!-- Plano 2 -->
        <div class="plano destaque">
            <h3>Plano Premium</h3>
            <p class="preco">R$ 89,90/mês</p>
            <ul>
                <li>✅ Acesso total à academia</li>
                <li>✅ Personal Trainer 1x/semana</li>
                <li>✅ Aulas coletivas e spinning</li>
                <li>✅ Área exclusiva de musculação</li>
                <li>❌ Nutricionista incluso</li>
            </ul>
            <button>Assinar Agora</button>
        </div>

        <!-- Plano 3 -->
        <div class="plano">
            <h3>Plano Black</h3>
            <p class="preco">R$ 129,90/mês</p>
            <ul>
                <li>✅ Acesso VIP 24h</li>
                <li>✅ Personal Trainer ilimitado</li>
                <li>✅ Nutricionista incluso</li>
                <li>✅ Todas as modalidades</li>
                <li>✅ Suplementos com desconto</li>
            </ul>
            <button>Assinar Agora</button>
        </div>

    </div>
</section>
    <footer>
        <h2>Desenvolvido por:</h2>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-gustavo-mota-ramos-9b60242a2/" target="_blank">João Gustavo Mota Ramos</a>
        <a href="https://www.linkedin.com/in/jo%C3%A3o-pedro-da-cunha-machado-2089482b7/" target="_blank">João Pedro da Cunha Machado</a>
    </footer>
    <script>
        function confirmarSaida() {
            Swal.fire({
                title: 'Você tem certeza?',
                text: "Você quer sair de sua conta?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sair',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redireciona para o logout
                    window.location.href = 'logout.php';
                }
            });
        }
    </script>
</body>
</html>