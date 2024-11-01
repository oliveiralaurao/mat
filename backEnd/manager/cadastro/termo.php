<?php
session_start();
require_once('../../../startup/connectBD.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_termo = $_POST['nome_termo'];
    $definicao_termo = $_POST['definicao_termo'];
    $expressao_termo = $_POST['expressao_termo'];
    $categorias_id_categoria = $_POST['categorias_id_categoria'];
    $data_criacao_termo = date('Y-m-d H:i:s'); // Data atual
    $data_atualizacao_termo = date('Y-m-d H:i:s'); // Mesmo valor para criação inicial

    $stmt = $mysqli->prepare("INSERT INTO termos (nome_termo, definicao_termo, data_criacao_termo, data_atualizacao_termo, expressao_termo, categorias_id_categoria) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssi", $nome_termo, $definicao_termo, $data_criacao_termo, $data_atualizacao_termo, $expressao_termo, $categorias_id_categoria);

    if ($stmt->execute()) {
        $_SESSION['mensagem'] = "Termo criado com sucesso!";
    } else {
        $_SESSION['mensagem'] = "Erro: " . $stmt->error;
    }

    $stmt->close();
    $mysqli->close();

    header("Location: ../../public/termos.php");
    exit();
}
?>
