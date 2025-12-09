<?php
session_start();
require 'db.php';

if (!isset($_SESSION['id_usuario'])) {
    header("Location: index.php");
    exit;
}

$mensagem = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $maquina = $_POST['maquina'];
    
    // --- NOVO: Recebendo o nome do serviço ---
    $servico = $_POST['servico']; 
    // -----------------------------------------

    $inicial = $_POST['h_inicial'];
    $final   = $_POST['h_final'];
    $id_user = $_SESSION['id_usuario'];

    $total = $final - $inicial;

    if ($total < 0) {
        $mensagem = "ERRO: Hora final não pode ser menor que a inicial!";
    } else {
        // --- ATUALIZADO: Incluindo nome_servico no INSERT ---
        $sql = "INSERT INTO registros (operador_id, nome_maquina, nome_servico, h_inicial, h_final, total_horas) 
                VALUES ('$id_user', '$maquina', '$servico', '$inicial', '$final', '$total')";
        
        if ($conn->query($sql) === TRUE) {
            $mensagem = "Dados enviados! Total trabalhado: $total hora(s).";
        } else {
            $mensagem = "Erro: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel de Produção</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <div class="header-painel">
            <span>Olá, <strong><?php echo $_SESSION['nome_usuario']; ?></strong></span>
            <a href="logout.php" style="color: red; text-decoration: none; font-size: 14px;">(Sair)</a>
        </div>

        <h2>Apontamento</h2>
        
        <?php if(!empty($mensagem)): ?>
            <div class="mensagem <?php echo strpos($mensagem, 'Erro') !== false || strpos($mensagem, 'ERRO') !== false ? 'erro' : 'sucesso'; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Máquina:</label>
            <input type="text" name="maquina" placeholder="Ex: Escavadeira 01" required>

            <label>Nome do Serviço / Prestador:</label>
            <input type="text" name="servico" placeholder="Ex: Limpeza de Terreno / João da Silva" required>
            <label>Horímetro Inicial:</label>
            <input type="number" step="0.01" name="h_inicial" placeholder="0000.00" required>

            <label>Horímetro Final:</label>
            <input type="number" step="0.01" name="h_final" placeholder="0000.00" required>

            <button type="submit" class="btn-salvar">Salvar Registro</button>
        </form>

        <?php if(isset($_SESSION['tipo_usuario']) && $_SESSION['tipo_usuario'] == 'admin'): ?>
            <div class="admin-area">
                <h3>Administrativo</h3>
                <p style="font-size: 14px; color: #666; margin-bottom: 10px;">Relatório Mensal</p>
                <a href="exportar.php">
                    <button class="btn-excel">Baixar Planilha (.csv)</button>
                </a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>