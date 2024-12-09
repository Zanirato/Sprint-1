<?php
// Inclui o arquivo que valida a sessão do usuário
include('valida_sessao.php');

// Inclui o arquivo de conexão com o banco de dados
include('conexao.php');

// Verifica se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];

    // Processa o upload da imagem
    // Prepara a query SQL para inserção ou atualização
    if ($id) {
        // Se o ID existe, é uma atualização
        $sql = "UPDATE fornecedores SET nome='$nome', email='$email', telefone='$telefone'";
        $sql .= " WHERE id_fornecedor='$id'";
        $mensagem = "Fornecedor atualizado com sucesso!";
    } else {
        // Se não há ID, é uma nova inserção
        $sql = "INSERT INTO fornecedores (nome, email, telefone) VALUES ('$nome', '$email', '$telefone')";
        $mensagem = "Fornecedor cadastrado com sucesso!";
    }

    // Executa a query e verifica se houve erro
    if ($conn->query($sql) === TRUE) {
        $mensagem = $mensagem;
    } else {
        $mensagem = "Erro: " . $conn->error;
    }
}

// Verifica se foi solicitada a exclusão de um fornecedor
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];

    // Verifica se o fornecedor tem produtos cadastrados
    $check_produtos = $conn->query("SELECT COUNT(*) as count FROM produtos WHERE id_fornecedor = '$delete_id'")->fetch_assoc();

    if ($check_produtos['count'] > 0) {
        $mensagem = "Não é possível excluir este fornecedor pois existem produtos cadastrados para ele.";
    } else {
        $sql = "DELETE FROM fornecedores WHERE id_fornecedor='$delete_id'";
        if ($conn->query($sql) === TRUE) {
            $mensagem = "Fornecedor excluído com sucesso!";
        } else {
            $mensagem = "Erro ao excluir fornecedor: " . $conn->error;
        }
    }
}

// Busca todos os fornecedores para listar na tabela
$fornecedores = $conn->query("SELECT * FROM fornecedores");

// Se foi solicitada a edição de um fornecedor, busca os dados dele
$fornecedor = null;
if (isset($_GET['edit_id'])) {
    $edit_id = $_GET['edit_id'];
    $fornecedor = $conn->query("SELECT * FROM fornecedores WHERE id_fornecedor='$edit_id'")->fetch_assoc();
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Cadastro de Fornecedor</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="shortcut icon" href="img-e-video/logo-dogma.jpg" type="image/jpg">
</head>

<body>
    <header>
        <img src="img-e-video/Logo-dogma-preto.jpeg" alt="Logo dogma">
        <h1>Sistema de Cadastro</h1>
    </header>
    <div class="container" style="width: 900px;">
        <!-- Formulário para cadastro/edição de fornecedor -->
        <form method="post" action="" enctype="multipart/form-data" class="form">
            <h2>Cadastro de Fornecedor</h2>
            <input type="hidden" name="id" value="<?php echo $fornecedor['id_fornecedor'] ?? ''; ?>">

            <input type="text" name="nome" id="nome" value="<?php echo $fornecedor['nome'] ?? ''; ?>" required placeholder="Nome">

            <input type="email" name="email" id="email" value="<?php echo $fornecedor['email'] ?? ''; ?>" placeholder="Email">

            <input type="text" name="telefone" id="telefone" value="<?php echo $fornecedor['telefone'] ?? ''; ?>" placeholder="Telefone">

            <button type="submit"><?php echo $fornecedor ? 'Atualizar' : 'Cadastrar'; ?></button>

            <!-- Exibe mensagens de sucesso ou erro -->
        <?php
        if (isset($mensagem)) echo "<p class='message " . (strpos($mensagem, 'Erro') !== false ? "error" : "success") . "'>$mensagem</p>";
        if (isset($mensagem_erro)) echo "<p class='message error'>$mensagem_erro</p>";
        ?>

            <h2>Listagem de Fornecedores</h2>
            <!-- Tabela para listar os fornecedores cadastrados -->
            <table>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th>Email</th>
                    <th>Ações</th>
                </tr>
                <?php while ($row = $fornecedores->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id_fornecedor']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td>
                            <?php
                            echo preg_replace('/(\d{2})(\d{5})(\d{4})/', '($1) $2-$3', $row['telefone']);
                            ?>
                        </td>
                        <td><?php echo $row['email']; ?></td>
                        <td>
                            <a href="?edit_id=<?php echo $row['id_fornecedor']; ?>">Editar</a>
                            <a href="?delete_id=<?php echo $row['id_fornecedor']; ?>" onclick="return confirm('Tem certeza que deseja excluir?')">Excluir</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
            <div class="actions">
            <a href="pagina.php" class="back-button">Voltar</a>
        </div>
        </form>
        
    </div>
</body>

</html>