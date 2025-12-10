<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario']) || $_SESSION['tipo_usuario'] != 'admin') {
    header("Location: index.php");
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_hp.csv');

$arquivo = fopen('php://output', 'w');
fprintf($arquivo, chr(0xEF).chr(0xBB).chr(0xBF));

// Adicionei a coluna 'Serviço' aqui no cabeçalho
fputcsv($arquivo, array('Data', 'Operador', 'Máquina', 'Serviço', 'H. Inicial', 'H. Final', 'Total'), ';');

// Verifica se veio filtro da URL (vinda do relatorios.php)
if (isset($_GET['inicio']) && isset($_GET['fim'])) {
    $inicio = $_GET['inicio'];
    $fim    = $_GET['fim'];
    
    $sql = "SELECT u.nome as nome_operador, r.* FROM registros r
            JOIN usuarios u ON r.operador_id = u.id
            WHERE r.data_registro >= '$inicio 00:00:00' 
            AND r.data_registro <= '$fim 23:59:59'
            ORDER BY r.data_registro ASC";
} else {
    // Se não veio filtro, baixa o mês atual (comportamento padrão)
    $mes = date('m');
    $ano = date('Y');
    $sql = "SELECT u.nome as nome_operador, r.* FROM registros r
            JOIN usuarios u ON r.operador_id = u.id
            WHERE MONTH(r.data_registro) = '$mes' AND YEAR(r.data_registro) = '$ano'";
}

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $linha = array(
            date('d/m/Y', strtotime($row['data_registro'])),
            $row['nome_operador'],
            $row['nome_maquina'],
            $row['nome_servico'], // Inclui o serviço na linha
            str_replace('.', ',', $row['h_inicial']), 
            str_replace('.', ',', $row['h_final']),
            str_replace('.', ',', $row['total_horas'])
        );
        fputcsv($arquivo, $linha, ';');
    }
}

fclose($arquivo);
exit;
?>