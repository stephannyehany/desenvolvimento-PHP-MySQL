<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cadastro de Produto</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            padding: 30px;
        }

        .container {
            max-width: 900px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 8px #ddd;
        }

        h2 {
            border-bottom: 2px solid #3068e8;
            padding-bottom: 12px;
        }

        .formulario {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .campo {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }

        label {
            font-size: 14px;
            font-weight: bold;
        }

        input, select {
            padding: 11px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .botao {
            margin-top: 22px;
            padding: 12px 18px;
            background: #3068e8;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .voltar {
            display: inline-block;
            margin-left: 10px;
            color: #3068e8;
        }

        @media (max-width: 600px) {
            .formulario {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Cadastro de Produto</h2>

    <form action="controle_produto.php" method="POST">

        <div class="formulario">

            <div class="campo">
                <label>Descrição do Produto</label>
                <input type="text" name="descricao"
                       maxlength="100" required
                       placeholder="Ex.: Notebook Samsung">
            </div>

            <div class="campo">
                <label>Categoria</label>
                <select name="categoria" required>
                    <option value="">Selecione uma categoria</option>
                    <option value="Informática">Informática</option>
                    <option value="Eletrônicos">Eletrônicos</option>
                    <option value="Escritório">Escritório</option>
                    <option value="Outros">Outros</option>
                </select>
            </div>

            <div class="campo">
                <label>Valor de Compra (R$)</label>
                <input type="number" name="valor_compra"
                       min="0.01" step="0.01" required>
            </div>

            <div class="campo">
                <label>Valor de Venda (R$)</label>
                <input type="number" name="valor_venda"
                       min="0.01" step="0.01" required>
            </div>

            <div class="campo">
                <label>Quantidade em Estoque</label>
                <input type="number" name="qtd_estoque"
                       min="0" step="1" required>
            </div>

        </div>

        <button type="submit" class="botao">
            CADASTRAR PRODUTO
        </button>

        <a href="index.php" class="voltar">Voltar</a>

    </form>

</div>
</body>
</html>
