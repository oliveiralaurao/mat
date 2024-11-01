<?php
session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../../index.php');
    exit();
}

require_once('../../../startup/connectBD.php');

require_once('../../manager/cadastro/categoria.php');
$isLoggedIn = isset($_SESSION['id_usuario']);
?>

<script>
// Passa o status de login para o JavaScript
const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
</script>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Categoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/style.css">
    <script src="../topo.js"></script>
</head>
<body>

<main class="">
    <h1>Criar Nova Categoria</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <form method="POST" action="../../manager/cadastro/categoria.php">
        <div class="form-group">
            <label for="nome_categoria">Nome da Categoria:</label>
            <input type="text" name="nome_categoria" id="nome_categoria" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar Categoria</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
