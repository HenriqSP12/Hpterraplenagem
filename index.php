<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $login = $_POST['login'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM usuarios WHERE login = '$login' AND senha = '$senha'";

    $result = $conn->query($sql);

    if ($result->num_rows > 0){
        $row = $result -> fetch_assoc();

        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['nome_usuario'] = $row['nome'];

        $_SESSION['tipo_usuario'] = $row['tipo'];

        header("Location: painel.php");
    } else {
        $erro = "Credenciais incorretas.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - HP Terraplenagem</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="media/favicon.ico" type="image/x-icon">
</head>
<body>

    <div class="container">
        <img src="media/logo.png" alt="HP Terraplenagem" class="logo-login">
        <h2>Acesso do Operador</h2>
        
        <?php if(!empty($erro)): ?>
            <div class="mensagem erro"><?php echo $erro; ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>Login:</label>
            <input type="text" name="login" placeholder="Digite seu usuário" required>

            <label>Senha:</label>
            <input type="password" name="senha" placeholder="Digite sua senha" required>

            <button type="submit" class="btn-login">Entrar no Sistema</button>
        </form>
        
        </div>

</body>
</html>