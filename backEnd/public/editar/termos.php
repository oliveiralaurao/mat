<?php
session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../../index.php');
    exit();
}
require_once("../../../startup/connectBD.php");

// Verificar se o ID do termo foi fornecido
if (!isset($_GET['id'])) {
    $_SESSION['mensagem'] = "ID do termo não fornecido.";
    header("Location: ../public/termos.php");
    exit();
}

$id_termo = $_GET['id'];

// Consulta para obter os detalhes do termo
$query = "SELECT `nome_termo`, `definicao_termo`, `expressao_termo`, `categorias_id_categoria` FROM `termos` WHERE `id_termo` = ?";
$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $id_termo);
$stmt->execute();
$result = $stmt->get_result();

$termo = $result->fetch_assoc();
if (!$termo) {
    $_SESSION['mensagem'] = "Termo não encontrado.";
    header("Location: ../public/termos.php");
    exit();
}

// Consulta para obter as categorias
$query_categorias = "SELECT `id_categoria`, `nome_categoria` FROM `categorias`";
$result_categorias = $mysqli->query($query_categorias);

$categorias = [];
if ($result_categorias) {
    while ($row = $result_categorias->fetch_assoc()) {
        $categorias[] = $row;
    }
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
    <title>Editar Termo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/style.css">
    <script src="../topo.js"></script>

</head>
<body>

<main class="">
    <h1>Editar Termo</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <!-- Formulário para editar o termo -->
    <form method="POST" action="../../manager/update/termo.php">
        <input type="hidden" name="id_termo" value="<?php echo $id_termo; ?>">

        <div class="form-group">
            <label for="nome_termo">Nome do Termo:</label>
            <input type="text" name="nome_termo" id="nome_termo" class="form-control" value="<?php echo htmlspecialchars($termo['nome_termo']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="definicao_termo">Definição do Termo:</label>
            <textarea name="definicao_termo" id="definicao_termo" class="form-control" rows="3" required><?php echo htmlspecialchars($termo['definicao_termo']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="expressao_termo">Expressão do Termo (opcional):</label>
            <textarea name="expressao_termo" id="expressao_termo" class="form-control" rows="3"><?php echo htmlspecialchars($termo['expressao_termo']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="categorias_id_categoria">Categoria:</label>
            <select name="categorias_id_categoria" id="categorias_id_categoria" class="form-control" required>
                <option value="">Selecione uma categoria</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>" <?php if ($termo['categorias_id_categoria'] == $categoria['id_categoria']) echo 'selected'; ?>>
                        <?php echo $categoria['nome_categoria']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Atualizar Termo</button>
        <button type="button" class="btn btn-secondary" onclick="javascript:history.back();">Voltar</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
