<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST['nome_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $senha_usuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT); // Criptografar senha

    // Verificar se o e-mail já está cadastrado
    $stmt = $mysqli->prepare("SELECT COUNT(*) FROM usuarios WHERE email_usuario = ?");
    $stmt->bind_param("s", $email_usuario);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        // E-mail já cadastrado
        $_SESSION['mensagem'] = "Erro: Este e-mail já está cadastrado.";
        header("Location: ../../public/cadastrar/usuario.php");
        exit();
    }

    // Inserir o novo usuário
    $stmt = $mysqli->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome_usuario, $email_usuario, $senha_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Usuário criado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/usuarios.php");
    exit();
}
?>
