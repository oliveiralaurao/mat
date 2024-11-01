<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_usuario = $_POST['id_usuario'];
    $nome_usuario = $_POST['nome_usuario'];
    $email_usuario = $_POST['email_usuario'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $stmt = $mysqli->prepare("UPDATE usuarios SET nome_usuario = ?, email_usuario = ?, tipo_usuario = ? WHERE id_usuario = ?");
    $stmt->bind_param("sssi", $nome_usuario, $email_usuario, $tipo_usuario, $id_usuario);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Usuário atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/usuarios.php");
    exit();
}
?>
