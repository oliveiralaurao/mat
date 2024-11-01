<?php
session_start();
require_once('../../../startup/connectBD.php');

// // Verifica se o usuário está autenticado
// if (!isset($_SESSION['id_usuario'])) {
//     die("Usuário não autenticado!");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verifica se algum termo foi selecionado
    if (!isset($_POST['termos']) || empty($_POST['termos'])) {
        $_SESSION['mensagem'] = "Erro: Nenhum termo selecionado.";
        header("Location: ../../public/termos.php");
        exit();
    }

    // Obtém os IDs dos termos selecionados
    $ids_termos = $_POST['termos'];

    // Prepara a query para deletar múltiplos termos
    $ids_placeholder = implode(',', array_fill(0, count($ids_termos), '?'));
    $stmt = $mysqli->prepare("DELETE FROM termos WHERE id_termo IN ($ids_placeholder)");

    // Bind dos parâmetros
    $stmt->bind_param(str_repeat('i', count($ids_termos)), ...$ids_termos);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            $_SESSION['mensagem'] = "Termos deletados com sucesso!";
        } else {
            $_SESSION['mensagem'] = "Nenhum termo encontrado com os IDs especificados.";
        }
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/termos.php");
    exit();
}
?>
