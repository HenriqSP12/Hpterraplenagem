<?php
session_start();
require 'db.php';


if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: painel.php");
    exit;
}

$data_inicio = "";
$data_final = "";


if (isset($_GET['inicio']) && isset($_GET['fim'])) {
    $data_inicio = $_GET['inicio'];
    $data_final = $_GET['fim'];

    $sql = "SELECT u.nome as nome_operador, r.* FROM registros r
            JOIN usuarios u ON r.operador_id = u.id
            WHERE r.data_registro >= '$data_inicio 00:00:00' 
            AND r.data_registro <= '$data_final 23:59:59'
            ORDER BY r.data_registro DESC";
            
    $result = $conn->query($sql);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Relatórios - HP Terraplenagem</title>
    <link rel="stylesheet" href="style.css">
    <style>

        table { width: 100%; border-collapse: collapse; margin-top: 20px; font-size: 14px; }
        th, td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        th { background-color: #000; color: #FFC107; }
        tr:nth-child(even) { background-color: #f2f2f2; }
        .filtro-box { display: flex; gap: 10px; align-items: flex-end; margin-bottom: 20px; }
        .input-group { display: flex; flex-direction: column; }
    </style>
</head>
<body>

<div class="container" style="max-width: 900px;"> <div class="header-painel">
        <h2>Relatório de Produção</h2>
        <a href="painel.php" class="btn-sair">Voltar</a>
    </div>

    <form class="filtro-box" method="GET">
        <div class="input-group">
            <label>De:</label>
            <input type="date" name="inicio" value="<?php echo $data_inicio; ?>" required style="margin-bottom:0;">
        </div>
        
        <div class="input-group">
            <label>Até:</label>
            <input type="date" name="fim" value="<?php echo $data_final; ?>" required style="margin-bottom:0;">
        </div>

        <button type="submit" class="btn-login" style="width: auto; height: 46px;">🔍 Buscar</button>
    </form>

    <?php if(isset($result) && $result->num_rows > 0): ?>
        
        <div style="text-align: right; margin-bottom: 10px;">
            <a href="exportar.php?inicio=<?php echo $data_inicio; ?>&fim=<?php echo $data_final; ?>">
                <button class="btn-excel" style="width: auto;">📥 Baixar CSV Filtrado</button>
            </a>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Operador</th>
                    <th>Máquina</th>
                    <th>Serviço</th>
                    <th>Total Horas</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo date('d/m/Y', strtotime($row['data_registro'])); ?></td>
                    <td><?php echo $row['nome_operador']; ?></td>
                    <td><?php echo $row['nome_maquina']; ?></td>
                    <td><?php echo $row['nome_servico']; ?></td>
                    <td><strong><?php echo number_format($row['total_horas'], 2, ',', '.'); ?></strong></td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

    <?php elseif(isset($result)): ?>
        <p class="mensagem erro">Nenhum registro encontrado neste período.</p>
    <?php endif; ?>

</div>

</body>
</html>