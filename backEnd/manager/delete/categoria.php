
<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['categorias']) || empty($_POST['categorias'])) {
        $_SESSION['mensagem'] = "Erro: Nenhuma categoria selecionada.";
        header("Location: ../../public/categorias.php");
        exit();
    }

    $ids_categorias = $_POST['categorias'];
    $ids_placeholder = implode(',', array_fill(0, count($ids_categorias), '?'));
    
    $stmt = $mysqli->prepare("DELETE FROM categorias WHERE id_categoria IN ($ids_placeholder)");
    $stmt->bind_param(str_repeat('i', count($ids_categorias)), ...$ids_categorias);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categorias e itens associados deletados com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/categorias.php");
    exit();
}
?>

