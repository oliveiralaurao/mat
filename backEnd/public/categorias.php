<?php
session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../index.php');
    exit();
}

require_once("../../startup/connectBD.php");

// Consulta para buscar as categorias
$query = "SELECT `id_categoria`, `nome_categoria` FROM `categorias`";
$result = $mysqli->query($query);

$categorias = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categorias[] = $row;
    }
} else {
    echo 'Não há registro de categorias.';
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
    <h1>Categorias</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <a href="cadastrar/categoria.php" class="btn btn-primary mb-3">Criar Nova Categoria</a>

    <form method="POST" action="../manager/delete/categoria.php">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($categorias as $categoria): ?>
                    <tr>
                        <td><input type="checkbox" name="categorias[]" value="<?php echo $categoria['id_categoria']; ?>"></td>
                        <td><?php echo $categoria['id_categoria']; ?></td>
                        <td><?php echo $categoria['nome_categoria']; ?></td>
                        <td>
                            <a href="editar/categoria.php?id=<?php echo $categoria['id_categoria']; ?>" class="btn btn-warning btn-sm">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#confirmDeleteModal">Deletar Selecionados</button>
        </form>
</main>
<!-- Modal de Confirmação -->
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="confirmDeleteModalLabel">Confirmação de Exclusão</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Você tem certeza de que deseja excluir as categorias selecionadas? Todas as entradas relacionadas serão removidas permanentemente.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" id="confirmDeleteButton" class="btn btn-danger">Confirmar Exclusão</button>
      </div>
    </div>
  </div>
</div>
<script>
    document.getElementById('confirmDeleteButton').addEventListener('click', function () {
        document.querySelector('form').submit();
    });
</script>


<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
