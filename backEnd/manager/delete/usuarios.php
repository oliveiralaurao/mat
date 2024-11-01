<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se algum usuário foi selecionado
    if (!isset($_POST['usuarios']) || empty($_POST['usuarios'])) {
        $_SESSION['mensagem'] = "Erro: Nenhum usuário selecionado.";
        header("Location: ../../public/usuarios.php");
        exit();
    }

    // Obtém os IDs dos usuários selecionados
    $ids_usuarios = $_POST['usuarios'];
    
    // Prepara a query para deletar múltiplos usuários
    $ids_placeholder = implode(',', array_fill(0, count($ids_usuarios), '?'));
    $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE id_usuario IN ($ids_placeholder)");

    // Bind dos parâmetros
    $stmt->bind_param(str_repeat('i', count($ids_usuarios)), ...$ids_usuarios);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['mensagem'] = "Usuários deletados com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Nenhum usuário encontrado com os IDs especificados.";
        }
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/usuarios.php");
    exit();
}
?>
