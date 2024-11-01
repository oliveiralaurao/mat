<?php
session_start();
require_once("../../../startup/connectBD.php");

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../../index.php');
    exit();
}

if (!isset($_GET['id'])) {
    die("ID da categoria não especificado.");
}

// Consulta para buscar a categoria pelo ID
$id_categoria = $_GET['id'];
$query = "SELECT `id_categoria`, `nome_categoria` FROM `categorias` WHERE `id_categoria` = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_categoria);
$stmt->execute();
$result = $stmt->get_result();

$categoria = $result->fetch_assoc();
$stmt->close();

if (!$categoria) {
    die("Categoria não encontrada.");
}
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
    <title>Editar Categoria</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/style.css">
    <script src="../topo.js"></script>
</head>
<body>

<main class="">
    <h1>Editar Categoria</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <!-- Formulário que envia os dados para update/categoria.php -->
    <form method="POST" action="../../manager/update/categoria.php">
        <input type="hidden" name="id_categoria" value="<?php echo $categoria['id_categoria']; ?>">
        <div class="form-group">
            <label for="nome_categoria">Nome da Categoria:</label>
            <input type="text" name="nome_categoria" id="nome_categoria" class="form-control" value="<?php echo htmlspecialchars($categoria['nome_categoria']); ?>" required>
        </div>
        <button type="submit" class="btn btn-primary">Atualizar Categoria</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
