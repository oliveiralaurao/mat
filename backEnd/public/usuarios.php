<?php
require_once("../../startup/connectBD.php");

session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../index.php');
    exit();
}



$query = "SELECT `id_usuario`, `nome_usuario`, `email_usuario`, `tipo_usuario` FROM `usuarios`";
$result = $mysqli->query($query); 

$usuarios = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $usuarios[] = $row;
    }
}else{
    echo 'Não há registro de usuário.';
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
    <h1>Usuários</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>
    <a href="cadastrar/usuario.php" class="btn btn-primary mb-3">Criar Novo Usuário</a>


    <form method="POST" action="../manager/delete/usuarios.php">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Tipo</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): ?>
                    <tr>
                        <td><input type="checkbox" name="usuarios[]" value="<?php echo $usuario['id_usuario']; ?>"></td>
                        <td><?php echo $usuario['id_usuario']; ?></td>
                        <td><?php echo $usuario['nome_usuario']; ?></td>
                        <td><?php echo $usuario['email_usuario']; ?></td>
                        <td><?php echo $usuario['tipo_usuario']; ?></td>
                        <td>
                            <a href="editar/usuario.php?id=<?php echo $usuario['id_usuario']; ?>" class="btn btn-warning btn-sm">Editar</a>
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
