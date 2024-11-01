<?php
session_start();
require_once('../../../startup/connectBD.php');

// if (!isset($_SESSION['id_usuario'])) {
//     die("Usuário não autenticado!");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_categoria = $_POST['id_categoria'];
    $nome_categoria = $_POST['nome_categoria'];

    $stmt = $mysqli->prepare("UPDATE categorias SET nome_categoria = ? WHERE id_categoria = ?");
    $stmt->bind_param("si", $nome_categoria, $id_categoria);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categoria atualizada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/categorias.php");
    exit();
}
?>


