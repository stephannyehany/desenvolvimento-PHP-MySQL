
<?php
include 'conexao.php';

// Busca todas as vendas e os nomes dos produtos.
$sql = "SELECT
            v.id_venda,
            v.data_venda,
            v.quantidade,
            v.valor_unitario,
            v.total,
            p.descricao
        FROM vendas v
        LEFT JOIN produtos p
            ON v.id_produto = p.id_produto
        ORDER BY v.data_venda DESC, v.id_venda DESC";

$resultado = mysqli_query($conecta_db, $sql);

// Calcula a quantidade de vendas e o faturamento total.
$sqlResumo = "SELECT
                  COUNT(*) AS quantidade_vendas,
                  COALESCE(SUM(total), 0) AS faturamento
              FROM vendas";

$resultadoResumo = mysqli_query($conecta_db, $sqlResumo);
$resumo = mysqli_fetch_assoc($resultadoResumo);

$quantidadeVendas = (int)$resumo['quantidade_vendas'];
$faturamento = (float)$resumo['faturamento'];
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Histórico de Vendas</title>

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
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.04);
        }

        h2 {
            color: #333;
            border-bottom: 2px solid #3068e8;
            padding-bottom: 10px;
        }

        .resumo {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
            margin: 25px 0;
        }

        .cartao {
            flex: 1;
            min-width: 220px;
            background: #edf2ff;
            border-left: 4px solid #3068e8;
            border-radius: 6px;
            padding: 20px;
        }

        .cartao p {
            margin: 0 0 10px;
            color: #555;
            font-size: 14px;
        }

        .cartao strong {
            font-size: 24px;
            color: #234fb7;
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
        }

        tbody tr:hover {
            background: #f8faff;
        }

        .botao {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #3068e8;
            color: white;
            text-decoration: none;
            padding: 11px 16px;
            border-radius: 5px;
            font-size: 13px;
            margin-top: 15px;
        }

        .botao:hover {
            background: #2454c5;
        }

        .vazio {
            text-align: center;
            padding: 30px;
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
                padding: 9px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <h2>Histórico de Vendas</h2>

    <p>Consulte as vendas registradas no sistema.</p>

    <div class="resumo">
        <div class="cartao">
            <p>Total de vendas registradas</p>
            <strong><?= $quantidadeVendas ?></strong>
        </div>

        <div class="cartao">
            <p>Faturamento total registrado</p>
            <strong>
                R$ <?= number_format($faturamento, 2, ',', '.') ?>
            </strong>
        </div>
    </div>

    <h2>Vendas Realizadas</h2>

    <div class="tabela-responsiva">
        <table>
            <thead>
                <tr>
                    <th>ID da Venda</th>
                    <th>Data e Hora</th>
                    <th>Produto</th>
                    <th>Quantidade</th>
                    <th>Valor Unitário</th>
                    <th>Total</th>
                </tr>
            </thead>

            <tbody>
                <?php if (mysqli_num_rows($resultado) > 0) { ?>

                    <?php while ($venda = mysqli_fetch_assoc($resultado)) { ?>
                        <tr>
                            <td><?= (int)$venda['id_venda'] ?></td>

                            <td>
                                <?php
                                $data = strtotime($venda['data_venda']);
                                echo $data
                                    ? date('d/m/Y H:i', $data)
                                    : 'Data indisponível';
                                ?>
                            </td>

                            <td>
                                <?= htmlspecialchars(
                                    $venda['descricao'] ?? 'Produto não encontrado',
                                    ENT_QUOTES,
                                    'UTF-8'
                                ) ?>
                            </td>

                            <td><?= (int)$venda['quantidade'] ?></td>

                            <td>
                                R$ <?= number_format(
                                    (float)$venda['valor_unitario'],
                                    2,
                                    ',',
                                    '.'
                                ) ?>
                            </td>

                            <td>
                                <strong>
                                    R$ <?= number_format(
                                        (float)$venda['total'],
                                        2,
                                        ',',
                                        '.'
                                    ) ?>
                                </strong>
                            </td>
                        </tr>
                    <?php } ?>

                <?php } else { ?>

                    <tr>
                        <td colspan="6" class="vazio">
                            Nenhuma venda registrada até o momento.
                        </td>
                    </tr>

                <?php } ?>
            </tbody>
        </table>
    </div>

    <a href="index.php" class="botao">
        Voltar para a tela inicial
    </a>

</div>
</body>
</html>
