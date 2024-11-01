<?php
session_start();

if (!isset($_SESSION['email']) || (!($_SESSION['tipo_usuario'] === 'adm'))) {
    header('Location: ../../index.php');
    exit();
}

require_once('../../startup/connectBD.php');

// Consulta para contar os registros nas tabelas
$queryUsuarios = "SELECT COUNT(*) as total FROM usuarios";
$resultUsuarios = $mysqli->query($queryUsuarios);
$totalUsuarios = $resultUsuarios->fetch_assoc()['total'];

$queryCategorias = "SELECT COUNT(*) as total FROM categorias";
$resultCategorias = $mysqli->query($queryCategorias);
$totalCategorias = $resultCategorias->fetch_assoc()['total'];

$queryTermos = "SELECT COUNT(*) as total FROM termos";
$resultTermos = $mysqli->query($queryTermos);
$totalTermos = $resultTermos->fetch_assoc()['total'];
$isLoggedIn = isset($_SESSION['id_usuario']);
?>

<script>
// Passa o status de login para o JavaScript
const isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
</script>




<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <script src="topo.js"></script>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
        // Carregar a biblioteca de visualização do Google Charts
        google.charts.load('current', {'packages':['corechart']});
        google.charts.setOnLoadCallback(drawChart);

        // Função para desenhar o gráfico de pizza
        function drawChart() {
            var data = google.visualization.arrayToDataTable([
                ['Tipo', 'Total'],
                ['Usuários', <?php echo $totalUsuarios; ?>],
                ['Categorias', <?php echo $totalCategorias; ?>],
                ['Termos', <?php echo $totalTermos; ?>]
            ]);

            var options = {
                title: 'Distribuição de Registros',
                pieHole: 0.4, // Gráfico de pizza com buraco no meio (pizza donut)
                colors: ['#4285F4', '#EA4335', '#34A853'],
                legend: { position: 'bottom' },
                chartArea: { width: '80%', height: '80%' }
            };

            var chart = new google.visualization.PieChart(document.getElementById('piechart'));
            chart.draw(data, options);
        }
    </script>
</head>
<body>
    <main class="">
        <h2>Dashboard</h2>
        <p>Bem-vindo ao painel administrativo!</p>
        <?php if (isset($_SESSION['mensagem'])): ?>
            <div class="alert alert-info"><?php echo $_SESSION['mensagem']; unset($_SESSION['mensagem']); ?></div>
        <?php endif; ?>
        <div class="row my-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Usuários</h5>
                        <p class="card-text"><?php echo $totalUsuarios; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Categorias</h5>
                        <p class="card-text"><?php echo $totalCategorias; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total de Termos</h5>
                        <p class="card-text"><?php echo $totalTermos; ?></p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Área do gráfico de pizza -->
        <div id="piechart" style="width: 100%; height: 500px;"></div>
    </main>
    

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
