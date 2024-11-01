<?php
session_start();
require_once('startup/connectBD.php');

// Obtém o ID da categoria da URL, se estiver presente
$id_categoria = isset($_GET['categoria']) ? intval($_GET['categoria']) : null;

$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';

$query = "SELECT id_termo, nome_termo, definicao_termo" . ($id_categoria == 17 ? ", expressao_termo" : "") . " FROM termos";
if ($id_categoria) {
    $query .= " WHERE categorias_id_categoria = $id_categoria";
}

if ($searchTerm) {
    $searchTerm = $mysqli->real_escape_string($searchTerm); // Escape para evitar SQL Injection
    $query .= (strpos($query, 'WHERE') === false ? ' WHERE ' : ' AND ') . "(nome_termo LIKE '%$searchTerm%' OR definicao_termo LIKE '%$searchTerm%')";
}

$query .= " ORDER BY nome_termo ASC";
$result = $mysqli->query($query);

$termos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $termos[] = $row;
    }
} else {
    echo 'Não há registros de termos.';
}

// Consulta para obter todas as categorias
$querys = "SELECT id_categoria, nome_categoria FROM categorias";
$resultado = $mysqli->query($querys);

$categorias = [];
if ($resultado) {
    while ($row = $resultado->fetch_assoc()) {
        $categorias[] = $row;
    }
} else {
    echo 'Não há registros de categorias.';
}

$isLoggedIn = isset($_SESSION['id_usuario']);
?>

<script>
// Passa o status de login para o JavaScript
const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
</script>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dicionário Profissional</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .nav-section ul li a{
            text-decoration: none;
            color: #ccc;
        }
    </style>
</head>
<body>
    <nav class="nav-bar">
        <div class="nav-section">
            <h3>Categorias de Termos</h3>
            <ul>
                <?php foreach ($categorias as $obj): ?>
                    <li><a href="?categoria=<?php echo $obj['id_categoria']; ?>"><?php echo htmlspecialchars($obj['nome_categoria']); ?></a></li>
                <?php endforeach; ?>
                <!-- Link para mostrar todos os termos -->
                <li><a href="?">Mostrar tudo</a></li>
            </ul>
        </div>
        
        <div class="nav-section">
            <h3>Funcionalidades</h3>
            <ul>
            <li><a href="favoritar.php"><i class="fas fa-star"></i> Favoritos</a></li>
            </ul>
        </div>
    </nav>

    <header>
    <div class="search-container">
        <form action="" method="get">
            <input type="text" name="search" placeholder="Pesquisar..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button class="search-btn" type="submit"><i class="fa fa-search"></i></button>
        </form>
    </div>
    <div class="auth-buttons">
        <?php 
        if ($isLoggedIn) {
            echo '<button class="log-out"><a href="backend/manager/sair.php">Sair</a></button>';
        } else {
            echo '<button class="sign-up"><a href="cadastrar.php">Sign Up</a></button>';
            echo '<button class="log-in"><a href="login.php">Log In</a></button>';
        }
        ?>
    </div>
</header>

    
<main class="dictionary-container">
    <h3>Dicionário Matemático</h3>
    <?php if (!empty($termos)): ?>
        <?php foreach ($termos as $termo): ?>
            <section class="dictionary-entry">
                <h2 class="word"><?php echo htmlspecialchars($termo['nome_termo']); ?></h2>
                <p class="definition"><?php echo htmlspecialchars($termo['definicao_termo']); ?></p>
                
                <?php if ($id_categoria == 17): // Se for a categoria de fórmulas comuns ?>
                    <p class="expression"><strong>Expressão:</strong> <?php echo htmlspecialchars($termo['expressao_termo']); ?></p>
                <?php endif; ?>

                <?php if ($isLoggedIn): ?>
                    <!-- Botão de favoritar com data-id -->
                    <button class="favorite-btn" data-id="<?php echo $termo['id_termo']; ?>">
                        <i class="fas fa-heart"></i> Favoritar
                    </button>
                <?php else: ?>
                    <p>Faça login para favoritar termos.</p>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>

    <?php else: ?>
        <p>Não há termos disponíveis para esta categoria.</p>
    <?php endif; ?>
</main>

    <!-- Modal -->
<div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="feedbackModalLabel">Feedback</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalMessage">
                <!-- A mensagem será injetada aqui -->
            </div>
        </div>
    </div>
</div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // Verifica se o usuário está logado
    if (isLoggedIn) {
        // Adiciona evento de clique em todos os botões de favoritar
        document.querySelectorAll('.favorite-btn').forEach(button => {
            button.addEventListener('click', function () {
                const termoId = this.getAttribute('data-id');
                console.log(termoId);

                fetch('backend/manager/cadastro/favoritos.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'termos_id_termo=' + termoId
                })
                .then(response => response.text())
                .then(data => {
                    // Define a mensagem do modal
                    document.getElementById('modalMessage').innerText = data;
                    // Abre o modal
                    const feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'));
                    feedbackModal.show();

                    // Fecha o modal automaticamente após 3 segundos
                    setTimeout(() => {
                        feedbackModal.hide();
                    }, 3000);
                })
                .catch(error => {
                    console.error('Erro:', error);
                });
            });
        });
    }
});

</script>

</body>
</html>
