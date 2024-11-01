

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuário</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        header{
            position: fixed;
            left: 0!important;
            width: 100%;
        }
        main{
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
        <button class="log-in"><a href="login.php">Log In</a></button>
    </div>
</header>


<main class="">
    <h1>Cadastro</h1>
    <?php if (isset($_SESSION['mensagem'])): ?>
        <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
    <?php endif; ?>

    <!-- Formulário para editar o usuário -->
    <form method="POST" action="backend/manager/cadastro/user.php">
        <input type="hidden" name="id_usuario" value="<?php echo $id_usuario; ?>">
        
        <div class="form-group">
            <label for="nome_usuario">Nome:</label>
            <input type="text" name="nome_usuario" id="nome_usuario" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label for="email_usuario">Email:</label>
            <input type="email" name="email_usuario" id="email_usuario" class="form-control"  required>
        </div>
         <div class="form-group">
            <label for="senha_usuario">Senha:</label>
            <input type="password" name="senha_usuario" id="senha_usuario" class="form-control"  required>
        </div>
        

        <button type="submit" class="btn btn-primary">Entrar</button>
    </form>
</main>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
