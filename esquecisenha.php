<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Esqueci Senha</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        header {
            position: fixed;
            left: 0!important;
            width: 100%;
        }
        main {
            position: relative;
            left: 0!important;
            margin-left: 8%;
        }
    </style>
</head>
<body>
<header>
    <div class="">
        <button type="button" class="btn btn-secondary" onclick="javascript:history.back();">Voltar</button>
    </div>
    <div class="auth-buttons">
        <button class="sign-up"><a href="cadastrar.php">Sign Up</a></button>
    </div>
</header>

<main>
    <h1>Esqueci Senha</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <form id="reset-password-form" method="POST" action="backEnd/manager/esqueci.php" onsubmit="return validatePasswords();">
        <div class="form-group">
            <label for="email_usuario">Email:</label>
            <input type="email" name="email_usuario" id="email_usuario" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="senha_usuario">Nova Senha:</label>
            <input type="password" name="senha_usuario" id="senha_usuario" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="rsenha_usuario">Repetir Senha:</label>
            <input type="password" name="rsenha_usuario" id="rsenha_usuario" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Confirmar</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function validatePasswords() {
        const senha = document.getElementById('senha_usuario').value;
        const rsenha = document.getElementById('rsenha_usuario').value;

        if (senha !== rsenha) {
            alert('As senhas não coincidem. Tente novamente.');
            return false; // Impede o envio do formulário
        }
        return true; // Permite o envio do formulário
    }
</script>
</body>
</html>
