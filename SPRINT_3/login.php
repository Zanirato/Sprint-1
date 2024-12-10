<?php
session_start();
include('conexao.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome'];
    $senha =($_POST['senha']);

    $sql = "SELECT * FROM funcionarios WHERE nome='$nome' AND senha='$senha'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $_SESSION['nome'] = $nome;
        header('Location: pagina.php');
    } else {
        $error = "Usuario ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-bt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Login</title>
    <link rel="shortcut icon" href="img-e-video/logo-dogma.jpg" type="image/jpg">
</head>

<body>
    <header>
        <img src="img-e-video/Logo-dogma-preto.jpeg" alt="Logo dogma">
        <h1>Login</h1>
    </header>
    <div class="container">
       
        <form action="" method="post"> <h2>Login</h2>
            <label for="usuario">Usuário:</label>
            <input type="text" name="nome" required placeholder="Insira o nome de usuário:">
            <label for="senha">Senha:</label>
            <input type="password" name="senha" required placeholder="Insira sua senha:">
            <button type="submit" style="margin-bottom: 30px;">Entrar</button>
            <?php if (isset($error)) echo "<p class='message error'>$error</p>"; ?>
        </form>
    </div>
</body>

</html>