<?php
require_once('../../startup/connectBD.php');
session_start();

if (isset($_POST['email_usuario']) && isset($_POST['senha_usuario'])) {
    $email = $_POST['email_usuario'];
    $senha = $_POST['senha_usuario'];

    if (!empty($email) && !empty($senha)) {
        $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email_usuario = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_usuario = $row['id_usuario'];
            $nome_usuario = $row['nome_usuario'];
            $senha_hash = $row['senha_usuario'];
            $tipo_usuario = $row['tipo_usuario'];

            if (password_verify($senha, $senha_hash)) {
                $_SESSION['email'] = $email;
                $_SESSION['id_usuario'] = $id_usuario;
                $_SESSION['nome_usuario'] = $nome_usuario;
                $_SESSION['tipo_usuario'] = $tipo_usuario;

                if ($tipo_usuario == 'adm' || $tipo_usuario == 'dev') {
                    header("Location: ../public/index.php");
                } else {
                    header("Location: ../../index.php");
                }
                exit();
            } else {
                header("Location: ../../login.php?error=" . urlencode("Email ou senha incorretos."));
                exit();
            }
        } else {
            header("Location: ../../login.php?error=" . urlencode("Email não encontrado."));
            exit();
        }
    } else {
        header("Location: ../../login.php?error=" . urlencode("Por favor, preencha todos os campos."));
        exit();
    }
}
?>
