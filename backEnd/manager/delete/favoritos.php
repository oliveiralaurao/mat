<?php
session_start();
require_once('../../startup/connectBD.php');

if (!isset($_SESSION['id_usuario'])) {
    die("Usuário não autenticado!");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_favorito = $_POST['id_favorito'];

    $stmt = $mysqli->prepare("DELETE FROM favoritos WHERE id_favorito = ?");
    $stmt->bind_param("i", $id_favorito);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Favorito deletado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../public/favoritos.php");
    exit();
}
?>


