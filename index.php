
<?php
include 'conexao.php';

$sql = "SELECT * FROM produtos ORDER BY id_produto";
$resultado = mysqli_query($conecta_db, $sql);
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Controle de Produtos</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 30px;
            color: #222;
        }

        .container {
            max-width: 1150px;
            margin: 0 auto;
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #3068e8;
            padding-bottom: 10px;
            margin-top: 20px;
        }

        h2:first-child {
            margin-top: 20px;
        }

        .botoes-principais {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin: 15px 0 20px;
        }

        .botao {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 40px;
            padding: 10px 16px;
            border: none;
            border-radius: 5px;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            cursor: pointer;
            transition: background 0.2s;
            white-space: nowrap;
        }

        .botao-cadastrar {
            background: #3068e8;
        }

        .botao-cadastrar:hover {
            background: #2454c5;
        }

        .botao-venda {
            background: #3068e8;
        }

        .botao-venda:hover {
            background: #2454c5;
        }

        .botao-editar {
            background: #e5a027;
        }

        .botao-editar:hover {
            background: #c48617;
        }

        .botao-excluir {
            background: #dc3545;
        }

        .botao-excluir:hover {
            background: #b92332;
        }

        .tabela-responsiva {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            text-align: left;
            vertical-align: middle;
        }

        th {
            background: #edf2ff;
            color: #222;
            font-weight: bold;
        }

        tbody tr:hover {
            background: #f8faff;
        }

        .coluna-acoes {
            width: 190px;
            min-width: 190px;
            text-align: center;
        }

        .acoes {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            white-space: nowrap;
        }

        .acoes .botao {
            min-height: 34px;
            padding: 8px 12px;
            font-size: 13px;
        }

        .sem-produtos {
            text-align: center;
            padding: 25px;
            color: #666;
        }

        @media (max-width: 700px) {
            body {
                padding: 12px;
            }

            .container {
                padding: 16px;
            }

            th, td {
                padding: 10px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Cadastro de Produto</h2>

    <p>Bem-vindo ao sistema de controle de produtos.</p>

    <div class="botoes-principais">
    <a href="cadastro.php" class="botao botao-cadastrar">
        Cadastrar Produto
    </a>

    <a href="venda.php" class="botao botao-venda">
        Registrar Venda
    </a>

    <a href="historico_vendas.php" class="botao botao-venda">
        Histórico de Vendas
    </a>
</div>

    <h2>Produtos Cadastrados</h2>

    <div class="tabela-responsiva">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Categoria</th>
                    <th>Valor de Compra</th>
                    <th>Valor de Venda</th>
                    <th>Estoque</th>
                    <th class="coluna-acoes">Ações</th>
                </tr>
            </thead>

            <tbody>
            <?php if (mysqli_num_rows($resultado) > 0) { ?>

                <?php while ($produto = mysqli_fetch_assoc($resultado)) { ?>
                    <tr>
                        <td><?= (int)$produto['id_produto'] ?></td>

                        <td>
                            <?= htmlspecialchars(
                                $produto['descricao'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            <?= htmlspecialchars(
                                $produto['categoria'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td>
                            R$ <?= number_format(
                                (float)$produto['valor_compra'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td>
                            R$ <?= number_format(
                                (float)$produto['valor_venda'],
                                2,
                                ',',
                                '.'
                            ) ?>
                        </td>

                        <td><?= (int)$produto['qtd_estoque'] ?></td>

                        <td class="coluna-acoes">
                            <div class="acoes">

                                <a
                                    href="editar.php?id=<?= (int)$produto['id_produto'] ?>"
                                    class="botao botao-editar"
                                >
                                    Editar
                                </a>

                                <form
                                    action="excluir.php"
                                    method="POST"
                                    style="display:inline; margin:0;"
                                    onsubmit="return confirm('Tem certeza que deseja excluir este produto?');"
                                >
                                    <input
                                        type="hidden"
                                        name="id_produto"
                                        value="<?= (int)$produto['id_produto'] ?>"
                                    >

                                    <button
                                        type="submit"
                                        class="botao botao-excluir"
                                    >
                                        Excluir
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                <?php } ?>

            <?php } else { ?>

                <tr>
                    <td colspan="7" class="sem-produtos">
                        Nenhum produto cadastrado.
                    </td>
                </tr>

            <?php } ?>
            </tbody>
        </table>
    </div>

</div>
</body>
</html>
