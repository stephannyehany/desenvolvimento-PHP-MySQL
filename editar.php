
<?php
include 'conexao.php';

$idProduto = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$idProduto || $idProduto <= 0) {
    die("ID do produto inválido.");
}

$sql = "SELECT * FROM produtos WHERE id_produto = ?";
$stmt = mysqli_prepare($conecta_db, $sql);

mysqli_stmt_bind_param($stmt, "i", $idProduto);
mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);
$produto = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);

if (!$produto) {
    die("Produto não encontrado.");
}

$categorias = [
    "Informática",
    "Eletrônicos",
    "Escritório",
    "Outros"
];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Produto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #3068e8;
            padding-bottom: 10px;
        }

        label {
            display: block;
            margin-top: 15px;
            font-weight: bold;
        }

        input, select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            box-sizing: border-box;
        }

        .botao {
            background: #3068e8;
            color: white;
            padding: 12px 18px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-top: 20px;
        }

        .voltar {
            display: inline-block;
            margin-left: 10px;
            color: #3068e8;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Editar Produto</h2>

    <form action="atualizar.php" method="POST">

        <input
            type="hidden"
            name="id_produto"
            value="<?= (int)$produto['id_produto'] ?>"
        >

        <label for="descricao">Descrição:</label>
        <input
            type="text"
            id="descricao"
            name="descricao"
            maxlength="255"
            value="<?= htmlspecialchars($produto['descricao'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="categoria">Categoria:</label>
        <select id="categoria" name="categoria" required>
            <?php foreach ($categorias as $categoria) { ?>
                <option
                    value="<?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?>"
                    <?= $produto['categoria'] === $categoria ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($categoria, ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php } ?>
        </select>

        <label for="valor_compra">Valor de Compra (R$):</label>
        <input
            type="number"
            id="valor_compra"
            name="valor_compra"
            min="0.01"
            step="0.01"
            value="<?= htmlspecialchars((string)$produto['valor_compra'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="valor_venda">Valor de Venda (R$):</label>
        <input
            type="number"
            id="valor_venda"
            name="valor_venda"
            min="0.01"
            step="0.01"
            value="<?= htmlspecialchars((string)$produto['valor_venda'], ENT_QUOTES, 'UTF-8') ?>"
            required
        >

        <label for="qtd_estoque">Quantidade em Estoque:</label>
        <input
            type="number"
            id="qtd_estoque"
            name="qtd_estoque"
            min="0"
            step="1"
            value="<?= (int)$produto['qtd_estoque'] ?>"
            required
        >

        <button type="submit" class="botao">
            Salvar Alterações
        </button>

        <a href="index.php" class="voltar">Cancelar</a>
    </form>
</div>

</body>
</html>
