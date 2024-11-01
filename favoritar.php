<?php
session_start();
require_once('startup/connectBD.php');

if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa fazer login para ver seus favoritos.";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Consulta para obter os termos favoritos do usuário
$query = "
    SELECT t.id_termo, t.nome_termo, t.definicao_termo
    FROM favoritos f
    JOIN termos t ON f.termos_id_termo = t.id_termo
    WHERE f.usuarios_id_usuario = ?
    ORDER BY t.nome_termo ASC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();

$favoritos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $favoritos[] = $row;
    }
} else {
    echo 'Erro ao buscar favoritos.';
}
$stmt->close();
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
    <title>Favoritos</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
                
                <!-- Link para mostrar todos os termos -->
                <li><a href="index.php">Voltar</a></li>
            </ul>
        </div>
        
       
    </nav>
    <header>
        <div class="">
            
        </div>
        <div class="auth-buttons">
            <button class="log-out"><a href="backend/manager/sair.php">Sair</a></button>
        </div>
    </header>
    <main class="dictionary-container">
    <h3>Seus Favoritos</h3>
    <?php if (!empty($favoritos)): ?>
        <?php foreach ($favoritos as $termo): ?>
            <section class="dictionary-entry">
                <h2 class="word"><?php echo htmlspecialchars($termo['nome_termo']); ?></h2>
                <p class="definition"><?php echo htmlspecialchars($termo['definicao_termo']); ?></p>
                
                <!-- Botão de desfavoritar com data-id -->
                <button class="unfavorite-btn" data-id="<?php echo $termo['id_termo']; ?>">
                    <i class="fas fa-heart-broken"></i> Desfavoritar
                </button>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Você ainda não favoritou nenhum termo.</p>
    <?php endif; ?>
</main>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Verifica se o usuário está logado
    if (isLoggedIn) {
        // Adiciona evento de clique em todos os botões de desfavoritar
        document.querySelectorAll('.unfavorite-btn').forEach(button => {
            button.addEventListener('click', function () {
                const termoId = this.getAttribute('data-id');
                console.log(termoId);

                fetch('backend/manager/desfavoritar.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: 'termos_id_termo=' + termoId
                })
                .then(response => response.text())
                .then(data => {
                    alert(data);  // Alerta para feedback
                    this.closest('.dictionary-entry').remove(); 
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
