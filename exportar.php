<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename=relatorio_mensal.csv');

$arquivo = fopen('php://output', 'w');

fprintf($arquivo, chr(0xEF).chr(0xBB).chr(0xBF));

fputcsv($arquivo, array('Operador', 'Máquina', 'Serviço', 'H. Inicial', 'H. Final', 'Total Horas', 'Data'), ';');

$mes_atual = date('m');
$ano_atual = date('Y');


$sql = "SELECT u.nome as nome_operador, r.nome_maquina, r.nome_servico, r.h_inicial, r.h_final, r.total_horas, r.data_registro 
        FROM registros r
        JOIN usuarios u ON r.operador_id = u.id
        WHERE MONTH(r.data_registro) = '$mes_atual' 
        AND YEAR(r.data_registro) = '$ano_atual'
        ORDER BY r.nome_maquina ASC, r.data_registro ASC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $data_formatada = date('d/m/Y H:i', strtotime($row['data_registro']));
        
        $linha = array(
            $row['nome_operador'],
            $row['nome_maquina'],
            $row['nome_servico'],
            str_replace('.', ',', $row['h_inicial']), 
            str_replace('.', ',', $row['h_final']),
            str_replace('.', ',', $row['total_horas']),
            $data_formatada
        );

        fputcsv($arquivo, $linha, ';');
    }
}

fclose($arquivo);
exit;
?>