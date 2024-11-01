<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_usuario = $_POST['nome_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $senha_usuario = password_hash($_POST['senha_usuario'], PASSWORD_DEFAULT); // Criptografar senha

    $stmt = $mysqli->prepare("INSERT INTO usuarios (nome_usuario, email_usuario, senha_usuario) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $nome_usuario, $email_usuario, $senha_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Usuário criado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../../index.php");
    exit();
}
?>

