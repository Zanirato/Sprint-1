<!-- 4ª Digitação (Aqui) -->

<?php include('valida_sessao.php'); ?>
<?php include('conexao.php'); ?>

<?php
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM produtos WHERE id_produto='$delete_id'";
    if ($conn->query($sql) === TRUE) {
        $mensagem = "Produto excluído com sucesso!";
    } else {
        $mensagem = "Erro ao excluir produto: " . $conn->error;
    }
}
$produtos = $conn->query("SELECT p.id_produto, p.nome, p.descricao, p.preco_unitario, f.nome AS fornecedor FROM produtos p JOIN fornecedores f ON p.id_fornecedor = f.id_fornecedor ");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Listagem de Produtos</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img-e-video/logo-dogma.jpg" type="image/jpg">
</head>

<body>
<header>
        <img src="img-e-video/Logo-dogma-preto.jpeg" alt="Logo dogma">
        <h1>Listagem de Produtos</h1>
    </header>
    <div class="container">
        <form class="form3">
            <h2>Listagem de Produtos</h2>
            <?php if (isset($mensagem)) echo "<p class='message " . ($conn->error ? "error" : "success") . "'>$mensagem</p>"; ?>
            <table class="tabela">
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Fornecedor</th>
                    <th>Ações</th>
                </tr>
                <?php while ($row = $produtos->fetch_assoc()): ?>
                    <tr>
                    <td><?php echo $row['id_produto']; ?></td>
                            <td><?php echo $row['nome']; ?></td>
                            <td><?php echo $row['descricao']; ?></td>
                            <td><?php echo 'R$ ' . number_format($row['preco_unitario'], 2, ',', '.'); ?></td>
                            <td><?php echo $row['fornecedor']; ?></td>
                            <td>
                                <a href="cadastro_produto.php?edit_id=<?php echo $row['id_produto']; ?>">Editar</a>
                                <a href="?delete_id=<?php echo $row['id_produto']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                            </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <div class="backbtn"><a href="pagina.php" class="back">Voltar</a></div>
        </form>
    </div>
    
</body>

</html>