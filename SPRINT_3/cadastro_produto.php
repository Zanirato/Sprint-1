<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');
// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Verifica se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id_produto'] ?? '';
    $fornecedor = $_POST['id_fornecedor'];
    $nome = $_POST['nome'];
    $descricao = $_POST['descricao'];
    $preco = str_replace(',', '.', $_POST['preco_unitario']); // Converte vírgula para ponto

    // Prepara a query SQL para inserção ou atualização
    if ($id) {
        // Atualização de produto existente
        $sql = "UPDATE produtos SET id_fornecedor=?, nome=?, descricao=?, preco_unitario=? WHERE id_produto=?";
        $stmt = $conn->prepare($sql);
        // Ajuste do número e tipos de parâmetros
        $stmt->bind_param("issdi", $fornecedor, $nome, $descricao, $preco, $id);
        // "i" para id_fornecedor e id_produto (inteiros), "s" para strings, "d" para preco_unitario (decimal)
        $mensagem = "Produto atualizado com sucesso!";
    } else {
        // Inserção de novo produto
        $sql = "INSERT INTO produtos (id_fornecedor, nome, descricao, preco_unitario) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        // Ajuste do número e tipos de parâmetros
        $stmt->bind_param("issd", $fornecedor, $nome, $descricao, $preco);
        // "i" para id_fornecedor (inteiro), "s" para strings, "d" para preco_unitario (decimal)
        $mensagem = "Produto cadastrado com sucesso!";
    }


    // Executa a query e verifica se houve erro
    if ($stmt->execute()) {
        $class = "success";
    } else {
        $mensagem = "Erro: " . $stmt->error;
        $class = "error";
    }
}

// Verifica se foi solicitada a exclusão de um produto
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $sql = "DELETE FROM produtos WHERE id_produto=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        $mensagem = "Produto excluído com sucesso!";
        $class = "success";
    } else {
        $mensagem = "Erro ao excluir produto: " . $stmt->error;
        $class = "error";
    }
}

// Busca todos os produtos para listar na tabela
$produtos = $conn->query("SELECT p.id_produto, p.nome, p.descricao, p.preco_unitario, f.nome AS fornecedor_nome FROM produtos p JOIN fornecedores f ON p.id_fornecedor = f.id_fornecedor");


// Se foi solicitada a edição de um produto, busca os dados dele
$produto = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $stmt = $conn->prepare("SELECT * FROM produtos WHERE id_produto=?");
    $stmt->bind_param("i", $edit_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto = $result->fetch_assoc();
    $stmt->close();
}

// Busca todos os fornecedores para o select do formulário
$fornecedores = $conn->query("SELECT id_fornecedor, nome FROM fornecedores");
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Produto</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img-e-video/logo-dogma.jpg" type="image/jpg">
</head>

<body>
    <header>
        <img src="img-e-video/Logo-dogma-preto.jpeg" alt="Logo dogma">
        <h1>Cadastro de Produtos</h1>
    </header>
    <div class="container">

        <!-- Formulário para cadastro/edição de produto -->
        <form method="post" action="" enctype="multipart/form-data" class="form2">
            <h2>Cadastro de Produto</h2>
            <input type="hidden" name="id" value="<?php echo $produto['id_produto'] ?? ''; ?>">

            <label>Fornecedor:</label>
            <select name="id_fornecedor" required>
                <?php while ($row = $fornecedores->fetch_assoc()): ?>
                    <option value="<?php echo $row['id_fornecedor']; ?>" <?php if ($produto && $produto['id_fornecedor'] == $row['id_fornecedor']) echo 'selected'; ?>><?php echo $row['nome']; ?></option>
                <?php endwhile; ?>
            </select>

            <label>Nome:</label>
            <input type="text" name="nome" value="<?php echo $produto['nome'] ?? ''; ?>" required placeholder="Insira o Nome">

            <label>Descrição:</label>
            <textarea name="descricao" placeholder="Insira a Descrição"><?php echo $produto['descricao'] ?? ''; ?></textarea>

            <label>Preço:   </label>
            <input type="text" name="preco_unitario" value="<?php echo $produto['preco_unitario'] ?? ''; ?>" required id="preco" placeholder="Insira o Valor">

            <button type="submit"><?php echo $produto ? 'Atualizar' : 'Cadastrar'; ?></button>
            <?php if (isset($mensagem)): ?>
                <p class="message <?php echo $class; ?>"><?php echo $mensagem; ?></p>
            <?php endif; ?>
           <a href="pagina.php" class="back-button">Voltar</a>
        </form>
         
    </div>
</body>

</html>