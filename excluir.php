
<?php
include 'conexao.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$idProduto = filter_input(
    INPUT_POST,
    'id_produto',
    FILTER_VALIDATE_INT
);

if (!$idProduto || $idProduto <= 0) {
    die("ID do produto inválido.");
}

try {
    mysqli_begin_transaction($conecta_db);

    // Verifica se o produto existe.
    $sql = "SELECT id_produto
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

    // Impede excluir produtos que tenham vendas registradas.
    $sql = "SELECT COUNT(*) AS total
            FROM vendas
            WHERE id_produto = ?";

    $stmt = mysqli_prepare($conecta_db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);

    $resultado = mysqli_stmt_get_result($stmt);
    $vendas = mysqli_fetch_assoc($resultado);

    mysqli_stmt_close($stmt);

    if ((int)$vendas['total'] > 0) {
        throw new Exception(
            "Este produto possui vendas registradas e não pode ser excluído."
        );
    }

    // Exclui somente se não houver vendas associadas.
    $sql = "DELETE FROM produtos WHERE id_produto = ?";
    $stmt = mysqli_prepare($conecta_db, $sql);
    mysqli_stmt_bind_param($stmt, "i", $idProduto);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    mysqli_commit($conecta_db);
    mysqli_close($conecta_db);

    header("Location: index.php");
    exit;

} catch (Throwable $erro) {
    try {
        mysqli_rollback($conecta_db);
    } catch (Throwable $erroRollback) {
        // Evita que uma falha no rollback esconda o erro original.
    }

    http_response_code(400);
    ?>
    <!DOCTYPE html>
    <html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Não foi possível excluir</title>
    </head>
    <body style="font-family:Arial,sans-serif;padding:30px;">
        <h2>Não foi possível excluir o produto</h2>

        <p>
            <?= htmlspecialchars(
                $erro->getMessage(),
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>

        <p><a href="index.php">Voltar para a tela inicial</a></p>
    </body>
    </html>
    <?php
}

if (isset($conecta_db) && $conecta_db instanceof mysqli) {
    mysqli_close($conecta_db);
}
?>
