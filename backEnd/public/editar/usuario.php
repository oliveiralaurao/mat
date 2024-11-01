<?php
session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../../index.php');
    exit();
}

require_once("../../../startup/connectBD.php");

// Verifica se o ID do usuário foi passado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['mensagem'] = "ID de usuário inválido!";
    header("Location: ../public/usuarios.php");
    exit();
}

$id_usuario = $_GET['id'];

// Consulta para buscar os dados do usuário a ser editado
$stmt = $mysqli->prepare("SELECT nome_usuario, email_usuario, tipo_usuario FROM usuarios WHERE id_usuario = ?");
$stmt->bind_param("i", $id_usuario);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();

if (!$usuario) {
    $_SESSION['mensagem'] = "Usuário não encontrado!";
    header("Location: ../public/usuarios.php");
    exit();
}

$stmt->close();
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
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../../css/style.css">
    <script src="../topo.js"></script>

</head>
<body>

<main class="">
    <h1>Editar Usuário</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <!-- Formulário para editar o usuário -->
    <form method="POST" action="../../manager/update/usuarios.php">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        
        <div class="form-group">
            <label for="nome_usuario">Nome do Usuário:</label>
            <input type="text" name="nome_usuario" id="nome_usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['nome_usuario']); ?>" required>
        </div>
        
        <div class="form-group">
            <label for="email_usuario">Email do Usuário:</label>
            <input type="email" name="email_usuario" id="email_usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['email_usuario']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tipo_usuario">Tipo do Usuário:</label>
            <select name="tipo_usuario" id="tipo_usuario" class="form-control" value="<?php echo htmlspecialchars($usuario['tipo_usuario']); ?>" required>
            <option value="" disabled>Selecione uma categoria</option>
                <option value="adm">Administrador</option>
                <option value="aluno">Aluno(a)</option>
            </select>
        </div>
        

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <button type="button" class="btn btn-secondary" onclick="javascript:history.back();">Cancelar</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
