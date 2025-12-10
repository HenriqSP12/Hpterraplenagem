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
    $servico = $_POST['servico']; 
    $inicial = $_POST['h_inicial'];
    $final   = $_POST['h_final'];
    $id_user = $_SESSION['id_usuario'];

    $total = $final - $inicial;

    if ($total < 0) {
        $mensagem = "ERRO: Hora final não pode ser menor que a inicial!";
    } else {
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
    <title>Painel - HP Terraplenagem</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        
        <div class="header-painel">
            <span>Olá, <strong><?php echo $_SESSION['nome_usuario']; ?></strong></span>
            <a href="logout.php" class="btn-sair">Sair</a>
        </div>

        <?php if($_SESSION['tipo_usuario'] == 'admin'): ?>
            
            <h2 style="margin-bottom: 30px;">Painel Gerencial</h2>
            
            <div class="admin-area" style="margin: 0; border-radius: 10px;">
                <a href="relatorios.php">
                    <button class="btn-excel">Ver Relatórios</button>
                </a>

                <hr style="border: 1px solid #333; margin: 20px 0;">

                <a href="cadastro.php">
                    <button class="btn-login">Cadastrar Novo Usuário</button>
                </a>
            </div>

        <?php else: ?>

            <h2>Apontamento</h2>
            
            <?php if(!empty($mensagem)): ?>
                <div class="mensagem <?php echo strpos($mensagem, 'ERRO') !== false ? 'erro' : 'sucesso'; ?>">
                    <?php echo $mensagem; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <label>Máquina:</label>
                <input type="text" name="maquina" required>

                <label>Serviço / Cliente:</label>
                <input type="text" name="servico" required>

                <label>Horímetro Inicial:</label>
                <input type="number" step="0.01" name="h_inicial" placeholder="0000.00" required>

                <label>Horímetro Final:</label>
                <input type="number" step="0.01" name="h_final" placeholder="0000.00" required>

                <button type="submit" class="btn-salvar">Salvar Registro</button>
            </form>

        <?php endif; ?>
        </div>

</body>
</html>