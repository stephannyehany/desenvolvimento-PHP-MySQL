
<?php
include 'conexao.php';

$sql = "SELECT id_produto, descricao, valor_venda, qtd_estoque
        FROM produtos
        ORDER BY descricao";

$resultado = mysqli_query($conecta_db, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Registrar Venda</title>

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

    <h2>Registrar Venda</h2>

    <form action="processa_venda.php" method="POST">

        <label for="id_produto">Selecione o produto:</label>

        <select name="id_produto" id="id_produto" required>
            <option value="">Escolha um produto</option>

            <?php while ($produto = mysqli_fetch_assoc($resultado)) { ?>
                <option value="<?= $produto['id_produto'] ?>">
                    <?= htmlspecialchars($produto['descricao']) ?>
                    - R$ <?= number_format($produto['valor_venda'], 2, ',', '.') ?>
                    - Estoque: <?= $produto['qtd_estoque'] ?>
                </option>
            <?php } ?>
        </select>

        <label for="quantidade">Quantidade vendida:</label>
        <input
            type="number"
            name="quantidade"
            id="quantidade"
            min="1"
            step="1"
            required
        >

        <button type="submit" class="botao">
            Confirmar Venda
        </button>

        <a href="index.php" class="voltar">Voltar</a>

    </form>

</div>
</body>
</html>
