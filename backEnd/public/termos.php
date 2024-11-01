<?php
require_once("../../startup/connectBD.php");
session_start();
if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../index.php');
    exit();
}



// Consulta para buscar os termos
$query = "SELECT `id_termo`, `nome_termo`, `definicao_termo`, `expressao_termo` FROM `termos`";
$result = $mysqli->query($query);

$termos = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $termos[] = $row;
    }
} else {
    echo 'Não há registro de termos.';
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
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="topo.js"></script>
</head>
<body>

<main class="">
    <h1>Termos</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>
    <a href="cadastrar/termos.php" class="btn btn-primary mb-3">Criar Novo Termo</a>

    <form method="POST" action="../manager/delete/termo.php">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Definição</th>
                    <th>Expressão</th>
                    <th>Ações</th>

                </tr>
            </thead>
            <tbody>
                <?php foreach ($termos as $termo): ?>
                    <tr>
                        <td><input type="checkbox" name="termos[]" value="<?php echo $termo['id_termo']; ?>"></td>
                        <td><?php echo $termo['id_termo']; ?></td>
                        <td><?php echo $termo['nome_termo']; ?></td>
                        <td><?php echo $termo['definicao_termo']; ?></td>
                        <td><?php echo $termo['expressao_termo']; ?></td>
                        <td>
                            <a href="editar/termos.php?id=<?php echo $termo['id_termo']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="submit" class="btn btn-danger">Deletar Selecionados</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
