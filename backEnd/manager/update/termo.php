<?php
session_start();
require_once('../../../startup/connectBD.php');

// if (!isset($_SESSION['id_usuario'])) {
//     die("Usuário não autenticado!");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_termo = $_POST['id_termo'];
    $nome_termo = $_POST['nome_termo'];
    $definicao_termo = $_POST['definicao_termo'];

    $stmt = $mysqli->prepare("UPDATE termos SET nome_termo = ?, definicao_termo = ? WHERE id_termo = ?");
    $stmt->bind_param("ssi", $nome_termo, $definicao_termo, $id_termo);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Termo atualizado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/termos.php");
    exit();
}
?>


