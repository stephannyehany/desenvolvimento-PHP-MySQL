<?php
include 'conexao.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: venda.php");
    exit;
}

$idProduto = filter_var(
    $_POST["id_produto"] ?? null,
    FILTER_VALIDATE_INT
);

$quantidade = filter_var(
    $_POST["quantidade"] ?? null,
    FILTER_VALIDATE_INT
);

if (
    $idProduto === false ||
    $idProduto === null ||
    $idProduto <= 0 ||
    $quantidade === false ||
    $quantidade === null ||
    $quantidade <= 0
) {
    die("Dados inválidos. Volte e confira o produto e a quantidade.");
}

try {
    mysqli_begin_transaction($conecta_db);

    // Busca o produto e bloqueia sua linha durante a venda.
    $sql = "SELECT valor_venda, qtd_estoque
            FROM produtos
            WHERE id_produto = ?
            FOR UPDATE";

    $stmt = mysqli_prepare($conecta_db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $produto = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);

    if (!$produto) {
        throw new Exception("Produto não encontrado.");
    }

    $estoqueAtual = (int) $produto["qtd_estoque"];
    $valorUnitario = (float) $produto["valor_venda"];

    // Confere se existe estoque suficiente.
    if ($quantidade > $estoqueAtual) {
        throw new Exception(
            "Estoque insuficiente! Quantidade disponível: " . $estoqueAtual
        );
    }

    // Calcula o valor total da venda.
    $total = round($valorUnitario * $quantidade, 2);

    // Registra a venda.
    $sql = "INSERT INTO vendas
            (id_produto, quantidade, valor_unitario, total)
            VALUES (?, ?, ?, ?)";

    $stmt = mysqli_prepare($conecta_db, $sql);

    mysqli_stmt_bind_param(
        $stmt,
        "iidd",
        $idProduto,
        $quantidade,
        $valorUnitario,
        $total
    );

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Desconta a quantidade vendida do estoque.
    $sql = "UPDATE produtos
            SET qtd_estoque = qtd_estoque - ?
            WHERE id_produto = ?";

    $stmt = mysqli_prepare($conecta_db, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $quantidade, $idProduto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    // Confirma as duas operações no banco de dados.
    mysqli_commit($conecta_db);

    mysqli_close($conecta_db);

    echo "<h2>Venda realizada com sucesso!</h2>";
    echo "<p>Quantidade vendida: " . $quantidade . "</p>";
    echo "<p>Valor unitário: R$ " .
         number_format($valorUnitario, 2, ',', '.') . "</p>";
    echo "<p>Total da venda: R$ " .
         number_format($total, 2, ',', '.') . "</p>";
    echo "<p><a href='index.php'>Voltar para a tela inicial</a></p>";

    } catch (Throwable $erro) {

    if (mysqli_connect_errno() === 0) {
        mysqli_rollback($conecta_db);
    }

    echo "<h2>Erro ao realizar a venda</h2>";

    echo "<p>Tipo de erro:</p>";
    echo "<pre>" . htmlspecialchars(get_class($erro)) . "</pre>";

    echo "<p>Mensagem:</p>";
    echo "<pre>" . htmlspecialchars((string)$erro->getMessage()) . "</pre>";

    echo "<p>Código:</p>";
    echo "<pre>" . htmlspecialchars((string)$erro->getCode()) . "</pre>";

    echo "<p><a href='venda.php'>Voltar para a venda</a></p>";
}

?>
