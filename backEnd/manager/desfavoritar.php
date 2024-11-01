<?php
session_start();
require_once('../../startup/connectBD.php');

if (!isset($_SESSION['id_usuario'])) {
    echo "Você precisa fazer login para desfavoritar termos.";
    exit();
}

$id_usuario = $_SESSION['id_usuario'];

// Obtém o ID do termo a ser desfavoritado
$termo_id = isset($_POST['termos_id_termo']) ? intval($_POST['termos_id_termo']) : null;

if ($termo_id) {
    // Prepara a consulta para desfavoritar o termo
    $query = "DELETE FROM favoritos WHERE termos_id_termo = ? AND usuarios_id_usuario = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("ii", $termo_id, $id_usuario);
    
    if ($stmt->execute()) {
        echo "Termo desfavoritado com sucesso!";
    } else {
        echo "Erro ao desfavoritar o termo.";
    }
    
    $stmt->close();
} else {
    echo "ID do termo não especificado.";
}
?>
