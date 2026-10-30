
<?php
include 'conexao.php';

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    header("Location: index.php");
    exit;
}

$descricao = trim($_POST["descricao"] ?? "");
$categoria = trim($_POST["categoria"] ?? "");
$valorCompra = $_POST["valor_compra"] ?? "";
$valorVenda = $_POST["valor_venda"] ?? "";
$qtdEstoque = $_POST["qtd_estoque"] ?? "";

$categoriasPermitidas = [
    "Informática",
    "Eletrônicos",
    "Escritório",
    "Outros"
];

if (
    $descricao === "" ||
    !in_array($categoria, $categoriasPermitidas, true) ||
    !is_numeric($valorCompra) ||
    !is_numeric($valorVenda) ||
    filter_var($qtdEstoque, FILTER_VALIDATE_INT) === false
) {
    die("Dados inválidos. Volte e confira os campos.");
}

if (
    (float)$valorCompra <= 0 ||
    (float)$valorVenda <= 0 ||
    (int)$qtdEstoque < 0
) {
    die("Os valores devem ser positivos e o estoque não pode ser negativo.");
}

$sql = "INSERT INTO produtos
        (descricao, categoria, valor_compra, valor_venda, qtd_estoque)
        VALUES (?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conecta_db, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "ssddi",
    $descricao,
    $categoria,
    $valorCompra,
    $valorVenda,
    $qtdEstoque
);

if (mysqli_stmt_execute($stmt)) {
    mysqli_stmt_close($stmt);
    mysqli_close($conecta_db);

    header("Location: index.php");
    exit;
}

die("Erro ao cadastrar produto.");
?>
