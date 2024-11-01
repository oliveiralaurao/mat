<?php
session_start(); 
require_once('../../startup/connectBD.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email_usuario = $_POST['email_usuario'];
    $senha_usuario = $_POST['senha_usuario'];
    $rsenha_usuario = $_POST['rsenha_usuario'];

    // Checando se as senhas coincidem
    if ($senha_usuario !== $rsenha_usuario) {
        $_SESSION['mensagem'] = "As senhas não coincidem. Tente novamente.";
        header("Location: ../../esquecisenha.php"); // Redireciona para a página do formulário
        exit;
    }

    $stmt = $mysqli->prepare("SELECT id_usuario FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $stmt->store_result();

    // Verificando se o e-mail existe
    if ($stmt->num_rows === 0) {
        $_SESSION['mensagem'] = "E-mail não encontrado. Tente novamente.";
        header("Location: ../../esquecisenha.php"); // Redireciona para a página do formulário
        exit;
    }

    $stmt->bind_result($id_usuario);
    $stmt->fetch();
    $stmt->close();

    // Atualizando a senha
    $senha_hash = password_hash($senha_usuario, PASSWORD_DEFAULT);
    $stmt = $mysqli->prepare("UPDATE usuarios SET senha_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("si", $senha_hash, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Senha redefinida com sucesso!";
        header("Location: ../../login.php"); // Redireciona para a página de login após sucesso
    } else {
        $_SESSION['mensagem'] = "Erro ao redefinir a senha. Tente novamente.";
        header("Location: ../../esquecisenha.php"); // Redireciona para a página do formulário
    }

    $stmt->close();
    $mysqli->close();
}
?>
