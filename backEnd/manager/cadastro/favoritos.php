<?php
session_start();
require_once('../../../startup/connectBD.php');

header("Content-Type: text/plain"); // Retorna texto puro

if (!isset($_SESSION['id_usuario'])) {
    echo "Usuário não autenticado!";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $termos_id_termo = $_POST['termos_id_termo'];
    $usuarios_id_usuario = $_SESSION['id_usuario'];

    // Validação para verificar se o termo existe
    $stmtCheck = $mysqli->prepare("SELECT id_termo FROM termos WHERE id_termo = ?");
    $stmtCheck->bind_param("i", $termos_id_termo);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows > 0) {
        // Verifica se o termo já está nos favoritos
        $stmtFavCheck = $mysqli->prepare("SELECT termos_id_termo FROM favoritos WHERE termos_id_termo = ? AND usuarios_id_usuario = ?");
        $stmtFavCheck->bind_param("ii", $termos_id_termo, $usuarios_id_usuario);
        $stmtFavCheck->execute();
        $stmtFavCheck->store_result();

        if ($stmtFavCheck->num_rows > 0) {
            echo "Este termo já está nos seus favoritos!";
        } else {
            // Inserir na tabela favoritos
            $stmt = $mysqli->prepare("INSERT INTO favoritos (termos_id_termo, usuarios_id_usuario) VALUES (?, ?)");
            $stmt->bind_param("ii", $termos_id_termo, $usuarios_id_usuario);

            if ($stmt->execute()) {
                echo "Favorito adicionado com sucesso!";
            } else {
                echo "Erro ao adicionar favorito: " . $stmt->error;
            }
            $stmt->close();
        }

        $stmtFavCheck->close();
    } else {
        echo "Erro: o termo selecionado não existe.";
    }

    $stmtCheck->close();
    $mysqli->close();
}
?>
