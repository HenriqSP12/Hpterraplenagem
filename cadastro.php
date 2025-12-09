<?php
require 'db.php';

$mensagem = "";
$classe_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome  = $_POST['nome'];
    $login = $_POST['login'];
    $senha = $_POST['senha'];
    $tipo  = $_POST['tipo'];


    $check = "SELECT id FROM usuarios WHERE login = '$login'";
    $result_check = $conn->query($check);

    if ($result_check->num_rows > 0) {
        $mensagem = "Erro: O login '$login' já existe!";
        $classe_msg = "erro";
    } else {
        
        
        $sql = "INSERT INTO usuarios (nome, login, senha, tipo) VALUES ('$nome', '$login', '$senha', '$tipo')";


        if ($conn->query($sql) === TRUE) {
            $mensagem = "Usuário <strong>$nome</strong> cadastrado com sucesso!";
            $classe_msg = "sucesso";
        } else {
            $mensagem = "Erro ao cadastrar: " . $conn->error;
            $classe_msg = "erro";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Usuário</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <div class="container">
        <a href="index.php" style="font-size: 14px; text-decoration: none; color: #666;">&larr; Voltar ao Login</a>
        
        <h2>Novo Usuário</h2>

        <?php if(!empty($mensagem)): ?>
            <div class="mensagem <?php echo $classe_msg; ?>">
                <?php echo $mensagem; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <label>Nome Completo:</label>
            <input type="text" name="nome" placeholder="Ex: João da Silva" required>

            <label>Login de Acesso:</label>
            <input type="text" name="login" placeholder="Ex: joao.silva" required>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Crie uma senha" required>

            <label>Nível de Acesso:</label>
            <select name="tipo" style="width: 100%; padding: 12px; margin-bottom: 20px; border-radius: 5px; border: 1px solid #ddd; background: white;">
                <option value="operador">Operador (Apenas lança horas)</option>
                <option value="admin">Administrador (Baixa relatórios)</option>
            </select>

            <button type="submit" class="btn-salvar">Cadastrar Usuário</button>
        </form>
    </div>

</body>
</html>