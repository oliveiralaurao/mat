<?php
// session_start();
require_once('../../../startup/connectBD.php');

// if (!isset($_SESSION['id_usuario'])) {
//     die("Usuário não autenticado!");
// }

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_categoria = $_POST['nome_categoria'];

    // Preparar e executar a instrução SQL
    $stmt = $mysqli->prepare("INSERT INTO categorias (nome_categoria) VALUES (?)");
    $stmt->bind_param("s", $nome_categoria);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Categoria criada com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/categorias.php"); // ajuste o caminho para a página de categorias
    exit();
}
?>


