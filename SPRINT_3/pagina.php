<?php include('valida_sessao.php'); ?>
<!-- Inclui o arquivo 'valida_sessao.php' para garantir que o usuário esteja autenticado -->

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Painel Principal</title>
    <!-- Link para o arquivo CSS para estilização da página -->
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img-e-video/logo-dogma.jpg" type="image/jpg">
</head>

<body>
    <header>
        <img src="img-e-video/Logo-dogma-preto.jpeg" alt="Logo dogma">
        <h1>Sistema de Cadastro</h1>
    </header>
    <div class="container">
        <!-- Exibe uma mensagem de boas-vindas com o nome do usuário logado -->

        <div class="menu">
            <h2>Bem-Vindo, <?php echo $_SESSION['nome']; ?>!</h2>
            <a href="cadastro_fornecedor.php"><input type="button" value="Cadastro de Fornecedores"></a>            
            <a href="cadastro_produto.php"><input type="button" value="Cadastro de produtos"></a>
            <a href="listagem_produtos.php"><input type="button" value="Listagem de produtos"></a>
            <a href="login.php"><input type="button" value="Sair" id="sair"></a>
        </div>
    </div>
</body>

</html>