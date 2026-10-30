
<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$idProduto = filter_var(
    $_POST["id_produto"] ?? null,
    FILTER_VALIDATE_INT
);

$descricao = trim($_POST["descricao"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$valorCompra = $_POST["valor_compra"] ?? "";
$valorVenda = $_POST["valor_venda"] ?? "";
$qtdEstoque = filter_var(
    $_POST["qtd_estoque"] ?? null,
    FILTER_VALIDATE_INT
);

$categoriasPermitidas = [
    "Informática",
    "Eletrônicos",
    "Escritório",
    "Outros"
];

if (
    $idProduto === false || $idProduto === null || $idProduto <= 0 ||
    $descricao === "" ||
    !in_array($categoria, $categoriasPermitidas, true) ||
    !is_numeric($valorCompra) ||
    !is_numeric($valorVenda) ||
    $qtdEstoque === false || $qtdEstoque === null
) {
    die("Dados inválidos. Volte e confira os campos.");
}

if (
    (float)$valorCompra <= 0 ||
    (float)$valorVenda <= 0 ||
    $qtdEstoque < 0
) {
    die("Os preços devem ser maiores que zero e o estoque não pode ser negativo.");
}

$sql = "UPDATE produtos
        SET descricao = ?,
            categoria = ?,
            valor_compra = ?,
            valor_venda = ?,
            qtd_estoque = ?
        WHERE id_produto = ?";

$stmt = mysqli_prepare($conecta_db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssddii",
    $descricao,
    $categoria,
    $valorCompra,
    $valorVenda,
    $qtdEstoque,
    $idProduto
);

mysqli_stmt_execute($stmt);

mysqli_stmt_close($stmt);
mysqli_close($conecta_db);

header("Location: index.php");
exit;
?>
